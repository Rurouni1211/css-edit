<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'contact_subject_type',
        'order_id',
        'subject',
        'body',
        'email_settings',
        'confirmed',
    ];
    protected $casts = [
        'email_settings' => 'boolean',
        'confirmed' => 'boolean',
    ];
}
