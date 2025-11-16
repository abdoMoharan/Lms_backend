<?php

namespace App\Models;

use App\Models\Exam;
use App\Models\ExamAnswer;
use Illuminate\Database\Eloquent\Model;

class ExamAttempt extends Model
{
    protected $fillable = ['user_id', 'exam_id', 'started_at', 'finished_at', 'score'];

    public function answers()
    {
        return $this->hasMany(ExamAnswer::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}
