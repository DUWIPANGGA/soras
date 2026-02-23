<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    // Relasi ke olahraga lewat pivot complaint_exercise
    public function exercises()
    {
        return $this->belongsToMany(Exercise::class, 'complaint_exercise')
            ->withPivot('relevance_score')
            ->withTimestamps();
    }

    // Semua user complaint yang memakai keluhan ini
    public function userComplaints()
    {
        return $this->hasMany(UserComplaint::class);
    }
}
