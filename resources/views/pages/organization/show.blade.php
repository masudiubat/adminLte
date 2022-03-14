@extends('layouts.layout')

@section('title', 'Report')

@push('css')
<style>
    .widget-user-image {
        top: 90px px !important;
    }
</style>
@endpush
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('organization')}}">Organization</a></li>
<li class="breadcrumb-item active">Details</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="card card-widget widget-user">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header bg-info">
                        <h4 class="widget-user-desc">{{ $organization->name }}</h4>
                        <h6 class="widget-user-username">{{ $organization->address }}</h6>
                    </div>
                    <div class="widget-user-image">
                        <img class="img-circle elevation-2" src="{{ asset('images/organization/logos/'. $organization->logo)}}" alt="Organization Avatar">
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">{{ $organization->projects->count() }}</h5>
                                    <span class="description-text">Projects</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">13,000</h5>
                                    <span class="description-text">Reports</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4">
                                <div class="description-block">
                                    <h5 class="description-header">35</h5>
                                    <span class="description-text">Working Days</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    </div>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Project</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($organization->projects->count())
                        @php $i = 1; @endphp
                        @foreach($organization->projects->reverse() as $key => $project)
                        <tr>
                            <td>@php echo $i++; @endphp</td>
                            <td><a href="{{route('admin.project.show', $project->id)}}">{{$project->title}}</a></td>
                            <td>{{ date('M d, Y', strtotime($project->start_date))}}</td>
                            <td>{{ date('M d, Y', strtotime($project->end_date))}}</td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')

@endpush