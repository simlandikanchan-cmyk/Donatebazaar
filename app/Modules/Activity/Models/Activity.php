<?php

namespace App\Modules\Activity\Models;

use App\Models\Campaign;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'user_id',
        'campaign_id',
        'type',
        'title',
        'description',
        'image',
        'meta',
        'visibility',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}
