<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Volunteer extends Model
{
    protected $fillable = [
        'user_id','bio','skills','availability','city','state','country','is_verified'
    ];

    protected $casts = [
        'skills' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function applications()
    {
        return $this->hasMany(VolunteerApplication::class);
    }

    public function assignments()
    {
        return $this->hasMany(VolunteerAssignment::class);
    }
}