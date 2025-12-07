<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';
    protected $fillable = [
        'user_id',
        'group_register_id',
        'price',
        'payment_type',
        'currency',
    ];
}
