<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SemesterTranslation extends Model
{
  protected $table    = 'semester_translations';
    protected $fillable = [
        'semester_id',
        'locale',
        'name',
   'slug',
    ];
}
