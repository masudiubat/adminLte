<?php

namespace App;

use App\Scope;
use App\ProjectReport;
use Illuminate\Database\Eloquent\Model;

class ProjectScope extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'project_id',
        'scope_id',
        'terget_url',
        'comment',
        'created_at',
        'updated_at'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function scope()
    {
        return $this->belongsTo(Scope::class);
    }

    public function project_reports()
    {
        return $this->hasMany(ProjectReport::class);
    }
}
