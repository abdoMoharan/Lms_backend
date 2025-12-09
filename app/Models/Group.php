<?php
namespace App\Models;

use App\Models\GroupRegister;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
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
        return $this->belongsTo(Course::class, 'course_id')->with(['transLocale','coursePrice']);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function groupDays(): HasMany
    {
        return $this->hasMany(GroupDay::class, 'group_id')->with('week');
    }
    public function groupSession(): HasMany
    {
        return $this->hasMany(GroupSession::class, 'group_id')->with(['lesson', 'groupDay','attachmentLesson']);
    }
    public function groupRegisters(): HasMany
    {
        return $this->hasMany(GroupRegister::class, 'group_id')->with(['user']);
    }
    public function scopeFilter(Builder $builder, array $filters): Builder
    {
        if (isset($filters['group_name'])) {
            $builder->where('group_name', 'like', '%' . $filters['group_name'] . '%');
        }
        if (isset($filters['course_id'])) {
            $builder->where('course_id', $filters['course_id']);
        }
        if (isset($filters['teacher_id'])) {
            $builder->where('teacher_id', $filters['teacher_id']);
        }
        if (isset($filters['available_seats'])) {
            $builder->where('available_seats', '%' . $filters['available_seats'] . '%');
        }
        if (isset($filters['duration'])) {
            $builder->where('duration', '%' . $filters['duration'] . '%');
        }
        if (isset($filters['session_status'])) {
            $builder->where('session_status', $filters['session_status']);
        }
        if (isset($filters['group_type'])) {
            $builder->where('group_type', $filters['group_type']);
        }
        $builder->when(isset($filters['status']), function ($builder) use ($filters) {
            $statusValue = intval($filters['status']) == 0 ? 0 : $filters['status']; // استخدام `intval` لتحويل النصوص للأرقام
            $builder->where('status', $statusValue);
        });
        return $builder;
    }
}
