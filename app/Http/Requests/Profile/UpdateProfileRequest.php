<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'age'       => ['sometimes', 'integer', 'min:5', 'max:100'],
            'gender'    => ['sometimes', 'in:L,P'],
            'height_cm' => ['sometimes', 'numeric', 'min:50', 'max:250'],
            'weight_kg' => ['sometimes', 'numeric', 'min:10', 'max:300'],
        ];
    }

    public function messages(): array
    {
        return [
            'age.min'       => 'Usia minimal 5 tahun.',
            'age.max'       => 'Usia maksimal 100 tahun.',
            'gender.in'     => 'Jenis kelamin harus L atau P.',
            'height_cm.min' => 'Tinggi badan minimal 50 cm.',
            'height_cm.max' => 'Tinggi badan maksimal 250 cm.',
            'weight_kg.min' => 'Berat badan minimal 10 kg.',
            'weight_kg.max' => 'Berat badan maksimal 300 kg.',
        ];
    }
}
