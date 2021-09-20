<?php

namespace App;

use App\User;
use App\Scope;
use App\Skill;
use App\Organization;
use App\OrganizationMember;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'moderator_id',
        'organization_id',
        'title',
        'brief',
        'start_date',
        'end_date',
        'is_approved',
        'executive_summary',
        'questionnaires',
        'hide_from_client',
        'created_at',
        'updated_at'
    ];

    

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function organization_members()
    {
        return $this->belongsToMany(OrganizationMember::class);
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class);
    }

    public function moderator()
    {
        return $this->belongsTo(User::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function project_scopes()
    {
        return $this->hasMany(ProjectScope::class);
    }
}
