<?php

namespace App\Http\Controllers\web;

use App\User;
use App\Scope;
use App\Skill;
use App\Organization;
use App\OrganizationMember;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminProjectController extends Controller
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
        echo "<pre>";
        print_r($request->all());
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
