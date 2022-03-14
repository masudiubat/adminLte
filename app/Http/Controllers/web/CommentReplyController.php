<?php

namespace App\Http\Controllers\web;

use App\User;
use App\Project;
use App\CommentReply;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CommentReplyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            'comment'        => 'required',
            'reply'        => 'required'
        ]);

        $reply = new CommentReply();

        if ($request->has('project')) {
            $reply->project_id = $request->input('project');
        }
        if ($request->has('report')) {
            $reply->project_report_id = $request->input('report');
        }
        if ($request->has('comment')) {
            $reply->comment_id = $request->input('comment');
        }

        if ($request->has('reply')) {
            $reply->reply = $request->input('reply');
        }

        $reply->reply_creator_user_id = Auth::user()->id;
        $store = $reply->save();

        if ($store) {
            $project = Project::with('users', 'moderator', 'organization_members')->where('id', $reply->project_id)->first();
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

            $reply->users()->attach($userIdCollections);
            return response()->json(['replysuccess' => $userIdCollections]);
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
