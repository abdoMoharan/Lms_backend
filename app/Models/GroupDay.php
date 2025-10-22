<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroupDay extends Model
{
    protected $fillable = ['start_time', 'session_time', 'group_id', 'week_id'];

//relations
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
    public function week(): BelongsTo
    {
        return $this->belongsTo(Week::class, 'week_id');
    }
}
