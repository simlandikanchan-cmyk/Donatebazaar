<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Volunteer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'phone', 'bio', 'skills', 'availability', 'city', 'state', 'country', 'is_verified',
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
