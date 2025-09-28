<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'qualification',
        'certificate_name',
        'certificate_date',
        'experience',
        'id_card_number',
        'id_card_image_front',
        'id_card_image_back',
        'birthdate',
        'nationality',
        'address',
        'degree',
        'cv',
        'bio',
        'gender',
        'intro_video',
    ];

    //get path cardImage front
    public static function getIdCardImageFrontAttribute($path)
    {
        if ($path) {
            return asset('attachments/' . $path); // تأكد من أنك تستخدم المسار الصحيح
        }
        return null;
    }
    //set path cardImage front
    public function setIdCardImageFrontAttribute($value)
    {
        if ($value) {
            $this->attributes['id_card_image_front'] = $value->store('profile', 'attachment');
        }
    }
    //get path cardImage back
    public static function getIdCardImageBackAttribute($path)
    {
        if ($path) {
            return asset('attachments/' . $path); // تأكد من أنك تستخدم المسار الصحيح
        }
        return null;
    }
    //set path cardImage back
    public function setIdCardImageBackAttribute($value)
    {
        if ($value) {
            $this->attributes['id_card_image_back'] = $value->store('profile', 'attachment');
        }
    }
    //cv
    public static function getCvAttribute($path)
    {
        if ($path) {
            return asset('attachments/' . $path); // تأكد من أنك تستخدم المسار الصحيح
        }
        return null;
    }
    //cv
    public function setCvAttribute($value)
    {
        if ($value) {
            $this->attributes['cv'] = $value->store('profile', 'attachment');
        }
    }
    //intro_video
    public static function getIntroVideoAttribute($path)
    {
        if ($path) {
            return asset('attachments/' . $path); // تأكد من أنك تستخدم المسار الصحيح
        }
        return null;
    }
    //intro_video
    public function setIntroVideoAttribute($value)
    {
        if ($value) {
            $this->attributes['intro_video'] = $value->store('profile', 'attachment');
        }
    }
    //relations
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->with('userEductionStage');
    }
}
