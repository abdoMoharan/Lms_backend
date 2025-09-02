<?php
namespace App\Models;

use App\Models\GradeTranslation;
use App\Models\User;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grade extends Model implements TranslatableContract
{
    use SoftDeletes, Translatable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['created_by', 'updated_by', 'status', 'stage_id'];
    public $translatedAttributes = [
        'grade_id',
        'locale',
        'name',
    ];
    protected $translationForeignKey = 'grade_id';

    public function transLocale()
    {
        $locale = app()->getLocale();
        return $this->hasMany(GradeTranslation::class, 'grade_id')->where('locale', $locale);
    }
    public function trans()
    {
        return $this->hasMany(GradeTranslation::class, 'grade_id');
    }
    public function educationalStage()
    {
        return $this->belongsTo(EducationalStage::class, 'stage_id')->with('transLocale');
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

     public static function getAllDeleted()
    {
        return self::onlyTrashed()->with(['transLocale','educationalStage'])->get();
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
