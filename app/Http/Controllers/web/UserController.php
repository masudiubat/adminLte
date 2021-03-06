<?php

namespace App\Http\Controllers\web;

use App\User;
use App\CountryCode;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Library\ActivityLogLib;
use App\Mail\UserVerificationMail;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserAccessCredentialMail;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::with('roles')->where('status', 1)->get();
        $codes = CountryCode::all();
        $roles = Role::all();
        $trashed = User::with('roles')->where('status', 0)->get();
        ActivityLogLib::addLog('View user list successfully', 'success');
        return view('pages.user.index', ['users' => $users, 'codes' => $codes, 'roles' => $roles, 'trashed' => $trashed]);
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
        ], [
            'name.required' => 'Woops! Name is missiong.',
            'country_code.required' => 'Woops! Country code is not selected.',
            'email.required' => 'Woops! Email is missiong.',
            'phone.required' => 'Woops! Phone number is missiong.',
            'email.email' => 'Woops! Wrong email format.',
            'email.unique' => 'Woops! Given email address is already registered.',
            'phone.unique' => 'Woops! Given mobile number is already in used.',
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
            $country = CountryCode::findOrFail($request->input('country_code'));
            $user->country_code = $country->code;
            $user->country = $country->country;
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
        $user = User::with('project_reports', 'projects')->where('status', 1)->findOrFail($id);
        $reportCount = $user->project_reports->count();

        return view('pages.user.show', compact('user', 'reportCount'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::with('roles')->where('status', 1)->findOrFail($id);

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
        $user = User::where('status', 1)->findOrFail($id);
        $verificationUrl = 'email/verify/' . $user->id . "/" . sha1(time());
        echo $verificationUrl;
        die();
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required | email | unique:users,email,' . $user->id,
            'country_code' => 'required',
            'phone' => 'required | unique:users,phone,' . $user->id
        ], [
            'name.required' => 'Woops! Name is missiong.',
            'country_code.required' => 'Woops! Country code is not selected.',
            'email.required' => 'Woops! Email is missiong.',
            'phone.required' => 'Woops! Phone number is missiong.',
            'email.email' => 'Woops! Wrong email format.',
            'email.unique' => 'Woops! Given email address is already registered.',
            'phone.unique' => 'Woops! Given mobile number is already in used.'
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

        if ($request->has('address')) {
            $user->address = $request->input('address');
        }

        if ($request->has('role')) {
            $roleName = Role::findOrFail($request->input('role'));
            $user->role = $roleName->name;
        }
        $password = Str::random(8);
        $user->password = bcrypt($password);
        $user->created_at = date('Y-m-d');
        $createUser = $user->save();

        if ($createUser) {
            $user->roles()->sync($request->input('role'));
            ActivityLogLib::addLog('User has updated ' . $user->name . ' info successfully.', 'success');
            Toastr::success('New user has created successfully.', 'success');
            return redirect()->back();
        } else {
            ActivityLogLib::addLog('User update attempt failed.', 'error');
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

        $user = User::where('status', 1)->findOrFail($id);
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
        $user = User::where('status', 1)->findOrFail($id);
        $user->status = 0;
        $userDelete = $user->save();
        if ($userDelete) {
            ActivityLogLib::addLog('Admin has Deleted ' . $user->name . ' successfully.', 'success');
            Toastr::success('User has deleted successfully.', 'success');
            return redirect()->back();
        } else {
            ActivityLogLib::addLog('Admin has tried to delete user.', 'error');
            Toastr::error('W00ps! Something went wrong. Try again.', 'error');
            return redirect()->back();
        }
    }
}
