<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class ReportImage extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'code',
        'name',
        'original_name',
        'status',
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
