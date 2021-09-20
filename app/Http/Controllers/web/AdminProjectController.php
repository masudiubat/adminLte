<?php

namespace App\Http\Controllers\web;

use App\User;
use App\Scope;
use App\Skill;
use App\Project;
use App\Organization;
use App\OrganizationMember;
use App\ProjectScope;
use Illuminate\Http\Request;
use App\Library\ActivityLogLib;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;

class AdminProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function current_project()
    {
        $currentDate = date('Y-m-d');
        $projects = Project::whereDate('start_date', '<=', date("Y-m-d"))->whereDate('end_date', '>=', date("Y-m-d"))->get();
        return view('pages.project.current-project', ['projects' => $projects]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $organizations = Organization::all();
        $moderators = User::where('role', 'moderator')->get();
        $researchers = User::where('role', 'researcher')->get();
        $skills = Skill::all();
        $scopes = Scope::all();
        return view('pages.project.create', ['scopes' => $scopes, 'skills' => $skills, 'organizations' => $organizations, 'moderators' => $moderators, 'researchers' => $researchers]);
    }

    public function search_member($id)
    {
        $members = OrganizationMember::with('user')->where('organization_id', $id)->get();
        return response()->json(['members' => $members]);
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
            'moderator' => 'required',
            'date' =>'required',
            ''
        ], [
            'title.required' => 'Project title is required.',
            'organization.required' => 'Organization is required',
            'moderator.required' => 'Moderator is required',
            'date.required' => 'Start and End date is required'
        ]);

        $project = new Project();

        if($request->has('title')){
            $project->title = $request->input('title');
        }

        if($request->has('organization')){
            $project->organization_id = $request->input('organization');
        }

        if($request->has('moderator')){
            $project->moderator_id = $request->input('moderator');
        }

        if($request->has('date')){
            $dateExplode = explode(' ', $request->input('date'));
            $project->start_date = $dateExplode[0];
            $project->end_date = $dateExplode[2];
        }

        if($request->has('description')){
            $project->brief = $request->input('description');
        }

        if($request->has('questionnaires')){
            $project->questionnaires = $request->input('questionnaires');
        }

        $project->created_at = date('Y-m-d');
        $storeProject = $project->save();
        if($storeProject){
            if($request->has('member')){
                $project->organization_members()->attach($request->input('member'));
            }
            if($request->has('researcher')){
                $project->users()->attach($request->input('researcher'));
            }
            if($request->has('skill')){
                $project->skills()->attach($request->input('skill'));
            }
            if($request->has('scope')){
                $tergetUrl = $request->input('url');
                $comment = $request->input('comment');
                $i = 0;
                foreach($request->input('scope') as $scope){
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
            ActivityLogLib::addLog('User has created a new project named ' . $project->title . ' successfully.', 'success');
            Toastr::success('New project named ' . $project->title . ' has created successfully.', 'success');
            return redirect()->back();
        }else{
            ActivityLogLib::addLog('User has tried to create new skill but failed.', 'error');
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
