<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer',
        'section',
        'question',
        'label',
        'choice',
        'choice_text',
        'points',
        'running_sum',
        'is_active',
        'attempt',
    ];
}
