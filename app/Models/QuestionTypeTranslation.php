<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionTypeTranslation extends Model
{
    protected $table    = 'question_type_translations';
    protected $fillable = [
        'question_type_id',
        'locale',
        'name',
   'slug',
    ];
}
