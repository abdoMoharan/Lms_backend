<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseSemester extends Model
{
    protected $fillable = ['semester_id', 'course_id'];

    //relations
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id')->with('transLocale');
    }
    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class, 'semester_id')->with('transLocale');
    }
}
