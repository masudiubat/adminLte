<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportCategory extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name',
        'created_at',
        'updated_at'
    ];
}