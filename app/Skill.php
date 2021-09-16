<?php

namespace App;

use App\Project;
use App\ResearcherApplication;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name',
        'slug',
        'created_at',
        'updated_at'
    ];

    public function researcher_applications()
    {
        return $this->belongsToMany(ResearcherApplication::class);
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }
}
