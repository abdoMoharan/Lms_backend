<?php
namespace App\Models;

use App\Models\LessonsAttachment;
use App\Models\LessonsTranslation;
use App\Models\User;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Lessons extends Model implements TranslatableContract
{
    use SoftDeletes, Translatable;
    protected $table = 'lessons';
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
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
        'slug',
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
    public static function forceDeleteById($id)
    {
        $model = self::onlyTrashed()->find($id);
        if ($model) {
            foreach ($model->attachments as $attachment) {
                if ($attachment->image) {
                    Storage::disk('attachment')->delete($attachment->image);
                }
                if ($attachment->file) {
                    Storage::disk('attachment')->delete($attachment->file);
                }
                if ($attachment->video_upload) {
                    Storage::disk('attachment')->delete($attachment->video_upload);
                }
            }
            Storage::disk('attachment')->delete($model->cover_image);
            $model->attachments()->delete();
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

    public static function getPath($path)
    {
        if ($path) {
            return asset('attachments/' . $path); // تأكد من أنك تستخدم المسار الصحيح
        }
        return null;
    }
    public function setCoverImageAttribute($value)
    {
        if ($value) {
            $this->attributes['cover_image'] = $value->store('lesson/cover_image', 'attachment');
        }
    }
}
