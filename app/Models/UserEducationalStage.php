<?php
namespace App\Models;

use App\Models\EducationalStage;
use Illuminate\Database\Eloquent\Model;

class UserEducationalStage extends Model
{
    protected $fillable = [
        'user_id',
        'stage_id',
        'subject_id',
    ];

    //relations
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function educational_stage()
    {
        return $this->belongsTo(EducationalStage::class, 'stage_id')->with('transLocale');
    }
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id')->with('transLocale');
    }
}
