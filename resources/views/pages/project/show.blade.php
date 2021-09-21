@extends('layouts.layout')

@section('title', 'Projects Detail')

@push('css')

@endpush
@section('breadcrumb')
<li class="breadcrumb-item"><a href="#">Projects</a></li>
<li class="breadcrumb-item active">Projects Detail</li>
@endsection

@section('content')
<!-- Default box -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Projects Detail</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-9 order-2 order-md-1">
                <div class="row">
                    <div class="col-12 col-sm-4">
                        <div class="info-box bg-light">
                            <div class="info-box-content">
                                <span class="info-box-text text-center text-muted">Number of Scopes</span>
                                <span class="info-box-number text-center text-muted mb-0">{{ $numberOfScopes }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="info-box bg-light">
                            <div class="info-box-content">
                                <span class="info-box-text text-center text-muted">Estimated project duration</span>
                                <span class="info-box-number text-center text-muted mb-0">{{ $timeDuration }} hr(s)</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="info-box bg-light">
                            <div class="info-box-content">
                                <span class="info-box-text text-center text-muted">Time Remaining</span>
                                <span class="info-box-number text-center text-muted mb-0">{{ $timeRemaining }} hr(s)</span>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="row">
                    <div class="col-12">
                        <h4>Project Scopes</h4>
                        <div class="post">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Scope</th>
                                        <th>Terget Url</th>
                                        <th>Comment</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!is_null($project->project_scopes))
                                    @foreach($project->project_scopes as $scope)
                                    <tr>
                                        <td>{{ $scope->scope->name }}</td>
                                        <td>{{ $scope->terget_url }}</td>
                                        <td>{{ $scope->comment }}</td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <h4>Project Brief</h4>
                        <div class="post">
                            {!! $project->brief !!}
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <h4>Questionnaires</h4>
                        <div class="post">
                            {!! $project->questionnaires !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-12 col-lg-3 order-1 order-md-2">
                <h5 class="mt-5 text-muted" style="margin-top: 0 !important">Organization</h5>
                <div class="text-muted">
                    <p class="text-sm">Client Organization
                        <b class="d-block">@if($project->organization){{ $project->organization->name }}@endif</b>
                    </p>
                    <p class="text-sm">Client Organization Members
                        @if(!is_null($project->organization_members))
                        @foreach($project->organization_members as $member)
                        <b class="d-block">{{ $member->user->name}} - {{$member->designation}}</b>
                        @endforeach
                        @endif
                    </p>
                </div>
                <h5 class="mt-5 text-muted">Time Duration</h5>
                <div class="text-muted">
                    <p class="text-sm">Project Start Date
                        <b class="d-block">{{ date('M d, Y', strtotime($project->start_date))}}</b>
                    </p>
                    <p class="text-sm">Project End Date
                        <b class="d-block">{{ date('M d, Y', strtotime($project->end_date))}}</b>
                    </p>
                </div>

                <h5 class="mt-5 text-muted">Moderator & Researchers</h5>
                <div class="text-muted">
                    <p class="text-sm">Moderator
                        <b class="d-block"><i class="fa-fw fas fa-user"></i> &nbsp; @if($project->moderator){{$project->moderator->name}}@endif</b>
                    </p>
                </div>
                <div class="text-muted">
                    <p class="text-sm" style="margin-bottom:0;">Researchers</p>
                    <ul class="list-unstyled">
                        @if(!is_null($project->users))
                        @foreach($project->users as $user)
                        <li>
                            <i class="fa-fw fas fa-user"></i> {{ $user->name }}
                        </li>
                        @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->
@endsection

@push('js')

@endpush