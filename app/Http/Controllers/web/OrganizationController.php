<?php

namespace App\Http\Controllers\web;

use App\User;
use App\CountryCode;
use App\Organization;
use App\OrganizationMember;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Library\ActivityLogLib;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserAccessCredentialMail;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $organizations = Organization::where('status', 1)->get();
        $phoneCodes = CountryCode::all();
        ActivityLogLib::addLog('User has viewed organization list successfully.', 'success');
        return view('pages.organization.index', ['organizations' => $organizations, 'phoneCodes' => $phoneCodes]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->filled('communicator')) {
            $this->validate($request, [
                'name' => 'required | unique:organizations',
                'email' => 'required | email | unique:users',
                'country_code' => 'required',
                'phone' => 'required | unique:users'
            ], [
                'email.required' => 'Email address is required.',
                'email.unique' => 'Email address is already registered',
                'email.email' => 'Invalid email address',
                'country_code.required' => 'Country code is required',
                'phone.required' => 'Phone number field is mandatory field',
                'phone.unique' => 'This number is already registered',
                'name.required' => 'Company name is required.',
                'name.unique' => 'Company name is already registered'
            ]);
        } else {
            $this->validate($request, [
                'name' => 'required | unique:organizations',
            ], [
                'name.required' => 'Company name is required.',
                'name.unique' => 'Company name is already registered'
            ]);
        }

        // Code for insert organization image
        $name = $request->input('name');
        $slug = Str::slug($name);
        $lastId = Organization::select('id')->orderBy('id', 'DESC')->first();
        if ($request->has('image')) {
            $image = $request->file('image');
            if (!is_null($lastId)) {
                $newId = $lastId->id + 1;
                $newImageName = $slug . "_" . $newId . '.' . $image->getClientOriginalExtension();
            } else {
                $newImageName = $slug . "_1" . '.' . $image->getClientOriginalExtension();
            }
            $destinationPath = 'images/organization/logos';
            $image->move($destinationPath, $newImageName);
        }

        $organization = new Organization();

        if ($request->has('name')) {
            $organization->name = $request->input('name');
        }

        if ($request->has('code_name')) {
            $organization->code_name = $request->input('code_name');
        }

        if ($request->has('address')) {
            $organization->address = $request->input('address');
        }

        if ($request->has('image')) {
            $organization->logo = $newImageName;
        }

        $organization->created_at = date('Y-m-d');

        $storeOrganization = $organization->save();

        if ($storeOrganization) {

            // Add user if communicator define
            if ($request->filled('communicator')) {

                $user = new User();

                if ($request->has('communicator')) {
                    $user->name = $request->input('communicator');
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
                    $user->roles()->attach($request->input('role'));
                    // Send email for user credentials
                    $myEmail = $user->email;
                    $details = [
                        'title' => 'Your beetles account details.',
                        'url' => 'http://bcp.bigweb.com.bd',
                        'password' => $password,
                        'name' => $user->name,
                        'email' => $user->email
                    ];
                    Mail::to($myEmail)->send(new UserAccessCredentialMail($details));

                    // Send email to verification user account
                    $user->sendEmailVerificationNotification();

                    // Create organization members
                    $organizationMember = new OrganizationMember();
                    $organizationMember->user_id = $user->id;
                    $organizationMember->organization_id = $organization->id;
                    $organizationMember->designation = $request->input('designation');
                    $organizationMember->role = 'leading person';
                    $organizationMember->is_leading_person = 1;
                    $organizationMember->created_at = date('Y-m-d');
                    $organizationMember->save();
                }
            }
            ActivityLogLib::addLog('User has created a new organization named ' . $organization->name . ' successfully.', 'success');
            Toastr::success('New company named ' . $organization->name . ' has created successfully.', 'success');
            return redirect()->back();
        } else {
            ActivityLogLib::addLog('User has tried to create new organization but failed.', 'error');
            Toastr::error('W00ps! Something went wrong. Try again.', 'error');
            return redirect()->back();
        }
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $organization = Organization::with('organization_members', 'projects')->where('status', 1)->findOrFail($id);
        return view('pages.organization.show', compact('organization'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $organization = Organization::findOrFail($id);
        $phoneCodes = CountryCode::all();
        $createView = view('pages.organization._edit', ['organization' => $organization, 'phoneCodes' => $phoneCodes])->render();
        return (['editCompany' => $createView]);
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
        $organization = Organization::findOrFail($id);
        $this->validate($request, [
            'name' => 'required | unique:organizations,name,' . $organization->id,
        ], [
            'name.required' => 'Company name is required.',
            'name.unique' => 'Company name is already registered'
        ]);

        // Code for insert profile image
        $name = $request->input('name');
        $slug = Str::slug($name);
        if ($request->has('image')) {
            $image = $request->file('image');
            $newImageName = $slug . "_" . $organization->id . '.' . $image->getClientOriginalExtension();
            $destinationPath = 'images/organization/logos';
            $image->move($destinationPath, $newImageName);
        }

        if ($request->has('name')) {
            $organization->name = $request->input('name');
        }

        if ($request->has('code_name')) {
            $organization->code_name = $request->input('code_name');
        }

        if ($request->has('address')) {
            $organization->address = $request->input('address');
        }

        if ($request->has('image')) {
            $organization->logo = $newImageName;
        }

        $organization->updated_at = date('Y-m-d');
        $updateOrganization = $organization->save();

        if ($updateOrganization) {
            ActivityLogLib::addLog('User has updated organization info named ' . $organization->name . ' successfully.', 'success');
            Toastr::success('Organization info named ' . $organization->name . ' has updated successfully.', 'success');
            return redirect()->back();
        } else {
            ActivityLogLib::addLog('User has tried to update organization info named' . $organization->name . ' but failed.', 'error');
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
        $organization = Organization::with('roles')->findOrFail($id);
        $companyName = $organization->name;
        $delete = $organization->delete();

        if ($delete) {
            ActivityLogLib::addLog('User has deleted organization named ' . $companyName . ' successfully.', 'success');
            Toastr::success('Organization has deleted successfully.', 'success');
            return redirect()->back();
        } else {
            ActivityLogLib::addLog('User has tried to delete' . $companyName . ' organization but failed.', 'error');
            Toastr::error('W00ps! Something went wrong. Try again.', 'error');
            return redirect()->back();
        }
    }
}
