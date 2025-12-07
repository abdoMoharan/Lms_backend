<?php

namespace App\Models;

use App\Models\User;
use App\Models\GroupRegister;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $table = 'subscriptions';
    protected $fillable = [
        'user_id',
        'group_register_id',
        'price',
        'start_date',
        'end_date',
        'status_payment',
        'status',
        'order',
    ];


//relations
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

public  function groupRegister()
{
    return $this->belongsTo(GroupRegister::class,'group_register_id');
}
}
