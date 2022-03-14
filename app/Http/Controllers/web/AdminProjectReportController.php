<?php

namespace App\Http\Controllers\web;

use App\Project;
use App\ReportImage;
use App\ProjectReport;
use App\ReportCategory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Library\ActivityLogLib;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class AdminProjectReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reports = ProjectReport::with('project')->where('is_archive', false)->orderBy('id', 'DESC')->get();
        return view('pages.report.admin.index', ['reports' => $reports]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $projects = Project::with('organization', 'project_scopes')
            ->whereDate('start_date', '<=', date("Y-m-d"))
            ->whereDate('end_date', '>=', date("Y-m-d"))
            ->where('is_approved', 1)
            ->where('status', 'active')
            ->get();

        $categories = ReportCategory::all();

        return view('pages.report.admin.create', ['projects' => $projects, 'categories' => $categories]);
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
            'project' => 'required',
            'scope' => 'required',
            'title' => 'required',
            'category' => 'required',
            'url' => 'required'

        ], [
            'project.required' => 'No project selected.',
            'scope.required' => 'No scope selected.',
            'title.required' => 'Write a title, title field is blank.',
            'category.required' => 'Report category not selected.',
            'url.required' => 'Url filed is blank.'
        ]);

        $report = new ProjectReport();
        $report->user_id = Auth::user()->id;

        if ($request->has('project')) {
            $report->project_id = $request->input('project');
        }

        if ($request->has('scope')) {
            $report->project_scope_id = $request->input('scope');
        }

        if ($request->has('category')) {
            $report->report_category_id = $request->input('category');
        }

        if ($request->has('title')) {
            $report->title = $request->input('title');
        }

        if ($request->has('url')) {
            $report->vulnerability_location = $request->input('url');
        }

        if ($request->has('description')) {
            $report->description = $request->input('description');
        }

        if ($request->has('reproduce')) {
            $report->step_to_reproduce = $request->input('reproduce');
        }

        if ($request->has('security_impact')) {
            $report->security_impact = $request->input('security_impact');
        }

        if ($request->has('recommendation')) {
            $report->recommended_fix = $request->input('recommendation');
        }
        if ($request->has('limitation')) {
            $report->constraint = $request->input('limitation');
        }

        $report->created_at = date('Y-m-d');

        $createReport = $report->save();

        if ($createReport) {
            ActivityLogLib::addLog('User has created a new report named ' . $report->title . ' successfully.', 'success');
            Toastr::success('New report named ' . $report->title . ' has created successfully.', 'success');
            return redirect()->back();
        } else {
            ActivityLogLib::addLog('User has tried to create new report but failed.', 'error');
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
        $report = ProjectReport::with('project', 'user', 'report_category', 'project_scope')->findOrFail($id);
        $description = shortcodet_to_image_url($report->description);
        $impact = shortcodet_to_image_url($report->security_impact);
        $recommended = shortcodet_to_image_url($report->recommended_fix);
        $limitation = shortcodet_to_image_url($report->constraint);

        ActivityLogLib::addLog('User has viewed report successfully.', 'success');
        return view('pages.report.admin.show', ['recommended' => $recommended, 'impact' => $impact, 'report' => $report, 'description' => $description, 'limitation' => $limitation]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $report = ProjectReport::with('project', 'user', 'report_category', 'project_scope')->findOrFail($id);

        $categories = ReportCategory::all();

        return view('pages.report.admin.edit', ['report' => $report, 'categories' => $categories]);
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
        $this->validate($request, [
            'title' => 'required',
            'category' => 'required',
            'url' => 'required'

        ], [
            'title.required' => 'Write a title, title field is blank.',
            'category.required' => 'Report category not selected.',
            'url.required' => 'Url filed is blank.'
        ]);

        $report = ProjectReport::findOrFail($id);

        if ($request->has('category')) {
            $report->report_category_id = $request->input('category');
        }

        if ($request->has('title')) {
            $report->title = $request->input('title');
        }

        if ($request->has('url')) {
            $report->vulnerability_location = $request->input('url');
        }

        if ($request->has('description')) {
            $report->description = $request->input('description');
        }

        if ($request->has('reproduce')) {
            $report->step_to_reproduce = $request->input('reproduce');
        }

        if ($request->has('security_impact')) {
            $report->security_impact = $request->input('security_impact');
        }

        if ($request->has('recommendation')) {
            $report->recommended_fix = $request->input('recommendation');
        }
        if ($request->has('limitation')) {
            $report->constraint = $request->input('limitation');
        }

        $report->updated_at = date('Y-m-d');

        $updateReport = $report->save();

        if ($updateReport) {
            ActivityLogLib::addLog('User has updated a report named ' . $report->title . ' successfully.', 'success');
            Toastr::success('Report named ' . $report->title . ' has updated successfully.', 'success');
            return redirect()->back();
        } else {
            ActivityLogLib::addLog('User has tried to update report but failed.', 'error');
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

    public function report_send_archive($id)
    {
        $report = ProjectReport::findOrFail($id);
        $report->is_archive = 1;
        $sendArchive = $report->save();
        if ($sendArchive) {
            ActivityLogLib::addLog('User has send report to archive successfully.', 'success');
            Toastr::success('Report has send to successfully.', 'success');
            return redirect()->back();
        } else {
            ActivityLogLib::addLog('User has tried to send a report to archive but failed.', 'error');
            Toastr::error('W00ps! Something went wrong. Try again.', 'error');
            return redirect()->back();
        }
    }

    public function report_send_index($id)
    {
        $report = ProjectReport::findOrFail($id);
        $report->is_archive = 0;
        $sendArchive = $report->save();
        if ($sendArchive) {
            ActivityLogLib::addLog('User has send report archive to index successfully.', 'success');
            Toastr::success('Report has send to successfully.', 'success');
            return redirect()->back();
        } else {
            ActivityLogLib::addLog('User has tried to send a report archive to index but failed.', 'error');
            Toastr::error('W00ps! Something went wrong. Try again.', 'error');
            return redirect()->back();
        }
    }

    public function archieve()
    {
        $reports = ProjectReport::with('project')->where('is_archive', true)->orderBy('id', 'DESC')->get();
        return view('pages.report.admin.archieve', ['reports' => $reports]);
    }
}
