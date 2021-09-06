<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('assets/dist/js/adminlte.min.js')}}"></script>
<!--end::Page Scripts -->
<script src="{{ asset('assets/js/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('assets/js/toastr.min.js')}}"></script>

{!! Toastr::message() !!}

<script>
    @if($errors -> any())
    @foreach($errors -> all() as $error)
    toastr.error('{{ $error }}', 'Error', {
        "closeButton": true,
        "progressBar": true
    });
    @endforeach
    @endif
</script>

@stack('js')