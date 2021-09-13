<?php

namespace App\Http\Controllers\web;

use App\User;
use App\CompanyUser;
use App\UserCompany;
use Illuminate\Http\Request;
use App\Library\ActivityLogLib;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;

class CompanyUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $assignUsers = CompanyUser::with('user', 'user_company')->get();
        $companies = UserCompany::all();
        $users = User::where('role', '=', 'client')->get();
        ActivityLogLib::addLog('User has viewd assign user to comapny module successfully.', 'success');
        return view('pages.company-user.index', ['assignUsers' => $assignUsers, 'companies' => $companies, 'users' => $users]);
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
            'company' => 'required ',
            'user' => 'required',
        ], [
            'company.required' => 'Company name is required.',
            'user.required' => 'User name is required'
        ]);

        $companyUser = new CompanyUser();

        if ($request->has('company')) {
            $companyUser->user_company_id = $request->input('company');
        }

        if ($request->has('user')) {
            $companyUser->user_id = $request->input('user');
        }

        if ($request->has('designation')) {
            $companyUser->designation = $request->input('designation');
        }

        if ($request->has('leading_person')) {
            $companyUser->is_leading_person = $request->input('leading_person');
        }

        if ($request->has('leading_person') && $request->input('leading_person') == 1) {
            $companyUser->role = 'leading person';
        }

        $companyUser->created_at = date('Y-m-d');
        $save = $companyUser->save();

        if ($save) {
            ActivityLogLib::addLog('User has assigned an user to company successfully.', 'success');
            Toastr::success('User has assigned an user to company successfully.', 'success');
            return redirect()->back();
        } else {
            ActivityLogLib::addLog('User has tried to assigned an user to company but failed.', 'error');
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
        $companyUser = CompanyUser::with('user', 'user_company')->findOrFail($id);
        $companies = UserCompany::all();
        $users = User::where('role', '=', 'client')->get();

        $createView = view('pages.company-user._edit', ['companyUser' => $companyUser, 'companies' => $companies, 'users' => $users])->render();
        return (['editCompanyUser' => $createView]);
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
