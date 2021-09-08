<?php

namespace App\Http\Controllers\web;

use App\User;
use App\CountryCode;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Library\ActivityLogLib;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::with('roles')->get();
        $codes = CountryCode::all();
        $roles = Role::all();
        ActivityLogLib::addLog('View user list successfully', 'success');
        return view('pages.user.index', ['users' => $users, 'codes' => $codes, 'roles' => $roles]);
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
            'name' => 'required',
            'email' => 'required | email | unique:users',
            'country_code' => 'required',
            'phone' => 'required | unique:users',
            'password' => 'required | min:8 | confirmed'
        ], [
            'name.required' => 'Woops! Name is missiong.',
            'country_code.required' => 'Woops! Country code is not selected.',
            'email.required' => 'Woops! Email is missiong.',
            'phone.required' => 'Woops! Phone number is missiong.',
            'email.email' => 'Woops! Wrong email format.',
            'email.unique' => 'Woops! Given email address is already registered.',
            'phone.unique' => 'Woops! Given mobile number is already in used.',
            'password.required' => 'Woops! Password is missiong.',
            'password.min' => 'Password length must be minimum 8 digits.',
            'password.confirmed' => 'Woops! Confirmation password are not match.'
        ]);

        // Code for insert profile image
        /*
        $name = $request->input('name');
        if ($request->has('image')) {
            $slug = Str::slug($name);
            $lastId = User::select('id')->orderBy('id', 'DESC')->first();
            $image = $request->file('image');
            if ($lastId->id != null) {
                $newId = $lastId->id + 1;
                $newImageName = $slug . "_" . $newId . '.' . $image->getClientOriginalExtension();
            } else {
                $newImageName = $slug . "_1" . '.' . $image->getClientOriginalExtension();
            }
            $destinationPath = 'images/users';
            $image->move($destinationPath, $newImageName);
        }
        */

        $user = new User();

        if ($request->has('name')) {
            $user->name = $request->input('name');
        }

        if ($request->has('email')) {
            $user->email = $request->input('email');
        }

        if ($request->has('country_code')) {
            $user->country_code = $request->input('country_code');
        }

        if ($request->has('phone')) {
            $user->phone = $request->input('phone');
        }

        if ($request->has('address')) {
            $user->address = $request->input('address');
        }

        if ($request->has('role')) {
            $roleName = Role::findOrFail($request->input('role'));
            $user->role = $roleName->name;
        }

        $user->password = bcrypt($request->input('password'));
        $user->created_at = date('Y-m-d');
        $createUser = $user->save();

        if ($createUser) {
            $user->roles()->attach($request->input('role'));
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
        $user = User::with('roles')->findOrFail($id);

        $roleArr = array();
        foreach ($user->roles as $exRole) {
            $roleArr[] = $exRole->id;
        }
        $codes = CountryCode::all();
        $roles = Role::all();
        $createView = view('pages.user._edit', ['roleArr' => $roleArr, 'user' => $user, 'codes' => $codes, 'roles' => $roles])->render();
        return (['editUser' => $createView]);
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
            'password' => 'required | min:8 | confirmed'
        ], [
            'name.required' => 'Woops! Name is missiong.',
            'country_code.required' => 'Woops! Country code is not selected.',
            'email.required' => 'Woops! Email is missiong.',
            'phone.required' => 'Woops! Phone number is missiong.',
            'email.email' => 'Woops! Wrong email format.',
            'email.unique' => 'Woops! Given email address is already registered.',
            'phone.unique' => 'Woops! Given mobile number is already in used.',
            'password.required' => 'Woops! Password is missiong.',
            'password.min' => 'Password length must be minimum 8 digits.',
            'password.confirmed' => 'Woops! Confirmation password are not match.'
        ]);

        if ($request->has('name')) {
            $user->name = $request->input('name');
        }

        if ($request->has('email')) {
            $user->email = $request->input('email');
        }

        if ($request->has('country_code')) {
            $user->country_code = $request->input('country_code');
        }

        if ($request->has('phone')) {
            $user->phone = $request->input('phone');
        }

        if ($request->has('address')) {
            $user->address = $request->input('address');
        }

        if ($request->has('role')) {
            $roleName = Role::findOrFail($request->input('role'));
            $user->role = $roleName->name;
        }

        $user->password = bcrypt($request->input('password'));
        $user->created_at = date('Y-m-d');
        $createUser = $user->save();

        if ($createUser) {
            $user->roles()->sync($request->input('role'));
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
     *  Chagne user password
     */
    public function change_user_password(Request $request, $id)
    {
        $this->validate($request, [
            'password' => 'required | min:8 | confirmed'
        ], [
            'password.required' => 'Password field is mandatory field.',
            'password.min' => 'Password length must be minimum 8 digits.',
            'password.confirmed' => 'Confirmation password are not match.'
        ]);

        $user = User::findOrFail($id);
        $user->password = bcrypt($request->input('password'));
        $user->updated_at = date('Y-m-d');
        $userPasswordChagne = $user->save();

        if ($userPasswordChagne) {
            ActivityLogLib::addLog('User has changed password for ' . $user->name . ' successfully.', 'success');
            Toastr::success('User password has changed successfully.', 'success');
            return redirect()->back();
        } else {
            ActivityLogLib::addLog('User has tried to changed password.', 'error');
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
