<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectSkill extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'project_id',
        'skill_id',
        'created_at',
        'updated_at'
    ];
}
