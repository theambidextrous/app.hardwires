<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer',
        'amount',
        'currency',
        'ref',
        'ext_ref',
        'email',
        'paid_amount',
        'req_payload',
        'init_payload',
        'payload',
        'is_paid',
    ];
}
