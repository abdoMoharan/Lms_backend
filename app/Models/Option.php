<?php

namespace App\Models;

use App\Models\Question;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'question_id',
        'option_text',
        'is_correct',
    ];


//relations
public function question()
{
    return $this->belongsTo(Question::class, 'question_id');
}
}
