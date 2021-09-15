<?php

namespace App\Http\Controllers\web;

use App\CountryCode;
use App\Organization;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Library\ActivityLogLib;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $organizations = Organization::all();
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
        $this->validate($request, [
            'name' => 'required | unique:organizations',
            'email' => 'required | email | unique:organizations'
        ], [
            'name.required' => 'Company name is required.',
            'name.unique' => 'Company name is already registered',
            'email.required' => 'Email address is required.',
            'email.unique' => 'Email address is already registered',
            'email.email' => 'Invalid email address'
        ]);

        // Code for insert profile image
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

        if ($request->has('email')) {
            $organization->email = $request->input('email');
        }

        if ($request->has('country_code')) {
            $organization->country_code = $request->input('country_code');
        }

        if ($request->has('phone')) {
            $organization->phone = $request->input('phone');
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
            'email' => 'required | email | unique:organizations,email,' . $organization->id
        ], [
            'name.required' => 'Company name is required.',
            'name.unique' => 'Company name is already registered',
            'email.required' => 'Email address is required.',
            'email.unique' => 'Email address is already registered',
            'email.email' => 'Invalid email address'
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

        if ($request->has('email')) {
            $organization->email = $request->input('email');
        }

        if ($request->has('country_code')) {
            $organization->country_code = $request->input('country_code');
        }

        if ($request->has('phone')) {
            $organization->phone = $request->input('phone');
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
