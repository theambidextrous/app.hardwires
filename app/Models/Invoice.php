<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'org',
        'item',
        'qty',
        'unit_cost',
        'cost',
        'due_date',
        'is_paid',
        'paid_sum',
        'balance',
        'path',
    ];
}
