<?php

namespace App\Http\Controllers\web;

use App\Organization;
use App\ProjectReport;
use App\OrganizationMember;
use Illuminate\Http\Request;
use App\Library\ActivityLogLib;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OrganizationReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userId = Auth::user()->id;
        $user = Auth::user();
        $projects = $user->organization_member->projects()->pluck('project_id')->toArray();
        $reports = ProjectReport::with(array('project' => function ($query) {
            $query->whereDate('start_date', '<=', date("Y-m-d"))
                ->whereDate('end_date', '>=', date("Y-m-d"));
        }))
            ->whereIn('project_id', $projects)
            ->get();

        ActivityLogLib::addLog('User has viewed report index/list successfully.', 'success');
        return view('pages.report.client.index', compact('reports'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::user();
        $projects = $user->organization_member->projects()->pluck('project_id')->toArray();

        $report = ProjectReport::with('project', 'user', 'report_category', 'project_scope')->whereIn('project_id', $projects)->findOrFail($id);
        $description = shortcodet_to_image_url($report->description);
        $impact = shortcodet_to_image_url($report->security_impact);
        $recommended = shortcodet_to_image_url($report->recommended_fix);
        $limitation = shortcodet_to_image_url($report->constraint);

        ActivityLogLib::addLog('User has viewed report successfully.', 'success');
        return view('pages.report.client.show', ['recommended' => $recommended, 'impact' => $impact, 'report' => $report, 'description' => $description, 'limitation' => $limitation]);
    }
}
