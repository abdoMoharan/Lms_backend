<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnswerTranslation extends Model
{
 protected $table    = 'answer_translations';
    protected $fillable = [
        'answer_id',
        'locale',
        'name',
    ];
}
