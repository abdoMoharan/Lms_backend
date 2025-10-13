<?php
namespace App\Models;

use App\Models\Subject;
use App\Models\Semester;
use Illuminate\Database\Eloquent\Model;

class SubjectSemester extends Model
{
    protected $fillable = ['subject_id', 'semester_id'];

    public function subject()
    {
        return $this->belongsTo(Subject::class,'subject_id')->with('transLocale');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class,'semester_id')->with('transLocale');
    }
}
