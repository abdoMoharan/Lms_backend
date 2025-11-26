<?php
namespace App\Models;

use App\Models\User;
use App\Models\Group;
use App\Models\Lessons;
use App\Models\GroupSession;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonsAttachment extends Model
{
    protected $table    = 'lessons_attachments';
    protected $fillable = [
        'video_upload',
        'file',
        'type',
        'link',
        'image',
        'group_session_id',
        'user_id',
    ];


    public function teacher()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function group_session(): BelongsTo
    {
        return $this->belongsTo(GroupSession::class, 'group_session_id')->with(['lesson','group']);
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
