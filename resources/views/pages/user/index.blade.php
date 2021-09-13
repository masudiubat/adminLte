@extends('layouts.layout')

@section('title', 'Users')

@push('css')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
@endpush
@section('breadcrumb')
<li class="breadcrumb-item active">Users</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Users</h3>
                <div class="add-user-btn float-right" style="width:20%">
                    <button type="button" class="btn btn-block btn-outline-success" data-toggle="modal" data-target="#NewUserModal">Add New User</button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>
                                @if($user->image != null)
                                <img class="img-fluid" src="{{ asset('images/users/'. $user->image)}}" alt="Photo" height="80px" width="70px">
                                @else
                                <img class="img-fluid" src="{{ asset('images/users/demo_profile_image.jpg')}}" alt="Photo" height="80px" width="70px">
                                @endif
                            </td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->country_code }}{{ $user->phone }}</td>
                            <td>
                                @if(!is_null($user->roles))
                                @foreach($user->roles as $userRole)
                                {{ ucfirst($userRole->name) }}
                                @endforeach
                                @endif
                            </td>
                            <td>
                                <a onclick="event.preventDefault(); editUser('{{ $user->id }}');" href="#" class="btn btn-xs btn-secondary" data-toggle="tooltip" data-placement="top" title="Edit User Info"><i class="fa fa-edit"></i></a>
                                <a onclick="event.preventDefault(); changeUserPassword('{{ $user->id }}');" href="#" class="btn btn-xs btn-info" data-toggle="tooltip" data-placement="top" title="Change Password"><i class="fa fa-key"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

        <!-- Start Modal for create new user -->
        <div class="modal fade" id="NewUserModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Create New User</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card card-primary">
                            <!-- form start -->
                            <form action="{{route('user.store')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name">Name <code>*</code></label>
                                                <input type="text" name="name" class="form-control" id="name" placeholder="Enter Name" required>
                                                <span class="text-danger">{{ $errors->first('name') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="email">Email address <code>*</code></label>
                                                <input type="email" name="email" class="form-control" id="email" placeholder="Enter email" required>
                                                <span class="text-danger">{{ $errors->first('email') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="mobile">Select Country Code <code>*</code></label>
                                                <select class="form-control select2bs4" name="country_code">
                                                    <option value="">Select Country Code</option>
                                                    @if(!is_null($codes))
                                                    @foreach($codes as $code)
                                                    <option value="{{$code->id}}">{{$code->country}} ({{$code->code}})</option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="mobile">Phone Number <code>*</code></label>
                                                <input type="text" name="phone" class="form-control" id="phone" placeholder="1XX" required>
                                                <span class="text-danger">{{ $errors->first('phone') }}</span>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="address">Address</label>
                                                <input type="text" name="address" class="form-control" id="address" placeholder="Enter Address">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="address">Role</label>
                                                <select class="form-control" id="role" name="role">
                                                    <option value="">Select Role</option>
                                                    @if(!is_null($roles))
                                                    @foreach($roles as $role)
                                                    <option value="{{$role->id}}">{{$role->name}}</option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                            </div>
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
                        <!-- /.card -->
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
        <!-- End Modal for create new user -->

        <!-- Start Modal for update user -->
        <div class="modal fade" id="EditUserModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit User Info</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card card-primary AddEditForm">
                            <!-- form details from edit page -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
        <!-- End Modal for update user -->


        <!-- Start Modal for change user password -->
        <div class="modal fade" id="ChangePasswordModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Change User Password</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card card-primary">
                            <!-- form start -->
                            <form id="changeUserPasswordForm" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="password">Password <code>*</code></label>
                                        <input type="password" name="password" class="form-control" id="password" placeholder="Enter password" required>
                                        <span class="text-danger">{{ $errors->first('password') }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label for="password-confirm">Confirm Password <code>*</code></label>
                                        <input type="password" name="password_confirmation" class="form-control" id="password-confirm" placeholder="Re-enter Password" required>
                                        <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <div class="float-right text-right"><button type="submit" class="btn btn-primary">Change Password</button></div>
                                </div>
                            </form>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
        <!-- End Modal for change user password -->
    </div>
    <!-- /.col -->
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
<!-- Select2 -->
<script src="{{ asset('assets/plugins/select2/js/select2.full.min.js')}}"></script>
<script>
    $(function() {
        //Initialize Select2 Elements
        $('.select2').select2()

        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })

        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            columnDefs: [{
                orderable: false,
                targets: [-1]
            }]

            /*"buttons": ["csv", "excel", "pdf", "print"]*/
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
    });
</script>

<script>
    var loadFile = function(event) {
        var output = document.getElementById('output');
        output.src = URL.createObjectURL(event.target.files[0]);
        $('#output').height(150);
    };
</script>

<script>
    function editUser(id) {
        var url = "{{url('/user/edit')}}/" + id;
        $.ajax({
            url: url,
            method: "GET",
        }).done(function(data) {
            $('.AddEditForm').html(data.editUser);
            $('#EditUserModal').modal('show');
        });
    }

    function changeUserPassword(id) {
        var url = "{{url('/user/password/change')}}/" + id;
        $('#changeUserPasswordForm').attr('action', url);
        $('#ChangePasswordModal').modal('show');
    }
</script>
@endpush