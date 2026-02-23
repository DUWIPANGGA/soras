<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserGoal extends Model
{
    protected $fillable = [
        'user_profile_id',
        'goal_id',
    ];

    // ─── Relationships ───────────────────────────────────────

    public function userProfile()
    {
        return $this->belongsTo(UserProfile::class);
    }

    public function goal()
    {
        return $this->belongsTo(Goal::class);
    }
}
