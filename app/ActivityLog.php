<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'user_name',
        'request_method',
        'request_route',
        'request_ip',
        'request_description',
        'request_status',
        'request_date',
        'created_at',
        'updated_at'
    ];
}
