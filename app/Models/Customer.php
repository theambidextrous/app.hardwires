<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'referral_cell',
        'hash',
        'is_active',
        'has_paid',
        'is_spec',
        'org_invoice',
    ];
}
