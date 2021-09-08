<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCompany extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name',
        'code_name',
        'email',
        'country_code',
        'phone',
        'address',
        'logo',
        'created_at',
        'updated_at'
    ];
}
