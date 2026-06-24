<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VolunteerApplication extends Model
{
    protected $fillable = [
        'volunteer_id',
        'campaign_id',
        'ngo_id',
        'message',
        'status',
        'applied_at'
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