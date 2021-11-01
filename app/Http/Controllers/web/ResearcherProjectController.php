<?php

namespace App\Http\Controllers\web;

use App\User;
use App\Project;
use Carbon\Carbon;
use App\ProjectUser;
use App\OrganizationMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ResearcherProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = Auth::user()->id;
        $user = User::with('projects')->where('id', $id)->first();
        return view('pages.project.researcher.index', ['user' => $user]);
    }

    public function current_project()
    {
        $userId = Auth::user()->id;

        $projects = Project::with('organization', 'organization_members', 'skills', 'moderator', 'users', 'project_scopes')
            ->whereHas('users', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->whereDate('start_date', '<=', date("Y-m-d"))->whereDate('end_date', '>=', date("Y-m-d"))
            ->where('is_approved', 1)
            ->where('status', 'active')
            ->get();
        return view('pages.project.researcher.active-projects-index', ['projects' => $projects]);
    }

    public function upcoming_project()
    {
        $userId = Auth::user()->id;

        $projects = Project::with('organization', 'organization_members', 'skills', 'moderator', 'users', 'project_scopes')
            ->whereHas('users', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->whereDate('start_date', '>', date("Y-m-d"))->whereDate('end_date', '>', date("Y-m-d"))
            ->where('is_approved', 1)
            ->where('status', 'upcoming')
            ->get();
        return view('pages.project.researcher.upcoming-projects-index', ['projects' => $projects]);
    }

    public function unapproved_project()
    {
        $userId = Auth::user()->id;

        $projects = Project::with('organization', 'organization_members', 'skills', 'moderator', 'users', 'project_scopes')
            ->whereHas('users', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->where('is_approved', 0)
            ->get();
        return view('pages.project.researcher.unapprove-projects-index', ['projects' => $projects]);
    }

    public function archieve_project()
    {
        $userId = Auth::user()->id;

        $projects = Project::with('organization', 'organization_members', 'skills', 'moderator', 'users', 'project_scopes')
            ->whereHas('users', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->get();
        return view('pages.project.researcher.archieve-projects-index', ['projects' => $projects]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $userId = Auth::user()->id;
        $userProject = DB::table('project_user')->where('project_id', $id)->where('user_id', $userId)->first();

        if ($userProject) {
            $project = Project::with('organization', 'organization_members', 'skills', 'moderator', 'users', 'project_scopes')->where('id', $id)->first();
            $numberOfScopes = $project->project_scopes->count();
            $numberOfResearchers = $project->users->count();
            $to = Carbon::parse($project->start_date);
            $from = Carbon::parse($project->end_date);
            $timeDuration = $from->diffInHours($to) + 24;
            $today = date('Y-m-d');
            $timeRemaining = $from->diffInHours($today) + 24;

            return view('pages.project.researcher.show', ['timeRemaining' => $timeRemaining, 'timeDuration' => $timeDuration, 'numberOfResearchers' => $numberOfResearchers, 'project' => $project, 'numberOfScopes' => $numberOfScopes]);
        } else {
        }
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
