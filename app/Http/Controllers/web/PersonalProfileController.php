<?php

namespace App\Http\Controllers\web;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PersonalProfileController extends Controller
{
    /**
     * method for show personal profile detail
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('pages.user.personal-profile.show', ['user' => $user]);
    }
}
