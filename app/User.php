<?php

namespace App;

use App\Customer;
use App\OrganizationMember;
use App\Mail\UserVerificationEmail;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'country_code',
        'mobile',
        'password',
        'address',
        'image',
        'status',
        'role',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sendEmailVerificationNotification()
    {
        $this->notify(new Notifications\UserVerificationEmail);
    }

    public function organization_member()
    {
        return $this->hasMany(OrganizationMember::class);
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }

    public function moderator()
    {
        return $this->hasMany(User::class, 'moderator_id');
    }
}
