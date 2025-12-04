<?php
namespace App\Models;

use App\Models\User;
use App\Models\Group;
use Illuminate\Database\Eloquent\Model;

class GroupRegister extends Model
{
    //
    protected $fillable = [
        'group_id',
        'user_id',
        'price',
    ];

//relations

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
