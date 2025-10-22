<?php
namespace App\Models;

use App\Models\Subject;
use App\Models\User;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model implements TranslatableContract
{
    use SoftDeletes, Translatable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable =
        [
        'subject_id',
        'created_by',
        'updated_by',
        'status',
        'day_count',
    ];
    public $translatedAttributes = [
        'course_id',
        'locale',
        'name',
        'description',
        'slug',
    ];
    protected $translationForeignKey = 'course_id';

    public function transLocale()
    {
        $locale = app()->getLocale();
        return $this->hasMany(CourseTranslation::class, 'course_id')->where('locale', $locale);
    }
    public function trans()
    {
        return $this->hasMany(CourseTranslation::class, 'course_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');

    }
    public function units(): HasMany
    {
        return $this->hasMany(Unit::class, 'course_id')->with(['transLocale', 'lessons']);
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
        return self::onlyTrashed()->with(['transLocale', 'teacher', 'subject', 'createdBy'])->get();
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
