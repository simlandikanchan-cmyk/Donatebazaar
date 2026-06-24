<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CampaignUpdate extends Model



{


    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'title',
        'body',
        'description',
        'media_url',
        'created_by',
    ];

    

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }



}