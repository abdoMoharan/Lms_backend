<?php
namespace App\Models;

use App\Models\Group;
use App\Models\GroupDay;
use App\Models\LessonsAttachment;
use Illuminate\Database\Eloquent\Model;

class GroupSession extends Model
{
    protected $fillable = ['date', 'group_id', 'day_id', 'lesson_id',
        'start_time', 'is_meeting_created'];

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
    public function groupDay()
    {
        return $this->belongsTo(GroupDay::class, 'day_id')->with('week');
    }
    public function lesson()
    {
        return $this->belongsTo(Lessons::class, 'lesson_id')->with('transLocale');
    }
    public function attachmentLesson()
    {
        return $this->hasMany(LessonsAttachment::class, 'group_session_id');
    }
    public function exams()
    {
        return $this->hasMany(Exam::class, 'group_session_id')->with(['questions','teacher']);
    }


}
