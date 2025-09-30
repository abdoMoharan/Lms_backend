<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeTranslation extends Model
{
     protected $table    = 'grade_translations';
    protected $fillable = [
        'grade_id',
        'locale',
        'name',
   'slug',
    ];
}
