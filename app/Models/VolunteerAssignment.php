<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VolunteerAssignment extends Model
{
    protected $fillable = [
        'volunteer_id',
        'campaign_id',
        'role',
        'start_date',
        'end_date',
        'status'
    ];

    public function volunteer()
    {
        return $this->belongsTo(Volunteer::class);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}