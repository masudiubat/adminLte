<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportCategory extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name',
        'description',
        'cwe_cve_reference',
        'created_at',
        'updated_at'
    ];
}
