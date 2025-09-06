<?php
namespace App\Models;

use App\Models\Lessons;
use Illuminate\Database\Eloquent\Model;

class LessonsAttachment extends Model
{
    protected $table    = 'lessons_attachments';
    protected $fillable = [
        'lesson_id',
        'video_upload',
        'file',
        'type',
        'link',
        'image',
    ];

    public function lesson()
    {
        return $this->belongsTo(Lessons::class, 'lesson_id')->with(['transLocale']);
    }
    public static function getPath($path)
    {
        if ($path) {
            return asset('attachments/' . $path); // تأكد من أنك تستخدم المسار الصحيح
        }
        return null;
    }
    public function setImageAttribute($value)
    {
        if ($value) {
            $this->attributes['image'] = $value->store('lesson/image', 'attachment');
        }
    }

    public function setFileAttribute($value)
    {
        if ($value) {
            $this->attributes['file'] = $value->store('lesson/file', 'attachment');
        }
    }
    public function setVideoUploadAttribute($value)
    {
        if ($value) {
            $this->attributes['video_upload'] = $value->store('lesson/video', 'attachment');
        }
    }
}
