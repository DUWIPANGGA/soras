<?php

namespace App\Http\Requests\Recommendation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class GenerateRecommendationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Primary — wajib, tepat 1
            'primary_complaint_id' => [
                'required',
                'integer',
                'exists:complaints,id',
            ],

            // Secondary — opsional, array, max 3 item
            'secondary_complaint_ids' => [
                'nullable',
                'array',
                'max:3',
            ],

            // Setiap item secondary harus valid
            'secondary_complaint_ids.*' => [
                'integer',
                'exists:complaints,id',
            ],

            // Goal — wajib, tepat 1
            'goal_id' => [
                'required',
                'integer',
                'exists:goals,id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'primary_complaint_id.required'     => 'Keluhan utama wajib dipilih.',
            'primary_complaint_id.integer'      => 'Format keluhan utama tidak valid.',
            'primary_complaint_id.exists'       => 'Keluhan utama tidak ditemukan.',

            'secondary_complaint_ids.array'     => 'Keluhan tambahan harus berupa array.',
            'secondary_complaint_ids.max'       => 'Keluhan tambahan maksimal 3 item.',
            'secondary_complaint_ids.*.integer' => 'Format keluhan tambahan tidak valid.',
            'secondary_complaint_ids.*.exists'  => 'Salah satu keluhan tambahan tidak ditemukan.',

            'goal_id.required'                  => 'Tujuan latihan wajib dipilih.',
            'goal_id.integer'                   => 'Format tujuan latihan tidak valid.',
            'goal_id.exists'                    => 'Tujuan latihan tidak ditemukan.',
        ];
    }

    /**
     * Validasi tambahan setelah rules() lolos.
     * Di sini kita tangani logika yang tidak bisa
     * dihandle rules biasa.
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                $this->validateNoDuplicateSecondary($validator);
                $this->validatePrimaryNotInSecondary($validator);
            },
        ];
    }

    // ─── Custom Validators ────────────────────────────────────

    /**
     * Secondary tidak boleh ada ID yang duplikat.
     * Contoh invalid: [1, 2, 2] → ID 2 duplikat
     */
    private function validateNoDuplicateSecondary(Validator $validator): void
    {
        $secondaryIds = $this->input('secondary_complaint_ids', []);

        if (empty($secondaryIds)) {
            return;
        }

        $unique    = array_unique($secondaryIds);
        $duplicate = array_diff_assoc($secondaryIds, $unique);

        if (!empty($duplicate)) {
            $validator->errors()->add(
                'secondary_complaint_ids',
                'Keluhan tambahan tidak boleh mengandung ID yang duplikat.'
            );
        }
    }

    /**
     * Primary tidak boleh ada di list secondary.
     * Contoh invalid: primary=1, secondary=[1, 2] → ID 1 duplikat
     */
    private function validatePrimaryNotInSecondary(Validator $validator): void
    {
        $primaryId    = $this->input('primary_complaint_id');
        $secondaryIds = $this->input('secondary_complaint_ids', []);

        if (empty($secondaryIds) || empty($primaryId)) {
            return;
        }

        if (in_array((int) $primaryId, array_map('intval', $secondaryIds))) {
            $validator->errors()->add(
                'secondary_complaint_ids',
                'Keluhan tambahan tidak boleh sama dengan keluhan utama.'
            );
        }
    }
}
