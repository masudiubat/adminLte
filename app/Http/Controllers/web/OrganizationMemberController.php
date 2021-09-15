<?php

namespace App\Http\Controllers\web;

use App\User;
use App\Organization;
use App\OrganizationMember;
use Illuminate\Http\Request;
use App\Library\ActivityLogLib;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;

class OrganizationMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $assignUsers = OrganizationMember::with('user', 'organization')->get();
        $organizations = Organization::all();
        $users = User::where('role', '=', 'client')->get();
        ActivityLogLib::addLog('User has viewd organization member module successfully.', 'success');
        return view('pages.organization-member.index', ['assignUsers' => $assignUsers, 'organizations' => $organizations, 'users' => $users]);
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
            'organization' => 'required ',
            'user' => 'required',
        ], [
            'organization.required' => 'Organization name is required.',
            'user.required' => 'User name is required'
        ]);

        $organizationmember = new OrganizationMember();

        if ($request->has('organization')) {
            $organizationmember->organization_id = $request->input('organization');
        }

        if ($request->has('user')) {
            $organizationmember->user_id = $request->input('user');
        }

        if ($request->has('designation')) {
            $organizationmember->designation = $request->input('designation');
        }

        if ($request->has('leading_person')) {
            $organizationmember->is_leading_person = $request->input('leading_person');
        }

        if ($request->has('leading_person') && $request->input('leading_person') == 1) {
            $organizationmember->role = 'leading person';
        }

        $organizationmember->created_at = date('Y-m-d');
        $save = $organizationmember->save();

        if ($save) {
            ActivityLogLib::addLog('User has assigned a member named' . $organizationmember->name . ' to organization successfully.', 'success');
            Toastr::success('User has assigned a member to organization successfully.', 'success');
            return redirect()->back();
        } else {
            ActivityLogLib::addLog('User has tried to assigned a member to organization but failed.', 'error');
            Toastr::error('W00ps! Something went wrong. Try again.', 'error');
            return redirect()->back();
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
        $organizationmember = OrganizationMember::with('user', 'organization')->findOrFail($id);
        $organizations = Organization::all();
        $users = User::where('role', '=', 'client')->get();

        $createView = view('pages.organization-member._edit', ['organizationmember' => $organizationmember, 'organizations' => $organizations, 'users' => $users])->render();
        return (['editOrganizationMember' => $createView]);
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
        $organizationMember = OrganizationMember::findOrFail($id);

        $this->validate($request, [
            'organization' => 'required ',
            'user' => 'required',
        ], [
            'organization.required' => 'Organization name is required.',
            'user.required' => 'User name is required'
        ]);

        if ($request->has('organization')) {
            $organizationMember->organization_id = $request->input('organization');
        }

        if ($request->has('user')) {
            $organizationMember->user_id = $request->input('user');
        }

        if ($request->has('designation')) {
            $organizationMember->designation = $request->input('designation');
        }

        if ($request->has('leading_person')) {
            $organizationMember->is_leading_person = $request->input('leading_person');
        }

        if ($request->has('leading_person') && $request->input('leading_person') == 1) {
            $organizationMember->role = 'leading person';
        }

        $organizationMember->updated_at = date('Y-m-d');
        $save = $organizationMember->save();

        if ($save) {
            ActivityLogLib::addLog('User has updated organization member to organization successfully.', 'success');
            Toastr::success('User has updated organization member successfully.', 'success');
            return redirect()->back();
        } else {
            ActivityLogLib::addLog('User has tried to update member to organization but failed.', 'error');
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
        $organizationMember = OrganizationMember::findOrFail($id);
        $delete = $organizationMember->delete();

        if ($delete) {
            ActivityLogLib::addLog('User has deleted member to organization successfully.', 'success');
            Toastr::success('delete operation successful.', 'success');
            return redirect()->back();
        } else {
            ActivityLogLib::addLog('User has tried to delete member to organization but failed.', 'error');
            Toastr::error('W00ps! Something went wrong. Try again.', 'error');
            return redirect()->back();
        }
    }
}
