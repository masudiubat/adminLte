<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAccessCredentialMail extends Model
{
    use SoftDeletes;
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'password',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
