<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserComplaint extends Model
{
    protected $fillable = [
        'user_profile_id',
        'complaint_id',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    // ─── Relationships ───────────────────────────────────────

    public function userProfile()
    {
        return $this->belongsTo(UserProfile::class);
    }

    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }
}
