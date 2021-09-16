<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectResearcher extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'project_id',
        'researcher_id',
        'created_at',
        'updated_at'
    ];
}
