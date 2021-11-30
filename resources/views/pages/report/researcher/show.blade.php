@extends('layouts.layout')

@section('title', 'Report')

@push('css')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
<style>
    .card-body body {
        font-size: 14pt;
        margin-left: 8px;
        margin-right: 8px;
        font-family: Calibri, sans-serif;
    }

    .card-body h2 {
        color: #e84b21;
    }

    .card-body h3,
    .card-body h4,
    .card-body h5,
    .card-body h6 {
        color: #e84b21;
    }

    .card-body .meta {
        color: #e84b21;
    }

    .card-body p {
        color: black;
        word-wrap: break-word;
        width: 100%;
    }

    .card-body #bg {
        background-repeat: repeat-y;
        background-position: center;
        background-attachment: fixed;
        background-size: 100%;
    }
</style>
@endpush
@section('breadcrumb')
<li class="breadcrumb-item active">Report</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Report</h3>
                <div class="text-right pull-right">
                    <a href="{{route('researcher.report.pdf.download', $report->id)}}" class="btn btn-sm btn-default">
                        <i class="fas fa-file-pdf"></i> PDF Download
                    </a>
                </div>
            </div>

            <div class="card-body">
                <a name="executive-summery" class="internal">
                    <h2>Executive Summary</h2>
                </a>

                <p>@if($report->project){{$report->project->executive_summary}}@endif</p>

                <a name="program-brief" class="internal">
                    <h2>Program Brief</h2>
                </a>

                <p>@if($report->project){!! $report->project->brief !!}@endif</p>

                <a name="methodology" class="internal">
                    <h2>Methodology</h2>
                </a>
                <p>The test was done according to penetration testing best practices. The flow from start to finish is listed below.</p>
                <br>

                <h4>Planning and Reconnaissance</h4>
                <ol>
                    <li>Scoping</li>
                    <li>Customer Q&A</li>
                    <li>Documentation</li>
                    <li>Information gathering</li>
                    <li>Discovery</li>
                </ol>

                <h4>Assessment and Testing</h4>
                <ol>
                    <li>Tool assisted assessment</li>
                    <li>Manual assessment</li>
                    <li>Exploitation</li>
                    <li>Risk analysis</li>
                    <li>Reporting</li>
                </ol>

                <h4>Post Engagement</h4>
                <ol>
                    <li>Prioritized remediation</li>
                    <li>Best practice support</li>
                    <li>Re-testing</li>
                </ol>

                <h3>Risk Factors</h3>
                <p>Each finding is assigned two factors to measure its risk. Factors are measured on a scale of 1 (very low) through 5 (very
                    high).
                </p>

                <h3>Impact</h3>
                <p>{!! $report->security_impact !!}</p>
                <h3>Likelihood</h3>
                <p>This indicates the finding's potential for exploitation. It takes into account aspects such as skill level required of
                    an attacker and relative ease of exploitation.</p>

                <h2>Criticality Definitions</h2>
                <p>Findings are grouped into three criticality levels based on their risk as calculated by their business impact and likelihood
                    of occurrence,
                    <b>
                        <i>risk = impact * likelihood</i>
                    </b>. This follows the OWASP Risk Rating Methodology.
                </p>

                <h3>High</h3>
                <p>Vulnerabilities with a high or greater business impact and high or greater likelihood are considered High severity. Risk
                    score minimum 16.</p>

                <h3>Medium</h3>
                <p>Vulnerabilities with a medium business impact and likelihood are considered Medium severity. This also includes vulnerabilities
                    that have either very high business impact combined with a low likelihood or have a low business impact combined
                    with a very high likelihood. Risk score between 5 and 15.</p>

                <h3>Low</h3>
                <p>Vulnerabilities that have either a very low business impact, maximum high likelihood, or very low likelihood, maximum
                    high business impact, are considered Low severity. Also, vulnerabilities where both business impact and likelihood
                    are low are considered Low severity. Risk score 1 through 4.</p>

                <a name="findings" class="internal">
                    <h2>Findings</h2>
                </a>

                <table class="table table-bordered table-striped table-responsive">
                    <tr>
                        <th>No</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Severity</th>
                    </tr>
                    <tr>
                        <td>1</td>

                        <td>
                            <a class="internal" href="#">Vulnerabilities with a medium business impact and likelihood are considered Medium severity. </a>
                        </td>

                        <td>Vulnerabilities with a medium business impact and likelihood are considered Medium severity. </td>
                        <td>Vulnerabilities with a medium business impact and likelihood are considered Medium severity. </td>
                    </tr>
                    <tr>
                        <td>2</td>

                        <td>
                            <a class="internal" href="#">Vulnerabilities with a medium business impact and likelihood are considered Medium severity. </a>
                        </td>

                        <td>Vulnerabilities with a medium business impact and likelihood are considered Medium severity. </td>
                        <td>Vulnerabilities with a medium business impact and likelihood are considered Medium severity. </td>
                    </tr>
                    <tr>
                        <td>3</td>

                        <td>
                            <a class="internal" href="#">Vulnerabilities with a medium business impact and likelihood are considered Medium severity. </a>
                        </td>

                        <td>Vulnerabilities with a medium business impact and likelihood are considered Medium severity. </td>
                        <td>Vulnerabilities with a medium business impact and likelihood are considered Medium severity. </td>
                    </tr>

                </table>
                <a name="summery-of-findings" class="internal">
                    <h2>Summary of Findings</h2>
                </a>

                <div id="piechart"><img src=""></div>


                <div id="barchart"><img src=""></div>

                <a name="" class="internal">
                    <h2>Lorem ipsum represents a long-held tradition for designers, typographers and the like. Some people hate it and argue for its demise, but others ignore.</h2>
                </a>

                <table class="table table-bordered table-striped table-responsive">
                    <tr>
                        <td colspan="1">
                            <span class="meta">Vulnerability Category</span>
                        </td>
                        <td>Lorem ipsum represents a long-held tradition for designers, typographers and the like. Some people hate it and argue for its demise, but others ignore.</td>
                    </tr>
                    <tr>
                        <td><span class="meta">Severity</span> </td>
                        <td>Lorem ipsum represents a long-held tradition for designers, typographers and the like. Some people hate it and argue for its demise, but others ignore.</td>

                    </tr>

                </table>

                <h3 style="margin-top: 40px">Description</h3>
                {!! $description !!}
                <h3>Steps to Reproduce</h3>
                <p>Lorem ipsum represents a long-held tradition for designers, typographers and the like. Some people hate it and argue for its demise, but others ignore.</p>

                <h3>Impact</h3>
                {!! $impact !!}

                <h3>Recommended Fix</h3>
                {!! $recommended !!}

            </div>
        </div>
    </div>
</div>
<!-- /.row -->
@endsection

@push('js')
<!-- DataTables  & Plugins -->
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{ asset('assets/plugins/jszip/jszip.min.js')}}"></script>
<script src="{{ asset('assets/plugins/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{ asset('assets/plugins/pdfmake/vfs_fonts.js')}}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
@endpush