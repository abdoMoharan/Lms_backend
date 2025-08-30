<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitTranslation extends Model
{
    protected $table    = 'unit_translations';
    protected $fillable = [
        'unit_id',
        'locale',
        'name',
    ];
}
