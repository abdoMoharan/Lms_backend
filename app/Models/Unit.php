<?php
namespace App\Models;

use App\Models\User;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model implements TranslatableContract
{
    use SoftDeletes, Translatable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable          = ['created_by', 'updated_by', 'status', 'course_id', 'sort'];
    public $translatedAttributes = [
        'unit_id',
        'locale',
        'name',
        'slug',
    ];
    protected $translationForeignKey = 'unit_id';

    public function transLocale()
    {
        $locale = app()->getLocale();
        return $this->hasMany(UnitTranslation::class, 'unit_id')->where('locale', $locale);
    }
    public function trans()
    {
        return $this->hasMany(UnitTranslation::class, 'unit_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id')->with(['transLocale', 'teacher', 'subject']);
    }


    public function lessons(): HasMany
    {
        return $this->hasMany(Lessons::class, 'unit_id')->with('transLocale');
    }

    public function scopeFilter(Builder $builder, array $filters): Builder
    {
        if (isset($filters['name'])) {
            $builder->whereHas('transLocale', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['name'] . '%');
            });
        }
        if (isset($filters['course_id'])) {
            $builder->whereHas('course', function ($q) use ($filters) {
                $q->where('id', $filters['course_id']);
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
        return self::onlyTrashed()->with(['transLocale', 'course', 'createdBy'])->get();
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
