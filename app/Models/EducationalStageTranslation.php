<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EducationalStageTranslation extends Model
{
    protected $table    = 'educational_stage_translations';
    protected $fillable = [
        'stage_id',
        'locale',
        'name',
        'slug',
    ];
}
