<?php

namespace App;

use App\Skill;
use Illuminate\Database\Eloquent\Model;

class ResearcherApplication extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name',
        'email',
        'country',
        'country_code',
        'phone',
        'date_of_birth',
        'father_name',
        'mother_name',
        'present_address',
        'permanent_address',
        'about',
        'profile_image',
        'photo_identity',
        'attachment',
        'preferred_interview_date',
        'status',
        'created_at',
        'updated_at'
    ];

    public function skills()
    {
        return $this->belongsToMany(Skill::class);
    }
}
