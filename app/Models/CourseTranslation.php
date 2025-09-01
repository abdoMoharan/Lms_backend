<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseTranslation extends Model
{
    protected $table    = 'course_translations';
    protected $fillable = [
        'course_id',
        'locale',
        'name',
        'description',
    ];
}
