<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectTranslation extends Model
{
    protected $table    = 'subject_translations';
    protected $fillable = [
        'subject_id',
        'locale',
        'name',
   'slug',
    ];
}
