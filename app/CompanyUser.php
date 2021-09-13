<?php

namespace App;

use App\User;
use App\UserCompany;
use Illuminate\Database\Eloquent\Model;

class CompanyUser extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'user_company_id',
        'user_id',
        'designation',
        'is_leading_person',
        'created_at',
        'updated_at'
    ];

    public function user_company()
    {
        return $this->belongsTo(UserCompany::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
