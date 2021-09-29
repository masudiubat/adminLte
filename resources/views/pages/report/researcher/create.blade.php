@extends('layouts.layout')

@section('title', 'New Report')

@push('css')
<!-- summernote -->
<link rel="stylesheet" href="{{ asset('assets/plugins/summernote/summernote-bs4.min.css')}}">
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<!-- daterange picker -->
<link rel="stylesheet" href="{{ asset('assets/plugins/daterangepicker/daterangepicker.css')}}">
<style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #007bff;
        border-color: #006fe6;
        color: #fff;
        padding: 0 10px;
        margin-top: .31rem;
    }

    @media (min-width: 768px) {
        .col-md-1 {
            margin-top: 35px;
            text-align: center;
        }
    }
</style>
@endpush
@section('breadcrumb')
<li class="breadcrumb-item active">New Report</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">New Report</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body" style="padding-top: 5px;">
                <!-- form start -->
                <form action="{{route('client.project.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <div class="form-group">
                                <label for="mobile">Select Project<code>*</code></label>
                                <select class="form-control project" id="project" name="project" style="width: 100%;">
                                    <option value="">Select Project</option>
                                    @if(!is_null($projects))
                                    @foreach($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->title }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <div class="form-group">
                                <label for="scope">Scope</label>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Select</th>
                                            <th>Scope</th>
                                            <th>Terget Url</th>
                                            <th>Comment</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!is_null($project->project_scopes))
                                        @foreach($project->project_scopes as $scope)
                                        <tr>
                                            <td><input type="radio" class="" id="html" name="fav_language" value="{{ $scope->scope->id }}"></td>
                                            <td>{{ $scope->scope->name }}</td>
                                            <td>{{ $scope->terget_url }}</td>
                                            <td>{{ $scope->comment }}</td>
                                        </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <div class="form-group">
                                <label for="title">Report Title</label>
                                <input type="text" name="title" id="title" value="" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <div class="form-group">
                                <label for="name">Select Category</label>
                                <select class="form-control category" id="category" name="category" style="width: 100%;">
                                    <option value="">Select Category</option>
                                    @if(!is_null($categories))
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <div class="form-group">
                                <label for="url">URL/Location of Vulnerability</label>
                                <input type="text" name="url" id="url" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <div class="form-group">
                                <label for="description">Description <br /><small> Describe the vulnerability, and provide a Proof of Concept. How would you fix it? </small></label>
                                <textarea class="form-control summernote" name="description" id="summernote">What is the vulnerability?</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <div class="form-group">
                                <label for="reproduce">Steps to Reproduce <br /><small> Describe how to reproduce the vulnerability </small></label>
                                <textarea class="form-control summernote" name="reproduce" id="summernote"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <div class="form-group">
                                <label for="security_impact">Security Impact</label>
                                <textarea class="form-control summernote" name="security_impact" id="summernote">What is security impact</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <div class="form-group">
                                <label for="recommendation">Recommended Fix <br /><small> Brief description with Objective, Scope, Rules and Instruction </small></label>
                                <textarea class="form-control summernote" name="recommendation" id="summernote">Any Recommended fix you'd like us to know about this submission.</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <div class="form-group">
                                <label for="name">Questionnaires <br /><small> Answer to Commonly Ask Questions </small></label>
                                <textarea class="form-control summernote" name="questionnaires" id="summernote"></textarea>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <div class="float-right text-right"><button type="submit" class="btn btn-primary">Submit</button></div>
                    </div>
                </form>
                <div class="scopeWraperCopy" style="display:none;">
                    <div class="row">
                        <div class="col-md-3 col-lg-3">
                            <div class="form-group">
                                <label for="name">Scope:</label>
                                <select class="form-control scope" id="scope" name="scope[]">
                                    <option>Select Scope</option>

                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <div class="form-group">
                                <label for="url">URL</label>
                                <input type="text" name="url[]" id="url" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <div class="form-group">
                                <label for="comment">Comment</label>
                                <input type="text" name="comment[]" id="comment" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-1 col-lg-1">
                            <div class="input-group-addon">
                                <a href="javascript:void(0)" class="btn btn-xs btn-danger remove"><i class="fa fa-times" aria-hidden="true"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->
@endsection

@push('js')
<!-- Summernote -->
<script src="{{ asset('assets/plugins/summernote/summernote-bs4.min.js')}}"></script>
<!-- Select2 -->
<script src="{{ asset('assets/plugins/select2/js/select2.full.min.js')}}"></script>
<!-- InputMask -->
<script src="{{ asset('assets/plugins/moment/moment.min.js')}}"></script>
<script src="{{ asset('assets/plugins/inputmask/jquery.inputmask.min.js')}}"></script>
<!-- date-range-picker -->
<script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js')}}"></script>
<script>
    //Add select 2 function
    $(function() {
        //Initialize Select2 Elements
        $('#researcher').select2({
            placeholder: 'Select Project Researcher'
        }).val('').trigger('change');

        $('#skill').select2({
            placeholder: 'Select Skills'
        }).val('').trigger('change');

        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })

        // Summernote
        $('.summernote').summernote({
            minHeight: 100,
        })

        ///Date range picker
        $('#reservation').daterangepicker({
            locale: {
                format: 'YYYY-MM-DD',
                separator: " to "
            }
        });
        //Date range picker with time picker
        $('#reservationtime').daterangepicker({
            locale: {
                format: 'YYYY-MM-DD',
                separator: " to "
            }
        })

    });
</script>

<script>
    $(document).ready(function() {

        //add category sub category fields group
        var scopeCounter = 2;
        $(".addMore").click(function() {
            var extendScopeWraper = '<div class="scopeWraper"> ' + $(".scopeWraperCopy ").html() + ' </div>';
            $('body').find('.scopeWraper:last').after(extendScopeWraper);
            categoryCounter++;
        });

        //remove category sub category fields group
        $("body").on("click", ".remove", function() {
            $(this).parents(".scopeWraper").remove();
        });


    });
</script>
<script>
    var loadProductImage = function(event) {
        var output = document.getElementById('productOutput');
        output.src = URL.createObjectURL(event.target.files[0]);
    };
</script>
<script>
    $(function() {

    })
</script>
@endpush