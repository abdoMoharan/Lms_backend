<?php
namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MeetingZoom extends Model
{
    protected $fillable = [
        'group_session_id',
        'zoom_id',
        'host_id',
        'host_email',
        'topic',
        'start_time',
        'duration',
        'timezone',
        'start_url',
        'join_url',
        'password',
        'is_meeting_created',
        'teacher_id',
    ];

//relations
    public function group_session(): BelongsTo
    {
        return $this->belongsTo(GroupSession::class, 'group_session_id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
