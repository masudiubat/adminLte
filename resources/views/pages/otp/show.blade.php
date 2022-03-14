@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <div class="card-header">{{ __('Verify Your Account by OTP') }}</div>

                <div class="card-body">
                    @if(Session::has('verifiedAccount'))
                    <div class="alert alert-success">{{ Session::get('verifiedAccount') }}</div>
                    @endif
                    @if($errors->any())
                    <div class="alert alert-danger">
                        <strong>{{$errors->first()}}</strong>
                    </div>
                    @endif
                    @if(Cache::has('otp'))
                    <form class="d-inline" method="POST" action="{{ route('otp.verification.send') }}">
                        @csrf
                        <div class="input-group mb-3">
                            <input id="otp" type="text" class="form-control @error('otp') is-invalid @enderror" name="otp" value="{{ old('otp') }}" required autocomplete="otp" autofocus>
                            @error('otp')
                            <span class="error invalid-feedback">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            @if($errors->any())

                            @endif
                        </div>
                        <div class="row">
                            <div class="offset-md-4 col-md-3">
                                <button type="submit" class="btn btn-primary btn-block">Verify Account</button>
                            </div>
                            <!-- /.col -->
                        </div>
                    </form>
                    @else
                    Your otp time has expired <a href="{{route('otp.code.resend')}}" class="">Resend OTP</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection