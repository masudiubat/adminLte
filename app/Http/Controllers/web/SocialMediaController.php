<?php

namespace App\Http\Controllers\web;

use App\SocialMedia;
use Illuminate\Http\Request;
use App\Library\ActivityLogLib;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;

class SocialMediaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $media = SocialMedia::all();
        return view('pages.social-media.index', ['media' => $media]);
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
            'name' => 'required | unique:social_media',
            'icon' => 'required | unique:social_media'
        ], [
            'name.required' => 'Social media name is required.',
            'name.unique' => 'Social media name is already registered',
            'icon.required' => 'Icon class name is required.',
            'icon.unique' => 'Icon class name is already registered'
        ]);

        $media = new SocialMedia();

        if ($request->has('name')) {
            $media->name = $request->input('name');
        }

        if ($request->has('icon')) {
            $media->icon = $request->input('icon');
        }

        $media->created_at = date('Y-m-d');
        $storeMedia = $media->save();

        if ($storeMedia) {
            ActivityLogLib::addLog('User has inserted a new social media named ' . $media->name . ' successfully.', 'success');
            Toastr::success('New social media named ' . $media->name . ' has created successfully.', 'success');
            return redirect()->back();
        } else {
            ActivityLogLib::addLog('User has tried to insert new social media but failed.', 'error');
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
        $media = SocialMedia::findOrFail($id);
        $createView = view('pages.social-media._edit', ['media' => $media])->render();
        return (['editMedia' => $createView]);
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
        $media = SocialMedia::findOrFail($id);

        $this->validate($request, [
            'name' => 'required | unique:social_media,name,' . $media->id,
            'icon' => 'required | unique:social_media,icon,' . $media->id
        ], [
            'name.required' => 'Social media name is required.',
            'name.unique' => 'Social media name is already registered',
            'icon.required' => 'Icon class name is required.',
            'icon.unique' => 'Icon class name is already registered'
        ]);

        if ($request->has('name')) {
            $media->name = $request->input('name');
        }

        if ($request->has('icon')) {
            $media->icon = $request->input('icon');
        }

        $media->updated_at = date('Y-m-d');
        $updateMedia = $media->save();

        if ($updateMedia) {
            ActivityLogLib::addLog('User has updated social media named ' . $media->name . ' successfully.', 'success');
            Toastr::success('Social media named ' . $media->name . ' has updated successfully.', 'success');
            return redirect()->back();
        } else {
            ActivityLogLib::addLog('User has tried to update social media but failed.', 'error');
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
        $media = SocialMedia::findOrFail($id);
        $mediaName = $media->name;
        $deleteMedia = $media->delete();

        if ($deleteMedia) {
            ActivityLogLib::addLog('User has deleted social media named ' . $mediaName . ' successfully.', 'success');
            Toastr::success('Social media has deleted successfully.', 'success');
            return redirect()->back();
        } else {
            ActivityLogLib::addLog('User has tried to delete' . $mediaName . ' social media but failed.', 'error');
            Toastr::error('W00ps! Something went wrong. Try again.', 'error');
            return redirect()->back();
        }
    }
}
