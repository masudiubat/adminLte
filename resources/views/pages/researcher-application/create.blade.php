@extends('layouts.login-layout')

@push('css')
<!-- summernote -->
<link rel="stylesheet" href="{{ asset('assets/plugins/summernote/summernote-bs4.min.css')}}">
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<!-- daterange picker -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">
<style>
    .login-box,
    .register-box {
        width: 80%;
    }

    .image-input .form-control {
        height: 45px;
    }
</style>
@endpush
@section('content')
<div class="login-box">
    <!-- /.login-logo -->
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <a href="#" class="h1"><img src="{{asset('images/logos/beetles_logo_2.png')}}" alt="Beetles"></a>
        </div>
        <div class="card-body">
            <p class="login-box-msg">Sign in to start your session</p>
            @if($errors->any())
            {{ implode('', $errors->all('<div>:message</div>')) }}
            @endif
            <form action="{{ route('researcher.application.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-6">
                        <div class="input-group mb-3">
                            <input type="name" name="name" value="{{ old('name') }}" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Name" required autocomplete="name" autofocus>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"><span class="text-danger"> * </span></span>
                                </div>
                            </div>
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="input-group mb-3">
                            <input type="text" id="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Email" required autocomplete="email">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"><span class="text-danger"> * </span></span>
                                </div>
                            </div>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="input-group mb-3">
                            <select id="country_code" name="country_code" class="form-control select2bs4 @error('email') is-invalid @enderror" required autocomplete="email" autofocus>
                                <option value="">Select your country code</option>
                                @if(!is_null($codes))
                                @foreach($codes as $key => $code)
                                <option value="{{$code->id}}" {{ old('country_code') == $code->id ? 'selected' : '' }}>{{$code->country}}({{$code->code}})</option>
                                @endforeach
                                @endif
                            </select>

                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-globe"><span class="text-danger"> * </span></span>
                                </div>
                            </div>
                            @error('country_code')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="input-group mb-3">
                            <input type="text" id="phone" value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror" name="phone" placeholder="Mobile number except country code, e.g: 1XXX" required autocomplete="phone">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-mobile-alt"><span class="text-danger"> * </span></span>
                                </div>
                            </div>
                            @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="input-group mb-3">
                            <input type="text" name="dob" value="{{ old('dob') }}" class="form-control datetimepicker-input date @error('dob') is-invalid @enderror" data-target="#reservationdate" placeholder="Date of birth" />
                            <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"><span class="text-danger"> * </span></i></div>
                            </div>
                            @error('dob')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="input-group mb-3">
                            <input type="text" id="father_name" value="{{ old('father_name') }}" class="form-control @error('father_name') is-invalid @enderror" name="father_name" placeholder="Father Name" required autocomplete="father_name">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"><span class="text-danger"> * </span></span>
                                </div>
                            </div>
                            @error('father_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="input-group mb-3">
                            <input type="text" id="mother_name" value="{{ old('mother_name') }}" class="form-control @error('mother_name') is-invalid @enderror" name="mother_name" placeholder="Mother Name" required autocomplete="mother_name">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"><span class="text-danger"> * </span></span>
                                </div>
                            </div>
                            @error('mother_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="input-group mb-3">
                            <input type="text" id="permanent_address" value="{{ old('permanent_address') }}" class="form-control @error('permanent_address') is-invalid @enderror" name="permanent_address" placeholder="Permanent Address" required autocomplete="permanent_address">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-map-marker-alt"><span class="text-danger"> * </span></span>
                                </div>
                            </div>
                            @error('permanent_address')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="input-group mb-3">
                            <input type="text" id="present_address" value="{{ old('present_address') }}" class="form-control @error('present_address') is-invalid @enderror" name="present_address" placeholder="Present Address" required autocomplete="present_address">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-map-marker-alt"><span class="text-danger"> * </span></span>
                                </div>
                            </div>
                            @error('present_address')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="input-group mb-3">
                            <textarea class="form-control" name="about" placeholder="About"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="input-group image-input mb-3">
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('profile_image') is-invalid @enderror" id="profile_image" name="profile_image" required accept="image/*" onchange="loadImage(event)">
                                    <label class="custom-file-label" for="profile_image">Choose file</label>
                                </div>
                                <div class="input-group-append">
                                    <span class="input-group-text">Profile Image <span class="text-danger"> * </span></span>
                                </div>
                            </div>
                            <img id="imageOutput" style="margin-top: 10px;" />
                            @error('profile_image')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="input-group image-input mb-3">
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('photo_identity') is-invalid @enderror" id="photo_identity" name="photo_identity" required accept="image/*" onchange="loadPhotoIdentity(event)">
                                    <label class="custom-file-label" for="photo_identity">Choose file</label>
                                </div>
                                <div class="input-group-append">
                                    <span class="input-group-text">Photo Identity <span class="text-danger"> * </span></span>
                                </div>
                            </div>
                            <img id="photoOutput" style="margin-top: 10px;" />
                            @error('profile_image')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="cretificationGroup">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control certification @error('certification') is-invalid @enderror" name="certification[]" placeholder="Certification" required autocomplete="certification">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-certificate"></span>
                                    </div>
                                </div>
                                @error('certification')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row add-more-media">
                            <div class="col-lg-12">
                                <a href="javascript:void(0)" id="add_more_btn" type="button" class="btn btn-success btn-xs float-right addMore">
                                    More Certification
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="input-group image-input mb-3">
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="attachment" name="attachment" required accept="image/*" onchange="loadAttachment(event)">
                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                </div>
                                <div class="input-group-append">
                                    <span class="input-group-text">Attachment</span>
                                </div>
                            </div>
                            <!-- <img id="attachmentOutput" style="margin-top: 10px;" /> -->
                            @error('attachment')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="input-group mb-3">
                            <select id="skill" name="skill[]" multiple="multiple" class="form-control skill select2 @error('email') is-invalid @enderror" required autocomplete="skill" autofocus>
                                @if(!is_null($skills))
                                @foreach($skills as $skill)
                                <option value="{{$skill->id}}">{{$skill->name}}</option>
                                @endforeach
                                @endif
                            </select>

                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-globe"><span class="text-danger"> * </span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="input-group mb-3">
                            <input type="text" name="preferred_interview_date" class="form-control datetimepicker-input date" data-target="#reservationdate" placeholder="Preferred interview date" />
                            <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                            @error('preferred_interview_date')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-10">

                    </div>
                    <!-- /.col -->
                    <div class="col-2">
                        <button type="submit" class="btn btn-primary btn-block">Apply</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
            <div class="cretificationGroupCopy" style="display: none;">
                <div class="input-group mb-3">
                    <input type="text" class="form-control certification @error('certification') is-invalid @enderror" name="certification[]" placeholder="Certification" required autocomplete="certification">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-certificate"></span>
                            <a href="javascript:void(0)" class="btn btn-danger btn-xs remove" data-toggle="tooltip" data-placement="top" title="Remove" style="margin-left: 5px;"><i class="fa fa-times" aria-hidden="true"></i></a>
                        </div>
                    </div>
                    @error('certification')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <p class="mb-1">
                <a href="{{ route('login') }}">I'm already registered</a>
            </p>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>
<!-- /.login-box -->
@endsection
@push('js')
<!-- Summernote -->
<script src="{{ asset('assets/plugins/summernote/summernote-bs4.min.js')}}"></script>
<!-- Select2 -->
<script src="{{ asset('assets/plugins/select2/js/select2.full.min.js')}}"></script>
<!-- date-range-picker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
<script>
    $(function() {
        //Initialize Select2 Elements
        $('.select2').select2()

        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })
        //Date picker
        $('.date').datepicker({
            format: 'yyyy-mm-dd'
        });

        // Summernote
        $('.summernote').summernote()
    });
</script>
<script>
    var loadImage = function(event) {
        var output = document.getElementById('imageOutput');
        output.src = URL.createObjectURL(event.target.files[0]);
        $('#imageOutput').height(150);
        $('#imageOutput').width(140);
    };

    var loadPhotoIdentity = function(event) {
        var output = document.getElementById('photoOutput');
        output.src = URL.createObjectURL(event.target.files[0]);
        $('#photoOutput').height(150);
        $('#photoOutput').width(140);
    };

    var loadAttachment = function(event) {
        var output = document.getElementById('attachmentOutput');
        output.src = URL.createObjectURL(event.target.files[0]);
        $('#attachmentOutput').height(150);
        $('#attachmentOutput').width(140);
    };
</script>
<script>
    $(document).ready(function() {
        //add more certification field
        $('body').on('click', '.addMore', function() {
            var extendedCertification = '<div class="cretificationGroup"> ' + $(".cretificationGroupCopy ").html() + ' </div>';
            $('body').find('.cretificationGroup:last').after(extendedCertification);
        });
        //remove Social media fields group
        $("body").on("click", ".remove", function() {
            $(this).parents(".cretificationGroup").remove();
        });

        $('#skill').select2({
            placeholder: 'Select your skills'
        }).val('').trigger('change');
    });
</script>
@endpush