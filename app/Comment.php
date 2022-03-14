<?php

namespace App;

use App\User;
use App\Project;
use App\ProjectReport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    public $timestamps = true;

    protected $guarded = [];

    protected $table = 'comments';

    public function creator_user()
    {
        return $this->belongsTo(User::class, 'creator_user_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function project_report()
    {
        return $this->belongsTo(ProjectReport::class);
    }
}
