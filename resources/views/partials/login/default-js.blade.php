<!-- jQuery -->
<!-- <script src="{{ asset('assets/plugins/jquery/jquery.min.js')}}"></script> -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('assets/dist/js/adminlte.min.js')}}"></script>
<script src="{{asset('assets/js/toastr.min.js')}}"></script>

{!! Toastr::message() !!}
@stack('js')