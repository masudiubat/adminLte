<?php

namespace App;

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
}
