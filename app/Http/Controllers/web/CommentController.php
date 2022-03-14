<?php

namespace App\Http\Controllers\web;

use App\User;
use App\Comment;
use App\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Notifications\CommentNotification;
use Illuminate\Support\Facades\Notification;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $comments = Comment::with('creator_user', 'users')
            ->where('project_report_id', $id)
            ->orderBy('id', 'DESC')
            ->get();

        $commentView = view('pages.comment._show', compact('comments'))->render();
        return ['comments' => $commentView];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'project'          => 'required',
            'report'         => 'required',
            'comment'        => 'required'
        ]);

        $comment = new Comment();
        if ($request->has('project')) {
            $comment->project_id = $request->input('project');
        }
        if ($request->has('report')) {
            $comment->project_report_id = $request->input('report');
        }
        if ($request->has('comment')) {
            $comment->comment = $request->input('comment');
        }
        $comment->creator_user_id = Auth::user()->id;
        $store = $comment->save();
        if ($store) {
            $project = Project::with('users', 'moderator', 'organization_members')->where('id', $comment->project_id)->first();
            $userIdCollections = $project->users()->pluck('user_id')->toArray();
            $userIdCollections = $project->organization_members()->pluck('user_id')->toArray();
            if (!is_null($project->moderator_id)) {
                array_push($userIdCollections, $project->moderator_id);
            }

            $allAdmins = User::whereHas("roles", function ($q) {
                $q->where("name", "admin");
            })->get();

            if ($allAdmins->count()) {
                foreach ($allAdmins as $admin) {
                    array_push($userIdCollections, $admin->id);
                }
            }

            $comment->users()->attach($userIdCollections);
            Notification::send($allAdmins, new CommentNotification($comment->comment, Auth::user()->name, $comment->created_at->diffForHumans()));

            return response()->json(['success' => $userIdCollections]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
