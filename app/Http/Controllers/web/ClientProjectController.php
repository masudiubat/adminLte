<?php

namespace App\Http\Controllers\web;

use App\User;
use DateTime;
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
        $members = OrganizationMember::with('user')->where('organization_id', $organizationId)->get();

        $skills = Skill::all();
        $scopes = Scope::all();
        return view('pages.project.client.create', ['members' => $members, 'scopes' => $scopes, 'skills' => $skills, 'organizationId' => $organizationId,  'organizationMember' => $organizationMember]);
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
            'title' => 'required|unique:projects',
            'organization_id' => 'required',
            'date' => 'required'
        ], [
            'title.required' => 'Project title is required.',
            'title.unique' => 'Project title is already exists.',
            'organization_id.required' => 'Organization is required',
            'date.required' => 'Start and End date is required'
        ]);

        $project = new Project();

        if ($request->has('title')) {
            $project->title = $request->input('title');
        }

        if ($request->has('organization_id')) {
            $project->organization_id = $request->input('organization_id');
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
                    ProjectScope::create($scopeArr);
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
        $timeDuration = $from->diffInDays($to) + 1;
        $today = date('Y-m-d');
        $timeRemaining = $from->diffInDays($today) + 1;

        if ($today < $to) {
            $percentage = 0;
        } else if ($today == $from) {
            $percentage = 100;
        } else {
            /*
            echo $to;

            $toInt = strtotime($to);
            $fromInt = strtotime($from);
            $todayInt = strtotime("today UTC");
            echo $toInt . '<br/>' . $fromInt . '<br/>' . $todayInt;
            $percentage = ceil((($fromInt - $todayInt) * 100) / ($fromInt - $toInt));
            echo $percentage;
            die();
            */
            $percentage = 50;
        }

        return view('pages.project.client.show', compact('timeRemaining', 'timeDuration', 'numberOfResearchers', 'project', 'numberOfScopes', 'percentage'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $project = Project::with('organization', 'organization_members', 'skills', 'project_scopes')->findOrFail($id);
        $members = OrganizationMember::with('user')->where('organization_id', $project->organization->id)->get();
        $skills = Skill::all();
        $scopes = Scope::all();
        $projectSkills = $project->skills()->pluck('skill_id')->toArray();

        return view('pages.project.client.edit', compact('project', 'members', 'skills', 'scopes', 'projectSkills'));
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
        $project = Project::findOrFail($id);

        $this->validate($request, [
            'title' => 'required|unique:projects,title,' . $project->id,
            'organization_id' => 'required',
            'date' => 'required'
        ], [
            'title.required' => 'Project title is required.',
            'title.unique' => 'Project title is already exists.',
            'organization_id.required' => 'Organization is required',
            'date.required' => 'Start and End date is required'
        ]);

        if ($request->has('title')) {
            $project->title = $request->input('title');
        }

        if ($request->has('organization_id')) {
            $project->organization_id = $request->input('organization_id');
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

        $project->updated_at = date('Y-m-d');
        $updateProject = $project->save();

        if ($updateProject) {
            if ($request->has('member')) {
                $project->organization_members()->sync($request->input('member'));
            }

            if ($request->has('skill')) {
                $project->skills()->sync($request->input('skill'));
            }

            // Update project scopes
            $existingScopes = ProjectScope::where('project_id', $id)->pluck('scope_id')->toArray();
            $newScopes = $request->input('scope');
            $removedScopes = array_diff($existingScopes, $newScopes);

            // Delete existing scopes which remove in update form...
            if (!empty($removedScopes)) {
                foreach ($removedScopes as $scopeId) {
                    $scope = ProjectScope::where('scope_id', $scopeId)->where('project_id', $id)->first();
                    $scope->delete();
                }
            }

            // Update scopes if new scope is added
            if ($request->has('scope')) {
                $tergetUrl = $request->input('url');
                $comment = $request->input('comment');
                $i = 0;
                foreach ($request->input('scope') as $scope) {
                    if (in_array($scope, $existingScopes)) {
                        $scope = ProjectScope::where('scope_id', $scope)->where('project_id', $id)->first();
                        $scope->terget_url = $tergetUrl[$i];
                        $scope->comment = $comment[$i];
                        $scope->updated_at = date('Y-m-d');
                    } else {
                        $scopeArr = array();
                        $scopeArr = [
                            'project_id' => $project->id,
                            'scope_id' => $scope,
                            'terget_url' => $tergetUrl[$i],
                            'comment' => $comment[$i],
                            'created_at' => date('Y-m-d')
                        ];
                        ProjectScope::create($scopeArr);
                    }
                    $i++;
                }
            }
            ActivityLogLib::addLog('Client has updated project named ' . $project->title . ' successfully.', 'success');
            Toastr::success('project named ' . $project->title . ' has updated successfully.', 'success');
            return redirect()->back();
        } else {
            ActivityLogLib::addLog('Client has tried to updated project but failed.', 'error');
            Toastr::error('W00ps! Something went wrong. Try again.', 'error');
            return redirect()->back();
        }
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
