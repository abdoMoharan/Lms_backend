<?php
namespace App\Models;

use App\Models\User;
use App\Models\Question;
use App\Models\GroupSession;
use App\Models\ExamTranslation;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Exam extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'group_session_id',
        'teacher_id',
        'duration',
        'start_date',
        'end_date',
        'total',
        'name',
        'description',
    ];

    public function questions()
    {
        return $this->hasMany(Question::class, 'exam_id')->with('options');
    }
    public function groupSession()
    {
        return $this->belongsTo(GroupSession::class, 'group_session_id')->with(['group','groupDay','lesson']);
    }
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
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

    public static function getAllDeleted()
    {
        return self::onlyTrashed()->with(['transLocale'])->get();
    }
    // Restore a Deleted Record
    public static function restoreSoft($id)
    {
        $model = self::onlyTrashed()->find($id);
        if ($model) {
            $model->restore();
        }
        return $model;
    }

    // Force Delete a Record
    public static function forceDeleteById($id)
    {
        $model = self::onlyTrashed()->find($id);
        if ($model) {
            $model->forceDelete();
        }
        return $model;
    }

}
