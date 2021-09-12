<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResearcherApplicationSkill extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'researcher_application_id',
        'skill_id',
        'created_at',
        'updated_at'
    ];
}
