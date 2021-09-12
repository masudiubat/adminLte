<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResearcherCertification extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'researcher_application_id',
        'name',
        'created_at',
        'updated_at'
    ];
}
