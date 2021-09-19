<?php

namespace App;

use App\Project;
use Illuminate\Database\Eloquent\Model;

class Scope extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name',
        'created_at',
        'updated_at'
    ];

    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }
}
