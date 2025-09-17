<?php
namespace App\Models;

use App\Models\AnswerTranslation;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
class Answer extends Model implements TranslatableContract
{
    use Translatable;
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'question_id',
        'correct_answer',
    ];
    public $translatedAttributes = [
        'answer_id',
        'locale',
        'name',
    ];
    protected $translationForeignKey = 'answer_id';

    public function transLocale()
    {
        $locale = app()->getLocale();
        return $this->hasMany(AnswerTranslation::class, 'answer_id')->where('locale', $locale);
    }
    public function trans()
    {
        return $this->hasMany(AnswerTranslation::class, 'answer_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id')->with('transLocale');
    }

    public function scopeFilter(Builder $builder, array $filters): Builder
    {
        if (isset($filters['name'])) {
            $builder->whereHas('transLocale', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['name'] . '%');
            });
        }
        $builder->when(isset($filters['correct_answer']), function ($builder) use ($filters) {
            $correct_answerValue = intval($filters['correct_answer']) == 0 ? 0 : $filters['correct_answer'];
            $builder->where('correct_answer', $correct_answerValue);
        });
        return $builder;
    }

}
