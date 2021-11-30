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

class AdminProjectReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reports = ProjectReport::with('project')->orderBy('id', 'DESC')->get();
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
        //
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
        return view('pages.report.admin.show', ['recommended' => $recommended, 'impact' => $impact, 'report' => $report, 'description' => $description]);
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
}
