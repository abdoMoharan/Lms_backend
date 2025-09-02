<?php
namespace App\Models;

use App\Models\User;
use App\Models\LessonsAttachment;
use App\Models\LessonsTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Lessons extends Model implements TranslatableContract
{
    use SoftDeletes, Translatable;
    protected $table = 'lessons';
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'sort',
        'cover_image',
        'url',
        'zoom_url',
        'unit_id',
        'created_by',
        'updated_by',
        'status',
    ];
    public $translatedAttributes = [
        'lesson_id',
        'locale',
        'name',
        'description',
        'content',
    ];
    protected $translationForeignKey = 'lesson_id';

    public function transLocale()
    {
        $locale = app()->getLocale();
        return $this->hasMany(LessonsTranslation::class, 'lesson_id')->where('locale', $locale);
    }

    public function trans()
    {
        return $this->hasMany(LessonsTranslation::class, 'lesson_id');
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id')->with(['transLocale']);
    }

    public function attachments()
    {
        return $this->hasMany(LessonsAttachment::class, 'lesson_id')->with(['lesson']);
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
        return self::onlyTrashed()->with(['transLocale', 'unit', 'attachments'])->get();
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
    // public static function forceDeleteById($id)
    // {
    //     $model = self::onlyTrashed()->find($id);
    //     if ($model) {
    //         $model->forceDelete();
    //     }
    //     return $model;
    // }
public static function forceDeleteById($id)
{
    $model = self::find($id);
    if ($model) {
        // حذف المرفقات من التخزين
        foreach ($model->attachments as $attachment) {
            if ($attachment->image) {
                Storage::delete('attachments/lesson/image/' . $attachment->image);
            }
            if ($attachment->file) {
                Storage::delete('attachments/lesson/file/' . $attachment->file);
            }
            if ($attachment->video_upload) {
                Storage::delete('attachments/lesson/video/' . $attachment->video_upload);
            }
        }
        if ($model->cover_image) {
            Storage::delete('attachments/lesson/cover_image/' . $model->cover_image);
        }

        // حذف المرفقات من قاعدة البيانات
        $model->attachments()->delete();

        // حذف الـ lesson نفسه من قاعدة البيانات
        $model->forceDelete();
    }

    return $model;
}

    public function scopeFilter(Builder $builder, array $filters): Builder
    {
        if (isset($filters['name'])) {
            $builder->whereHas('transLocale', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['name'] . '%');
            });
        }
        if (isset($filters['unit_id'])) {
            $builder->whereHas('unit', function ($q) use ($filters) {
                $q->where('id', $filters['unit_id']);
            });
        }
        $builder->when(isset($filters['status']), function ($builder) use ($filters) {
            $statusValue = intval($filters['status']) == 0 ? 0 : $filters['status'];
            $builder->where('status', $statusValue);
        });
        return $builder;
    }

    public function getCoverImageAttribute($value)
    {
        if ($value) {
            $path = asset('attachments/lesson/cover_image/' . $value);
            return $path;
        }
    }
    public function setCoverImageAttribute($value)
    {
        if ($value) {
            $this->attributes['cover_image'] = $value->store('lesson/cover_image', 'attachment');
        }
    }
}
