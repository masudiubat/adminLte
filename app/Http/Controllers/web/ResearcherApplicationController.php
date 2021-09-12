<?php

namespace App\Http\Controllers\web;

use App\Skill;
use App\CountryCode;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\ResearcherApplication;
use App\Library\ActivityLogLib;
use App\ResearcherCertification;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;

class ResearcherApplicationController extends Controller
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
        $codes = CountryCode::all();
        $skills = Skill::all();
        return view('pages.researcher-application.create', ['codes' => $codes, 'skills' => $skills]);
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

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required | unique:researcher_applications',
            'country_code' => 'required',
            'phone' => 'required',
            'dob' => 'required',
            'father_name' => 'required',
            'mother_name' => 'required',
            'present_address' => 'required',
            'permanent_address' => 'required',
            'profile_image' => 'required | mimes:png,jpg,jpeg',
            'photo_identity' => 'required | mimes:png,jpg,jpeg',
            'attachment' => 'mimes:png,jpg,jpeg,doc,pdf,docx,zip'
        ], [
            'name.required' => 'Applicant name is required.',
            'email.required' => 'Email is required.',
            'email.unique' => 'email is already registered',
            'country_code' => 'Country code is required',
            'phone.required' => 'Phone number is required.',
            'dob.required' => 'date of birth is required.',
            'father_name.required' => 'Father name is required.',
            'mother_name.required' => 'Mother name is required.',
            'present_address.required' => 'Present address is required.',
            'permanent_address.required' => 'Permanent address is required.',
            'profile_image.required' => 'Profile image address is required.',
            'profile_image.mimes' => 'Photo must be jpg, jpeg or png format.',
            'photo_identity.required' => 'Photo identity is required.',
            'photo_identity.mimes' => 'Photo must be jpg, jpeg or png format.'
        ]);

        // Code for insert profile image        
        if ($request->has('profile_image')) {
            $image = $request->file('profile_image');
            $name = $request->input('name');
            $slug = Str::slug($name);
            $lastApplicantId = ResearcherApplication::select('id', 'name')->orderBy('id', 'DESC')->first();
            if (!is_null($lastApplicantId)) {
                $newProfileImageName = "thumbnail_" . $slug . "_" . $lastApplicantId->id . '.' . $image->getClientOriginalExtension();
            } else {
                $newProfileImageName = "thumbnail_" . $slug . "_1" . '.' . $image->getClientOriginalExtension();
            }
            $destinationPath = 'images/users';
            $image->move($destinationPath, $newProfileImageName);
        }

        // Code for insert photo identity       
        if ($request->has('photo_identity')) {
            $image = $request->file('photo_identity');
            $name = $request->input('name');
            $slug = Str::slug($name);
            $lastApplicantId = ResearcherApplication::select('id', 'name')->orderBy('id', 'DESC')->first();
            if (!is_null($lastApplicantId)) {
                $newIdentityImageName = "identity_" . $slug . "_" . $lastApplicantId->id . '.' . $image->getClientOriginalExtension();
            } else {
                $newIdentityImageName = "identity_" . $slug . "_1" . '.' . $image->getClientOriginalExtension();
            }
            $destinationPath = 'images/photo-identity';
            $image->move($destinationPath, $newIdentityImageName);
        }

        // Code for insert attachment file      
        if ($request->has('attachment')) {
            $image = $request->file('attachment');
            $name = $request->input('name');
            $slug = Str::slug($name);
            $lastApplicantId = ResearcherApplication::select('id', 'name')->orderBy('id', 'DESC')->first();
            if (!is_null($lastApplicantId)) {
                $newAttachmentName = "attachment_" . $slug . "_" . $lastApplicantId->id . '.' . $image->getClientOriginalExtension();
            } else {
                $newAttachmentName = "attachment_" . $slug . "_1" . '.' . $image->getClientOriginalExtension();
            }
            $destinationPath = 'images/researcher-attachment';
            $image->move($destinationPath, $newAttachmentName);
        }

        $application = new ResearcherApplication();

        if ($request->has('name')) {
            $application->name = $request->input('name');
        }

        if ($request->has('email')) {
            $application->email = $request->input('email');
        }

        if ($request->has('country_code')) {
            $country = CountryCode::findOrFail($request->input('country_code'));
            $application->country_code = $country->code;
            $application->country = $country->country;
        }

        if ($request->has('phone')) {
            $application->phone = $request->input('phone');
        }

        if ($request->has('dob')) {
            $application->date_of_birth = $request->input('dob');
        }

        if ($request->has('father_name')) {
            $application->father_name = $request->input('father_name');
        }

        if ($request->has('mother_name')) {
            $application->mother_name = $request->input('mother_name');
        }

        if ($request->has('present_address')) {
            $application->present_address = $request->input('present_address');
        }

        if ($request->has('permanent_address')) {
            $application->permanent_address = $request->input('permanent_address');
        }

        if ($request->has('about')) {
            $application->about = $request->input('about');
        }

        if ($request->has('profile_image')) {
            $application->profile_image = $newProfileImageName;
        }

        if ($request->has('photo_identity')) {
            $application->photo_identity = $newIdentityImageName;
        }

        if ($request->has('attachment')) {
            $application->attachment = $newAttachmentName;
        }

        if ($request->has('preferred_interview_date')) {
            $application->preferred_interview_date = $request->input('preferred_interview_date');
        }

        $application->created_at = date('Y-m-d');
        $storeApplication = $application->save();
        if ($storeApplication) {
            $application->skills()->attach($request->input('skill'));
            if (!is_null($request->input('certification'))) {
                foreach ($request->input('certification') as $certification) {
                    if ($certification) {
                        $createCertification = new ResearcherCertification();
                        $createCertification->researcher_application_id = $application->id;
                        $createCertification->name = $certification;
                        $createCertification->created_at = date('Y-m-d');
                        $createCertification->save();
                    }
                }
            }
            Toastr::success('Your application has submitted successfully.', 'success');
            return redirect()->back();
        } else {
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

    private static function convert_date($date)
    {
        return date('Y-m-d', strtotime($date));
    }
}
