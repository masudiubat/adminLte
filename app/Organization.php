<?php

namespace App;

use App\Project;
use App\OrganizationMember;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
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
        'status',
        'created_at',
        'updated_at'
    ];

    public function organization_members()
    {
        return $this->hasMany(OrganizationMember::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
