<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassRoomTranslation extends Model
{
    protected $table    = 'class_room_translations';
    protected $fillable = [
        'class_room_id',
        'locale',
        'name',
    ];
}
