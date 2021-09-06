<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title')</title>

<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<!-- Font Awesome Icons -->
<link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css')}}">
<!-- Theme style -->
<link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css')}}">
<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css')}}">
<link rel="stylesheet" href="{{ asset('assets/css/sweetalert2.min.css')}}">
<style>
    .modal-header,
    .modal-footer {
        padding-top: .5rem;
        padding-bottom: .5rem;
    }

    .modal-body {
        padding: 0;
    }

    .modal-body .card {
        margin-bottom: 0;
    }

    .card-body {
        padding-bottom: .20rem;
    }
</style>
@stack('css')