<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectScope extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'project_id',
        'scope_id',
        'created_at',
        'updated_at'
    ];
}
