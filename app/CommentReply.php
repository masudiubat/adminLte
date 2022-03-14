<?php

namespace App;

use App\User;
use App\Comment;
use App\Project;
use App\ProjectReport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommentReply extends Model
{
    use SoftDeletes;

    public $timestamps = true;

    protected $guarded = [];

    protected $table = 'comment_replies';

    public function reply_creator_user()
    {
        return $this->belongsTo(User::class, 'reply_creator_user_id');
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

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }
}
