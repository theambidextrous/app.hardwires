<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Progresitem extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer',
        'series',
        'prev_section',
        'next_section',
        'next_url',
        'has_finished',
        'attempt',
        'paid',
    ];
}
