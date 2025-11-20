<?php
namespace App\Models;

use App\Models\Grade;
use App\Models\EducationalStage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CoursePrice extends Model
{
    public $fillable = [
        'course_id',
        'stage_id',
        'grade_id',
        'price'];

//relations
    public function stage(): BelongsTo
    {
        return $this->belongsTo(EducationalStage::class, 'stage_id')->with('transLocale');
    }
    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class, 'grade_id')->with('transLocale');
    }
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id')->with('transLocale');
    }

}
