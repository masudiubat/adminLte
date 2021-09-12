<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SocialMedia extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name',
        'icon',
        'status',
        'created_at',
        'updated_at'
    ];
}
