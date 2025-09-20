<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
class Answer extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'question_id',
        'correct_answer',
        'name',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id')->with('transLocale');
    }

    public function scopeFilter(Builder $builder, array $filters): Builder
    {
        if (isset($filters['name'])) {
            $builder->where('name', 'like', '%' . $filters['name'] . '%');
        }
        $builder->when(isset($filters['correct_answer']), function ($builder) use ($filters) {
            $correct_answerValue = intval($filters['correct_answer']) == 0 ? 0 : $filters['correct_answer'];
            $builder->where('correct_answer', $correct_answerValue);
        });
        return $builder;
    }

}
