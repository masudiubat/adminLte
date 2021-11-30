<?php

namespace App\Http\Controllers\web;

use PDF;
use File;
use App\Project;
use App\ReportImage;
use App\ProjectScope;
use App\ProjectReport;
use App\ReportCategory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Library\ActivityLogLib;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
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
        $userId = Auth::user()->id;
        $reports = ProjectReport::with('project', 'user')
            ->whereHas('user', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->orderBy('id', 'DESC')
            ->get();
        ActivityLogLib::addLog('User has viewed his/her all reports successfully.', 'success');
        return view('pages.report.researcher.index', ['reports' => $reports]);
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
            ->where('is_approved', 1)
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
            $destinationPath = 'report';
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

    /**
     * Store report
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'project' => 'required',
            'scope' => 'required',
            'title' => 'required',
            'category' => 'required',
            'url' => 'required',
            'description' => 'required',
            'reproduce' => 'required',
            'security_impact' => 'required',
            'recommendation' => 'required'
        ], [
            'project.required' => 'No project selected.',
            'scope.required' => 'No scope selected.',
            'title.required' => 'Write a title, title field is blank.',
            'category.required' => 'Report category not selected.',
            'url.required' => 'Url filed is blank.',
            'description.required' => 'Description field is required.',
            'reproduce.required' => 'Reproduce field is required.',
            'security_impact.required' => 'Security impact is required',
            'recommendation.required' => 'Recommendation is required'
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

        $report->created_at = date('Y-m-d');

        $createReport = $report->save();

        if ($createReport) {
            ActivityLogLib::addLog('Researcher has created a new report named ' . $report->title . ' successfully.', 'success');
            Toastr::success('New report named ' . $report->title . ' has created successfully.', 'success');
            return redirect()->back();
        } else {
            ActivityLogLib::addLog('User has tried to create new report but failed.', 'error');
            Toastr::error('W00ps! Something went wrong. Try again.', 'error');
            return redirect()->back();
        }
        /*
        echo "<pre>";
        print_r($request->all());
        $template = $request->input('description');
        $content =  substr($template, strpos($template, "["), 8);
        $code = substr($content, 1, 6);
        $image = ReportImage::where('code', $code)->first();
        echo $image->original_name;
        */
    }

    public function replacement($string, array $placeholders)
    {
        $resultString = $string;
        foreach ($placeholders as $key => $value) {
            $resultString = str_replace('[' . $key . ']', trim($value), $resultString, $i);
        }
        return $resultString;
    }

    /**
     *  Delete temp image for db
     */
    public function temp_image_delete($id)
    {
        $image = ReportImage::findOrFail($id);
        $deleteImge = $image->delete();
        $userId = Auth::user()->id;
        if ($deleteImge) {
            $images = ReportImage::where('user_id', $userId)->get();
            return response()->json(['images' => $images], 200);
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
        $description = $this->shortcodet_to_image_url($report->description);
        $impact = $this->shortcodet_to_image_url($report->security_impact);
        $recommended = $this->shortcodet_to_image_url($report->recommended_fix);

        ActivityLogLib::addLog('User has viewed report successfully.', 'success');
        return view('pages.report.researcher.show', ['recommended' => $recommended, 'impact' => $impact, 'report' => $report, 'description' => $description]);
    }

    /**
     * Downlaod pdf report
     * 
     */
    public function dwonlaod_pdf($id)
    {
        $report = ProjectReport::with('project', 'user', 'report_category', 'project_scope')->findOrFail($id);
        $description = $this->shortcodet_to_image_url($report->description);
        $impact = $this->shortcodet_to_image_url($report->security_impact);
        $recommended = $this->shortcodet_to_image_url($report->recommended_fix);

        //ActivityLogLib::addLog('User has downlaoded pdf format report successfully.', 'success');
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('pages.download.report', compact(['recommended', 'impact', 'report', 'description']))->setOptions(['defaultFont' => 'sans-serif']);
        //return view('pages.download.report', ['recommended' => $recommended, 'impact' => $impact, 'report' => $report, 'description' => $description]);
        return $pdf->download('report.pdf');
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

        $projects = Project::with('organization', 'project_scopes')
            ->whereDate('start_date', '<=', date("Y-m-d"))
            ->whereDate('end_date', '>=', date("Y-m-d"))
            ->where('is_approved', 1)
            ->where('status', 'active')
            ->orderBy('id', 'DESC')
            ->get();

        $categories = ReportCategory::all();

        return view('pages.report.admin.edit', ['report' => $report, 'projects' => $projects, 'categories' => $categories]);
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

    public function shortcodet_to_image_url($contentget)
    {
        $results = preg_match_all("/\[([^\]]*)\]/", $contentget, $matches);

        if ($results === false || $results === 0) {
            return $contentget;
        }

        [$placeholders, $figureids] = $matches;

        $figureArr = array();
        foreach ($figureids as $figure) {
            $code = preg_replace('/<[^>]*>/', '', $figure);
            $figureArr[] = $code;
        }

        $placeHolderArr = array();
        foreach ($placeholders as $placeholder) {
            $placeHolderArr[] = preg_replace('/<[^>]*>/', '', $placeholder);
        }

        $figures = ReportImage::query()
            ->whereIn('code', $figureArr)
            ->get();

        if ($figures->isEmpty()) {
            return $contentget;
        }

        foreach ($placeHolderArr as $index => $placeholder) {
            if ($figures) {
                $content = Str::of('<br/><img src="##URL##" alt="##ALT##" class="img-responsive" height="200px" width="250px"><br/>')
                    ->replace('##URL##', 'http://127.0.0.1:8000/storage/reports/' . $figures[$index]['name'])
                    ->replace('##ALT##', $figures[$index]['alt']);

                $contentget = str_replace($placeholders[$index], $content, $contentget);
            }
        }
        return $contentget;
    }

    public function shortcodet_to_pdf_image_url($contentget)
    {
        $results = preg_match_all("/\[([^\]]*)\]/", $contentget, $matches);

        if ($results === false || $results === 0) {
            return $contentget;
        }

        [$placeholders, $figureids] = $matches;

        $figureArr = array();
        foreach ($figureids as $figure) {
            $code = preg_replace('/<[^>]*>/', '', $figure);
            $figureArr[] = $code;
        }

        $placeHolderArr = array();
        foreach ($placeholders as $placeholder) {
            $placeHolderArr[] = preg_replace('/<[^>]*>/', '', $placeholder);
        }

        $figures = ReportImage::query()
            ->whereIn('code', $figureArr)
            ->get();

        if ($figures->isEmpty()) {
            return $contentget;
        }

        foreach ($placeHolderArr as $index => $placeholder) {
            if ($figures) {
                $content = Str::of('<br/><img src="##URL##" alt="##ALT##" class="img-responsive" height="200px" width="250px"><br/>')
                    ->replace('##URL##', 'http://127.0.0.1:8000/images/reports/' . $figures[$index]['name'])
                    ->replace('##ALT##', $figures[$index]['alt']);

                $contentget = str_replace($placeholders[$index], $content, $contentget);
            }
        }
        return $contentget;
    }
}
