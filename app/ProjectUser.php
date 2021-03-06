<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectUser extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'project_id',
        'user_id',
        'created_at',
        'updated_at'
    ];
}
