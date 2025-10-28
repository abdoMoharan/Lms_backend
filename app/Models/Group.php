<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    protected $fillable = [
        'group_name',
        'course_id',
        'teacher_id',
        'max_seats',
        'available_seats',
        'status',
        'session_status',
        'group_type',
        'hours_count',
        'number_lessons',
        'duration',
        'start_date',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }


    public function groupDays(): HasMany
    {
        return $this->hasMany(GroupDay::class, 'group_id');
    }
    public function groupSession(): HasMany
    {
        return $this->hasMany(GroupSession::class, 'group_id')->with(['lesson']);
    }
}
