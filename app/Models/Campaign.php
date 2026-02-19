<?php

// namespace App\Models;

// use Illuminate\Database\Eloquent\Model;

// class Campaign extends Model
// {
//     //
// }
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'slug',
        'cover_image',
        'video_url',
        'goal_amount',
        'raised_amount',
        'location',
        'start_date',
        'end_date',
        'is_featured',
        'is_urgent',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}