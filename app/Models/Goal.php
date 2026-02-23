<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Goal extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description'];

    // Relasi ke olahraga lewat pivot goal_exercise
    public function exercises()
    {
        return $this->belongsToMany(Exercise::class, 'goal_exercise')
            ->withPivot('relevance_score')
            ->withTimestamps();
    }

    // Semua user goal yang memakai goal ini
    public function userGoals()
    {
        return $this->hasMany(UserGoal::class);
    }
}
