<?php
namespace App\Models;

use App\Models\Group;
use App\Models\GroupDay;
use Illuminate\Database\Eloquent\Model;

class GroupSession extends Model
{
    protected $fillable = ['date',  'group_id', 'day_id', 'lesson_id'];

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
    public function groupDay()
    {
        return $this->belongsTo(GroupDay::class, 'day_id');
    }
    public function lesson()
    {
        return $this->belongsTo(Lessons::class, 'lesson_id')->with('transLocale');
    }
}
