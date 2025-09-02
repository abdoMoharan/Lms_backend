<?php
namespace App\Models;

use App\Models\Lessons;
use Illuminate\Database\Eloquent\Model;

class LessonsAttachment extends Model
{
    protected $table = 'lessons_attachments';
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
    public function getImageAttribute($value)
    {
        if ($value) {
            $path = asset('attachments/lesson/image/' . $value);
            return $path;
        }
    }
    public function setImageAttribute($value)
    {
        if ($value) {
            $this->attributes['image'] = $value->store('lesson/image', 'attachment');
        }
    }
    public function getFileAttribute($value)
    {
        if ($value) {
            $path = asset('attachments/lesson/file/' . $value);
            return $path;
        }
    }
    public function setFileAttribute($value)
    {
        if ($value) {
            $this->attributes['file'] = $value->store('lesson/file', 'attachment');
        }
    }
    public function getVideoUploadAttribute($value)
    {
        if ($value) {
            $path = asset('attachments/lesson/video/' . $value);
            return $path;
        }
    }
    public function setVideoUploadAttribute($value)
    {
        if ($value) {
            $this->attributes['video_upload'] = $value->store('lesson/video', 'attachment');
        }
    }
}
