<?php
namespace App\Models;

use App\Models\Grade;
use App\Models\Semester;
use App\Models\SubjectTranslation;
use App\Models\User;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model implements TranslatableContract
{
    use SoftDeletes, Translatable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable          = ['created_by', 'updated_by', 'status', 'stage_id', 'semester_id', 'grade_id'];
    public $translatedAttributes = [
        'subject_id',
        'locale',
        'name',
    ];
    protected $ClassRoomTranslation = 'subject_id';

    public function transLocale()
    {
        $locale = app()->getLocale();
        return $this->hasMany(SubjectTranslation::class, 'subject_id')->where('locale', $locale);
    }
    public function trans()
    {
        return $this->hasMany(SubjectTranslation::class, 'subject_id');
    }
    public function educationalStage()
    {
        return $this->belongsTo(EducationalStage::class, 'stage_id')->with('transLocale');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id')->with('transLocale');
    }
    public function grade()
    {
        return $this->belongsTo(Grade::class, 'grade_id')->with('transLocale');
    }
    public function scopeFilter(Builder $builder, array $filters): Builder
    {
        if (isset($filters['name'])) {
            $builder->whereHas('transLocale', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['name'] . '%');
            });
        }
        $builder->when(isset($filters['status']), function ($builder) use ($filters) {
            $statusValue = intval($filters['status']) == 0 ? 0 : $filters['status']; // استخدام `intval` لتحويل النصوص للأرقام
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

    public static function getAllDeleted()
    {
        return self::onlyTrashed()->get();
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
