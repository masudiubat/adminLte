<?php

namespace App;

use App\Comment;
use App\Customer;
use App\ReportImage;
use App\ProjectReport;
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
        'isVerified',
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
        return $this->hasOne(OrganizationMember::class);
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }

    public function moderators()
    {
        return $this->hasMany(Project::class, 'moderator_id');
    }

    public function report_images()
    {
        return $this->hasMany(ReportImage::class);
    }

    public function project_reports()
    {
        return $this->hasMany(ProjectReport::class);
    }

    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function comments_created()
    {
        return $this->hasMany(Comment::class, 'creator_user_id');
    }

    public function comment_replies_created()
    {
        return $this->hasMany(CommentReply::class, 'reply_creator_user_id');
    }

    public function comments()
    {
        return $this->belongsToMany(Comment::class);
    }

    public function comment_replies()
    {
        return $this->belongsToMany(CommentReply::class);
    }
}
