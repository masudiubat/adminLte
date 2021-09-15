<?php

namespace App;

use App\User;
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
        'target_url',
        'hide_from_client',
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
