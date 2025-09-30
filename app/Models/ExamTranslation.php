<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamTranslation extends Model
{
    protected $table    = 'exam_translations';
    protected $fillable = [
        'exam_id',
        'locale',
        'name',
        'description',
   'slug',
    ];
}
