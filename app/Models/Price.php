<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'normal',
        'discounted',
    ];

    public function getPrice()
    {
        return $this->find(1);
    }
}
