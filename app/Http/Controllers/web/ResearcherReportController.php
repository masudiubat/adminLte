<?php

namespace App\Http\Controllers\web;

use App\Project;
use App\ReportImage;
use App\ProjectScope;
use App\ReportCategory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ResearcherReportController extends Controller
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
     * Search all scopes by project id 
     */
    public function search_scopes($id)
    {
        $scopes = ProjectScope::with('scope')->where('project_id', $id)->get();
        return response()->json(['scopes' => $scopes], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $userId = Auth::user()->id;
        $projects = Project::with('organization', 'project_scopes')
            ->whereHas('users', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->whereDate('start_date', '<=', date("Y-m-d"))->whereDate('end_date', '>=', date("Y-m-d"))
            ->where('is_approved', 0)
            ->where('status', 'active')
            ->get();

        $categories = ReportCategory::all();

        return view('pages.report.researcher.create', ['projects' => $projects, 'categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function files_upload(Request $request)
    {
        $id = Auth::user()->id;
        if ($request->hasFile('file')) {
            $random = Str::random(6);
            $image = $request->file('file');
            $originalName = $image->getClientOriginalName();
            $newActionName = $random . '.' . $image->getClientOriginalExtension();
            $destinationPath = 'images/temp';
            $image->move($destinationPath, $newActionName);

            // save image details
            $image = new ReportImage();
            $image->user_id = $id;
            $image->code = $random;
            $image->name = $newActionName;
            $image->original_name = $originalName;
            $image->created_at = date('Y-m-d');
            $image->save();
        }
        $images = ReportImage::where('user_id', $id)->get();
        return response()->json(['images' => $images], 200);
    }
    public function store(Request $request)
    {
        echo "<pre>";
        print_r($request->all());
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
}
