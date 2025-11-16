<?php
namespace App\Models;

use App\Models\User;
use App\Models\Option;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Question extends Model
{

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'exam_id',
        'question_text',
        'mark',
        'status',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_ids')->with('transLocale');
    }

    // public function answers()
    // {
    //  return   $this->hasMany(Answer::class, 'question_id');
    // }

   public function options()
    {
        return $this->hasMany(Option::class,'question_id');
    }
    public function scopeFilter(Builder $builder, array $filters): Builder
    {
        if (isset($filters['name'])) {
            $builder->where('name', 'like', '%' . $filters['name'] . '%');
        }
        $builder->when(isset($filters['status']), function ($builder) use ($filters) {
            $statusValue = intval($filters['status']) == 0 ? 0 : $filters['status'];
            $builder->where('status', $statusValue);
        });
        return $builder;
    }

}
