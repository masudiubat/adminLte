<?php

namespace App\Http\Controllers\web;

use App\User;
use App\Project;
use App\CountryCode;
use App\OrganizationMember;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Library\ActivityLogLib;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserAccessCredentialMail;

class OrganizationUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*
        $projects = Project::select('id', 'organization_id', 'title')->where('organization_id', 4)->get();

        $user = User::with('permissions', 'organization_member')->where('status', 1)->findOrFail(54);

        $exPermisions = $user->permissions()->pluck('name')->toArray();
        $exProjects = $user->organization_member->projects()->get()->pluck('id')->toArray();

        echo "<pre>";
        foreach ($projects as $project) {
            if (in_array($project->id, $exProjects)) {
                echo $project->title;
            }
        }
        //print_r($exProjects);

        die();
*/

        $userId = Auth::user()->id;
        $userOrgId = OrganizationMember::with('organization')->where('user_id', $userId)->first();
        $users = OrganizationMember::with('user')->where('organization_id', $userOrgId->organization_id)->where('status', 1)->get();
        $codes = CountryCode::all();
        $projects = Project::select('id', 'organization_id', 'title')->where('organization_id', $userOrgId->organization_id)->get();
        return view('pages.support-client.index', compact('users', 'codes', 'userOrgId', 'projects'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userId = Auth::user()->id;
        $userOrgId = OrganizationMember::with('organization')->where('user_id', $userId)->first();
        $users = OrganizationMember::where('organization_id', $userOrgId->organization_id)->where('status', 1)->count();

        if ($userOrgId->organization->maximum_members <= $users) {
            Toastr::error('W00ps! You have reached your member creation limit.', 'error');
            return redirect()->back();
        }

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required | email | unique:users',
            'country_code' => 'required',
            'phone' => 'required | unique:users',
            'designation' => 'required',
            'organization_id' => 'required'
        ], [
            'name.required' => 'Woops! Name is missiong.',
            'country_code.required' => 'Woops! Country code is not selected.',
            'email.required' => 'Woops! Email is missiong.',
            'phone.required' => 'Woops! Phone number is missiong.',
            'email.email' => 'Woops! Wrong email format.',
            'email.unique' => 'Woops! Given email address is already registered.',
            'phone.unique' => 'Woops! Given mobile number is already in used.',
            'designation' => 'Woops! Designation is missiong.',
            'organization_id' => 'Woops! Organization name is missiong.'
        ]);



        $user = new User();

        if ($request->has('name')) {
            $user->name = $request->input('name');
        }

        if ($request->has('email')) {
            $user->email = $request->input('email');
        }

        if ($request->has('country_code')) {
            $country = CountryCode::findOrFail($request->input('country_code'));
            $user->country_code = $country->code;
            $user->country = $country->country;
        }

        if ($request->has('phone')) {
            $user->phone = $request->input('phone');
        }

        $user->role = 'client';
        $password = Str::random(8);
        $user->password = bcrypt($password);
        $user->created_at = date('Y-m-d');
        $createUser = $user->save();
        if ($createUser) {
            // Add user permission
            $user->givePermissionTo($request->input('credentials'));

            // Add user to project member table
            $organizationMember = new OrganizationMember();
            $organizationMember->user_id = $user->id;
            $organizationMember->organization_id = $request->input('organization_id');
            $organizationMember->designation = $request->input('designation');
            $organizationMember->role = 'team member';
            $organizationMember->is_leading_person = 0;
            $organizationMember->created_at = date('Y-m-d');
            $organizationMember->save();

            // Add user to project
            if ($request->has('project')) {
                $organizationMember->projects()->attach($request->input('project'));
            }

            // Send email for user credentials
            $myEmail = $user->email;
            $details = [
                'title' => 'Your beetles account details.',
                'url' => 'http://127.0.0.1:8000',
                'password' => $password,
                'name' => $user->name,
                'email' => $user->email
            ];
            Mail::to($myEmail)->send(new UserAccessCredentialMail($details));

            // Send email to verification user account
            $user->sendEmailVerificationNotification();

            ActivityLogLib::addLog('User has created a new user successfully.', 'success');
            Toastr::success('New user has created successfully.', 'success');
            return redirect()->back();
        } else {
            ActivityLogLib::addLog('User creation attempt failed.', 'error');
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
        $user = User::with('permissions', 'organization_member')->where('status', 1)->findOrFail($id);
        $userDetails = view('pages.support-client._show', compact('user'))->render();
        return ['UserInfo' => $userDetails];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $userId = Auth::user()->id;
        $userOrgId = OrganizationMember::with('organization')->where('user_id', $userId)->first();
        $memberOrgId = OrganizationMember::with('organization')->where('user_id', $id)->first();
        if ($userOrgId->organization_id != $memberOrgId->organization_id) {
            Toastr::error('W00ps! You have no right to update this member info.', 'error');
            return redirect()->back();
        }
        $users = OrganizationMember::with('user')->where('organization_id', $userOrgId->organization_id)->where('status', 1)->get();
        $codes = CountryCode::all();
        $projects = Project::select('id', 'organization_id', 'title')->where('organization_id', $userOrgId->organization_id)->get();

        $user = User::with('permissions', 'organization_member')->where('status', 1)->findOrFail($id);

        $exPermisions = $user->permissions()->pluck('name')->toArray();
        $exProjects = $user->organization_member->projects()->get()->pluck('id')->toArray();

        $updateLoad = view('pages.support-client._edit', compact('user', 'exPermisions', 'exProjects', 'users', 'codes', 'projects', 'userOrgId'))->render();
        return ['editUser' => $updateLoad];
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
        $user = User::findOrFail($id);

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required | email | unique:users,email,' . $user->id,
            'country_code' => 'required',
            'phone' => 'required | unique:users,phone,' . $user->id,
            'designation' => 'required',
            'organization_id' => 'required'
        ], [
            'name.required' => 'Woops! Name is missiong.',
            'country_code.required' => 'Woops! Country code is not selected.',
            'email.required' => 'Woops! Email is missiong.',
            'phone.required' => 'Woops! Phone number is missiong.',
            'email.email' => 'Woops! Wrong email format.',
            'email.unique' => 'Woops! Given email address is already registered.',
            'phone.unique' => 'Woops! Given mobile number is already in used.',
            'designation' => 'Woops! Designation is missiong.',
            'organization_id' => 'Woops! Organization name is missiong.'
        ]);

        if ($request->has('name')) {
            $user->name = $request->input('name');
        }

        if ($request->has('email')) {
            $user->email = $request->input('email');
        }

        if ($request->has('country_code')) {
            $country = CountryCode::findOrFail($request->input('country_code'));
            $user->country_code = $country->code;
            $user->country = $country->country;
        }

        if ($request->has('phone')) {
            $user->phone = $request->input('phone');
        }

        $user->role = 'client';
        $user->updated_at = date('Y-m-d');
        $updateUser = $user->save();
        if ($updateUser) {
            // Update user permission
            $user->syncPermissions($request->input('credentials'));

            // Update organization member
            $organizationMember = OrganizationMember::where('user_id', $user->id)->first();
            if ($request->has('designation')) {
                $organizationMember->designation = $request->input('designation');
                $organizationMember->updated_at = date('Y-m-d');
            }
            if ($organizationMember->isDirty('designation')) {
                $organizationMember->save();
            }

            // Update user to project
            if ($request->has('project')) {
                $organizationMember->projects()->sync($request->input('project'));
            }

            // Send email for user credentials
            $myEmail = $user->email;
            $details = [
                'title' => 'Your beetles account details.',
                'url' => 'http://127.0.0.1:8000',
                'name' => $user->name,
                'password' => '',
                'email' => $user->email
            ];
            Mail::to($myEmail)->send(new UserAccessCredentialMail($details));

            ActivityLogLib::addLog('User has updated team member info successfully.', 'success');
            Toastr::success('Member info has updated successfully.', 'success');
            return redirect()->back();
        } else {
            ActivityLogLib::addLog('Member info modification attempt failed.', 'error');
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
        $user = User::with('permissions', 'organization_member')->findOrFail($id);

        // Delete user permissions
        $user->permissions()->detach();

        // delete from organization member project
        $user->organization_member->projects()->detach();

        // delete from organization member
        $user->organization_member->delete();

        // delete user
        $delete = $user->delete();

        if ($delete) {
            ActivityLogLib::addLog('User has deleted a team member info successfully.', 'success');
            Toastr::success('Member info has deleted successfully.', 'success');
            return redirect()->back();
        } else {
            ActivityLogLib::addLog('User has tried to delete member info but failed.', 'error');
            Toastr::error('W00ps! Something went wrong. Try again.', 'error');
            return redirect()->back();
        }
    }
}
