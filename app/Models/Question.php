<?php
namespace App\Models;

use App\Models\User;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model implements TranslatableContract
{
    use Translatable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'question_type_id',
        'exam_id',
        'created_by',
        'updated_by',
        'status',
    ];
    public $translatedAttributes = [
        'question_id',
        'locale',
        'name',
    ];
    protected $translationForeignKey = 'question_id';

    public function transLocale()
    {
        $locale = app()->getLocale();
        return $this->hasMany(QuestionTranslation::class, 'question_id')->where('locale', $locale);
    }
    public function trans()
    {
        return $this->hasMany(QuestionTranslation::class, 'question_id');
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_ids')->with('transLocale');
    }
    public function question_type()
    {
        return $this->belongsTo(QuestionType::class, 'question_type_id')->with('transLocale');
    }
    public function scopeFilter(Builder $builder, array $filters): Builder
    {
        if (isset($filters['name'])) {
            $builder->whereHas('transLocale', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['name'] . '%');
            });
        }
        $builder->when(isset($filters['status']), function ($builder) use ($filters) {
            $statusValue = intval($filters['status']) == 0 ? 0 : $filters['status'];
            $builder->where('status', $statusValue);
        });
        return $builder;
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

}
