<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupSession extends Model
{
    protected $fillable = ['date', 'start_time', 'session_time', 'group_id', 'day_id'];
}
