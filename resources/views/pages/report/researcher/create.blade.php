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
<link rel="stylesheet" href="{{ asset('assets/css/dropify.css' )}}">
<link href="https://fonts.googleapis.com/css?family=Roboto|Oswald|Michroma" rel="stylesheet" type="text/css">
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
                <form action="{{route('researcher.report.store')}}" method="POST" enctype="multipart/form-data">
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
                                <textarea class="form-control summernote" name="description" id="description"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <div class="form-group">
                                <label for="reproduce">Steps to Reproduce <br /><small> Describe how to reproduce the vulnerability </small></label>
                                <textarea class="form-control summernote" name="reproduce" id="reproduce"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <div class="form-group">
                                <label for="security_impact">Security Impact</label>
                                <textarea class="form-control summernote" name="security_impact" id="security_impact"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <div class="form-group">
                                <label for="recommendation">Recommended Fix <br /><small> Brief description with Objective, Scope, Rules and Instruction </small></label>
                                <textarea class="form-control summernote" name="recommendation" id="recommendation"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <div class="form-group">
                                <label for="recommendation">Attachment</label>
                                <input type="file" name="attachment" class="dropify" id="attachment-files" multiple>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <div class="img_prv" id="img_prv"></div>
                            @if(!empty($emp_data[0]->emp_img))

                            @else
                            <img id="img_prv" style="max-width: 150px;max-height: 150px" class="img-thumbnail">
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <div class="form-group">
                                <span id="mgs_ta">
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <div class="float-right text-right"><button type="submit" class="btn btn-primary">Submit</button></div>
                    </div>
                </form>
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
<script src="{{ asset('assets/js/dropify.js' )}}"></script>

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
        $('#description').summernote({
            minHeight: 100,
            placeholder: 'What is the vulnerability?'
        })
        var placeholder = 'Replication steps with screenshorts:'
        $('#reproduce').summernote({
            minHeight: 100,
            placeholder: placeholder
        })
        $('#security_impact').summernote({
            minHeight: 100,
            placeholder: 'What is security impact'
        })
        $('#recommendation').summernote({
            minHeight: 100,
            placeholder: "Any Recommended fix you'd like us to know about this submission."
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
        // Add dropify class
        $('.dropify').dropify();

        // Add attachment files
        $('#attachment-files').on('change', function(ev) {
            if (this.files) {
                var filesAmount = this.files.length;
                var tblArr = [];
                for (i = 0; i < filesAmount; i++) {
                    var filedata = this.files[i];
                    var imgtype = filedata.type;

                    var match = ['image/jpeg', 'image/jpg', 'image/png'];

                    if (!(imgtype == match[0]) || (imgtype == match[1]) || (imgtype == match[2])) {
                        $('#mgs_ta').html('<p style="color:red">Plz select a valid type image..only jpg jpeg allowed</p>');

                    } else {
                        $('#mgs_ta').empty();
                        //---image preview

                        var reader = new FileReader();
                        reader.readAsDataURL(this.files[i]);

                        // upload files
                        var postData = new FormData();
                        postData.append('file', this.files[i]);

                        var url = "{{url('researcher/report/files/upload')}}";
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            async: true,
                            type: "post",
                            contentType: false,
                            url: url,
                            data: postData,
                            processData: false,
                        }).done(function(data) {
                            console.log(data);
                            for (var x = 0; x < data.images.length; x++) {
                                console.log(data.images[x]['original_name']);
                            }
                            $(".dropify-clear").click();
                            var createHtml = '<div class="">' +
                                '<table class="table">' +
                                '<thead>' +
                                '<tr>' +
                                '<th scope="col">code</th><th scope="col">Name</th><th>Image</th><th>Action</th>' +
                                '</tr>' +
                                '</thead>' +
                                '<tbody>' +

                                '</tbody>' +
                                '</table>'
                            $('#img_prv').html(createHtml);
                        });
                    }
                }
            }
        });


        // Add scope for selected project
        $('body').on('change', '.project', function() {
            var id = $('.project').val();
            var url = "{{url('project/scopes/search')}}/" + id;

            $.ajax({
                url: url,
                method: "GET",
            }).done(function(data) {
                var res = '';
                for (var i = 0; i < data.scopes.length; i++) {
                    res +=
                        '<tr>' +
                        '<td><input type="radio" id="scopeId" name="scope" value="' + data.scopes[i]['id'] + '"></td>' +
                        '<td>' + data.scopes[i]['scope']['name'] + '</td>' +
                        '<td>' + data.scopes[i]['terget_url'] + '</td>' +
                        '<td>' + data.scopes[i]['comment'] + '</td>' +
                        '</tr>';
                };

                $('tbody').html(res);
            });

        });
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
@endpush