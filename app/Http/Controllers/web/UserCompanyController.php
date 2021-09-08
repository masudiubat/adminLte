<?php

namespace App\Http\Controllers\web;

use App\CountryCode;
use App\UserCompany;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Library\ActivityLogLib;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;

class UserCompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies = UserCompany::all();
        $phoneCodes = CountryCode::all();
        ActivityLogLib::addLog('User has viewed company list successfully.', 'success');
        return view('pages.company.index', ['companies' => $companies, 'phoneCodes' => $phoneCodes]);
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
            'name' => 'required | unique:user_companies',
            'email' => 'required | email | unique:user_companies'
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
        $lastId = UserCompany::select('id')->orderBy('id', 'DESC')->first();
        if ($request->has('image')) {
            $image = $request->file('image');
            if (!is_null($lastId)) {
                $newId = $lastId->id + 1;
                $newImageName = $slug . "_" . $newId . '.' . $image->getClientOriginalExtension();
            } else {
                $newImageName = $slug . "_1" . '.' . $image->getClientOriginalExtension();
            }
            $destinationPath = 'images/company/logos';
            $image->move($destinationPath, $newImageName);
        }

        $company = new UserCompany();

        if ($request->has('name')) {
            $company->name = $request->input('name');
        }

        if ($request->has('code_name')) {
            $company->code_name = $request->input('code_name');
        }

        if ($request->has('email')) {
            $company->email = $request->input('email');
        }

        if ($request->has('country_code')) {
            $company->country_code = $request->input('country_code');
        }

        if ($request->has('phone')) {
            $company->phone = $request->input('phone');
        }

        if ($request->has('address')) {
            $company->address = $request->input('address');
        }

        if ($request->has('image')) {
            $company->logo = $newImageName;
        }

        $company->created_at = date('Y-m-d');
        $storeCompany = $company->save();

        if ($storeCompany) {
            ActivityLogLib::addLog('User has created a new company named ' . $company->name . ' successfully.', 'success');
            Toastr::success('New company named ' . $company->name . ' has created successfully.', 'success');
            return redirect()->back();
        } else {
            ActivityLogLib::addLog('User has tried to create new company but failed.', 'error');
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
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $company = UserCompany::findOrFail($id);
        $phoneCodes = CountryCode::all();
        $createView = view('pages.company._edit', ['company' => $company, 'phoneCodes' => $phoneCodes])->render();
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
        $company = UserCompany::findOrFail($id);
        $this->validate($request, [
            'name' => 'required | unique:user_companies,name,' . $company->id,
            'email' => 'required | email | unique:user_companies,email,' . $company->id
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
            $newImageName = $slug . "_" . $company->id . '.' . $image->getClientOriginalExtension();
            $destinationPath = 'images/company/logos';
            $image->move($destinationPath, $newImageName);
        }

        if ($request->has('name')) {
            $company->name = $request->input('name');
        }

        if ($request->has('code_name')) {
            $company->code_name = $request->input('code_name');
        }

        if ($request->has('email')) {
            $company->email = $request->input('email');
        }

        if ($request->has('country_code')) {
            $company->country_code = $request->input('country_code');
        }

        if ($request->has('phone')) {
            $company->phone = $request->input('phone');
        }

        if ($request->has('address')) {
            $company->address = $request->input('address');
        }

        if ($request->has('image')) {
            $company->logo = $newImageName;
        }

        $company->updated_at = date('Y-m-d');
        $updateCompany = $company->save();

        if ($updateCompany) {
            ActivityLogLib::addLog('User has updated company info named ' . $company->name . ' successfully.', 'success');
            Toastr::success('Company info named ' . $company->name . ' has updated successfully.', 'success');
            return redirect()->back();
        } else {
            ActivityLogLib::addLog('User has tried to update comapny info named' . $company->name . ' but failed.', 'error');
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
        $company = UserCompany::with('roles')->findOrFail($id);
        $companyName = $company->name;
        $delete = $company->delete();

        if ($delete) {
            ActivityLogLib::addLog('User has deleted company named ' . $companyName . ' successfully.', 'success');
            Toastr::success('Company has deleted successfully.', 'success');
            return redirect()->back();
        } else {
            ActivityLogLib::addLog('User has tried to delete' . $companyName . ' company but failed.', 'error');
            Toastr::error('W00ps! Something went wrong. Try again.', 'error');
            return redirect()->back();
        }
    }
}
