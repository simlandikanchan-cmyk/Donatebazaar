<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FundraiserLevelHistory extends Model
{
    public $timestamps = false; // only has created_at

    protected $fillable = [
        'user_id',
        'from_level_id',
        'to_level_id',
        'reason',
        'triggered_by',
        'admin_id',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user()       { return $this->belongsTo(User::class); }
    public function fromLevel()  { return $this->belongsTo(FundraiserLevel::class, 'from_level_id'); }
    public function toLevel()    { return $this->belongsTo(FundraiserLevel::class, 'to_level_id'); }
    public function admin()      { return $this->belongsTo(User::class, 'admin_id'); }
}