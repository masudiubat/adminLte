<?php

namespace App;

use App\ProjectScope;
use Illuminate\Database\Eloquent\Model;

class Scope extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name',
        'created_at',
        'updated_at'
    ];

    public function scopes()
    {
        return $this->belongsToMany(ProjectScope::class);
    }
}
