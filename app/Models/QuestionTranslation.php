<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionTranslation extends Model
{
   protected $table    = 'question_translations';
    protected $fillable = [
        'question_id',
        'locale',
        'name',
    ];
}
