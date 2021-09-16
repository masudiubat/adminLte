<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrganizationMemberProject extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'organization_member_id',
        'project_id',
        'created_at',
        'updated_at'
    ];
}
