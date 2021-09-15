<?php

namespace App;

use App\User;
use App\Organization;
use Illuminate\Database\Eloquent\Model;

class OrganizationMember extends Model
{
    protected $fillable = [
        'organization_id',
        'user_id',
        'designation',
        'is_leading_person',
        'created_at',
        'updated_at'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
