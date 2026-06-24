<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Partnership extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'organization_name',
        'website',
        'partnership_type',
        'message',
        'document',
        'status',
        'admin_notes',

        //  NEW FIELDS
        'priority_score',
        'organization_type',
        'organization_size',
        'location',
        'goal',
        'timeline',
        'reviewed_at',
        'reviewed_by',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods (VERY IMPORTANT)
    |--------------------------------------------------------------------------
    */

    //  Priority Label (for UI)
    public function getPriorityLabelAttribute()
    {
        if ($this->priority_score >= 30) return 'High';
        if ($this->priority_score >= 10) return 'Medium';
        return 'Low';
    }

    // Priority Color (for badges)
    public function getPriorityColorAttribute()
    {
        if ($this->priority_score >= 30) return 'green';
        if ($this->priority_score >= 10) return 'yellow';
        return 'gray';
    }

    //  Check if document exists
    public function getHasDocumentAttribute()
    {
        return !empty($this->document);
    }

    //  Check if website exists
    public function getHasWebsiteAttribute()
    {
        return !empty($this->website);
    }

    //  Human readable time
    public function getSubmittedAgoAttribute()
    {
        return $this->created_at?->diffForHumans();
    }
}