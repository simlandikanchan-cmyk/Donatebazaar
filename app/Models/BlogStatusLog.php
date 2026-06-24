<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogStatusLog extends Model
{
    protected $fillable = [
        'blog_id',
        'changed_by',
        'from_status',
        'to_status',
        'note'
    ];

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }

    public function actor()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}