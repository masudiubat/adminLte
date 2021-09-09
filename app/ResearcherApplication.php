<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResearcherApplication extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name',
        'email',
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
        'certifications',
        'attachment',
        'preferred_interview_date',
        'status',
        'created_at',
        'updated_at'
    ];
}
