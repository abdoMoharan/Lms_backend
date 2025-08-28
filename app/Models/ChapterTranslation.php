<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChapterTranslation extends Model
{
    protected $table    = 'chapter_translations';
    protected $fillable = [
        'chapter_id',
        'locale',
        'name',
    ];
}
