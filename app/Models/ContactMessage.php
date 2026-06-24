<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    // Table name (optional if default)
    protected $table = 'contacts';

    // Mass assignable fields
    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'is_read', // optional (for future use)
    ];

    // Default values
    protected $attributes = [
        'is_read' => false,
    ];

    // Casting
    protected $casts = [
        'is_read' => 'boolean',
    ];
}