<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CountryCode extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'country',
        'country_code',
        'code',
        'created_at',
        'updated_at'
    ];
}
