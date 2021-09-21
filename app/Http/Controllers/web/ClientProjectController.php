<?php

namespace App\Http\Controllers\web;

use App\User;
use App\Scope;
use App\Skill;
use App\Project;
use Carbon\Carbon;
use App\Organization;
use App\ProjectScope;
use App\OrganizationMember;
use Illuminate\Http\Request;
use App\Library\ActivityLogLib;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class ClientProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = Auth::user()->id;
        $organizationMember = OrganizationMember::with('organization')->where('user_id', $id)->first();
        $organizationId = $organizationMember->organization->id;

        $projects = Project::where('organization_id', $organizationId)->get();
        return view('pages.project.client.index', ['projects' => $projects]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $id = Auth::user()->id;
        $organizationMember = OrganizationMember::with('organization')->where('user_id', $id)->first();
        $organizationId = $organizationMember->organization->id;
        $organization = $organizationMember->organization->name;
        $members = OrganizationMember::with('user')->where('organization_id', $organizationId)->get();

        $moderators = User::where('role', 'moderator')->get();
        $researchers = User::where('role', 'researcher')->get();
        $skills = Skill::all();
        $scopes = Scope::all();
        return view('pages.project.client.create', ['members' => $members, 'scopes' => $scopes, 'skills' => $skills, 'organizationId' => $organizationId,  'organization' => $organization, 'moderators' => $moderators, 'researchers' => $researchers]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'organization' => 'required',
            'date' => 'required',
            ''
        ], [
            'title.required' => 'Project title is required.',
            'organization.required' => 'Organization is required',
            'date.required' => 'Start and End date is required'
        ]);
        $id = Auth::user()->id;
        $organizationMember = OrganizationMember::with('organization')->where('user_id', $id)->first();
        $organizationId = $organizationMember->organization->id;
        $project = new Project();

        if ($request->has('title')) {
            $project->title = $request->input('title');
        }

        if ($request->has('organization')) {
            $project->organization_id = $organizationId;
        }

        if ($request->has('date')) {
            $dateExplode = explode(' ', $request->input('date'));
            $project->start_date = $dateExplode[0];
            $project->end_date = $dateExplode[2];
        }

        if ($request->has('description')) {
            $project->brief = $request->input('description');
        }

        if ($request->has('questionnaires')) {
            $project->questionnaires = $request->input('questionnaires');
        }

        $project->created_at = date('Y-m-d');
        $storeProject = $project->save();
        if ($storeProject) {
            if ($request->has('member')) {
                $project->organization_members()->attach($request->input('member'));
            }

            if ($request->has('skill')) {
                $project->skills()->attach($request->input('skill'));
            }
            if ($request->has('scope')) {
                $tergetUrl = $request->input('url');
                $comment = $request->input('comment');
                $i = 0;
                foreach ($request->input('scope') as $scope) {
                    $scopeArr = array();
                    $scopeArr = [
                        'project_id' => $project->id,
                        'scope_id' => $scope,
                        'terget_url' => $tergetUrl[$i],
                        'comment' => $comment[$i],
                        'created_at' => date('Y-m-d')
                    ];
                    $createScope = ProjectScope::create($scopeArr);
                    $i++;
                }
            }
            ActivityLogLib::addLog('Client has created a new project named ' . $project->title . ' successfully.', 'success');
            Toastr::success('New project named ' . $project->title . ' has created successfully.', 'success');
            return redirect()->back();
        } else {
            ActivityLogLib::addLog('Client has tried to create new skill but failed.', 'error');
            Toastr::error('W00ps! Something went wrong. Try again.', 'error');
            return redirect()->back();
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

        $userId = Auth::user()->id;
        $organizationMember = OrganizationMember::with('organization')->where('user_id', $userId)->first();
        $organizationId = $organizationMember->organization->id;

        $project = Project::with('organization', 'organization_members', 'skills', 'moderator', 'users', 'project_scopes')->where('organization_id', $organizationId)->where('id', $id)->first();
        $numberOfScopes = $project->project_scopes->count();
        $numberOfResearchers = $project->users->count();
        $to = Carbon::parse($project->start_date);
        $from = Carbon::parse($project->end_date);
        $timeDuration = $from->diffInHours($to) + 24;
        $today = date('Y-m-d');
        $timeRemaining = $from->diffInHours($today) + 24;

        return view('pages.project.client.show', ['timeRemaining' => $timeRemaining, 'timeDuration' => $timeDuration, 'numberOfResearchers' => $numberOfResearchers, 'project' => $project, 'numberOfScopes' => $numberOfScopes]);
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
