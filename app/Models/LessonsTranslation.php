<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessonsTranslation extends Model
{
    protected $table    = 'lessons_translations';
    protected $fillable = [
        'lesson_id',
        'locale',
        'name',
        'description',
        'content',
   'slug',
    ];
}
