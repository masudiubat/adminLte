<?php

namespace App;

use App\User;
use App\Project;
use App\ProjectScope;
use App\ReportCategory;
use Illuminate\Database\Eloquent\Model;

class ProjectReport extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'project_id',
        'report_category_id',
        'project_scope_id',
        'title',
        'vulnerability_location',
        'description',
        'step_to_reproduce',
        'security_impact',
        'recommended_fix',
        'cvss',
        'severity',
        'is_approved',
        'is_archive',
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function report_category()
    {
        return $this->belongsTo(ReportCategory::class);
    }

    public function project_scope()
    {
        return $this->belongsTo(ProjectScope::class);
    }
}
