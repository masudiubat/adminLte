@extends('layouts.layout')

@section('title', 'Team Members')

@push('css')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<style>
    .badge {
        font-size: 95%;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        color: #495057;
    }
</style>
@endpush
@section('breadcrumb')
<li class="breadcrumb-item active">Team Members</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                @if($userOrgId->organization->maximum_members <= $users->count())
                    <div class="alert alert-warning">You have reached your maximum member creation limit. </div>
                    @endif
                    <h3 class="card-title">Team Members</h3>
                    <div class="add-user-btn float-right" style="width:20%">
                        <button type="button" class="btn btn-block btn-outline-success d-flex justify-content-center d-md-table mx-auto" data-toggle="modal" data-target="#NewUserModal" {{$userOrgId->organization->maximum_members <= $users->count() ? 'disabled' : ''}}>Add Team Member</button>
                    </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Designation</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>
                                @if($user->user->image != null)
                                <img class="img-fluid" src="{{ asset('images/users/'. $user->user->image)}}" alt="Photo" height="70px" width="65px">
                                @else
                                <img class="img-fluid" src="{{ asset('images/users/demo_profile_image.jpg')}}" alt="Photo" height="70px" width="65px">
                                @endif
                            </td>
                            <td>{{ $user->user->name }}</td>
                            <td>{{ $user->designation }}</td>
                            <td>{{ $user->role }}</td>

                            <td>
                                <a onclick="event.preventDefault(); showUser('{{ $user->user->id }}');" href="#" class="btn btn-xs btn-info" data-toggle="tooltip" data-placement="top" title="Details"><i class="fa fa-eye"></i></a>
                                <a onclick="event.preventDefault(); editUser('{{ $user->user->id }}');" href="#" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                                @if(Auth::user()->id != $user->user->id)
                                <a href="#" onclick="event.preventDefault(); deleteUser('{{$user->user->id}}');" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                                <form id="organization-user-delete-{{ $user->user->id }}" action="{{route('organization.user.destroy', $user->user->id)}}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Designation</th>
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
                        <h4 class="modal-title">Add Team Member</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card card-primary">
                            <!-- form start -->
                            <form action="{{route('organization.user.store')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="organization_id" value="{{$userOrgId->organization_id}}">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="organization_name">Organization <code>*</code></label>
                                                <input type="text" name="organization_name" class="form-control {{ $errors->has('organization_id') ? 'has-error' : '' }}" value="{{$userOrgId->organization->name}}" required readonly>
                                                <span class="text-danger">{{ $errors->first('organization_id') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="project">Select Project</label>
                                                <select class="form-control select2bs4" multiple="multiple" id="project" name="project[]" data-placeholder="Select Projects" style="width: 100%;">
                                                    @if($projects->count())
                                                    @foreach($projects as $project)
                                                    <option value="{{$project->id}}">{{$project->title}}</option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name">Member Name <code>*</code></label>
                                                <input type="text" name="name" class="form-control" id="name" placeholder="Enter Name" required>
                                                <span class="text-danger">{{ $errors->first('name') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="email">Member Email <code>*</code></label>
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
                                                <label for="designation">Official Designation <code>*</code></label>
                                                <input type="text" name="designation" class="form-control" id="designation" placeholder="Official Designation" required>
                                                <span class="text-danger">{{ $errors->first('designation') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Access Control List <code>*</code></label>
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" name="all_credential" id="select-all" value="all credentials">
                                                    <label for="select-all" class="custom-control-label">Select All</label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" name="credentials[]" id="credential4" value="client project update">
                                                    <label for="credential4" class="custom-control-label">Project Update</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label> &nbsp; </label>
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" name="credentials[]" id="credential2" value="client project read">
                                                    <label for="credential2" class="custom-control-label">Project Show</label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" name="credentials[]" id="credential5" value="client project delete">
                                                    <label for="credential5" class="custom-control-label">Project Delete</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label> &nbsp; </label>
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" name="credentials[]" id="credential3" value="client project write">
                                                    <label for="credential3" class="custom-control-label">Project Create</label>
                                                </div>
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


        <!-- Start Modal for show user info -->
        <div class="modal fade" id="ShowUserModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">User</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card card-primary AddUserInfo">

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
        $('#project').select2({
            placeholder: 'Select Projects'
        }).val('').trigger('change');

        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })

        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "ordering": false,
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
    $('#select-all').click(function() {
        var checked = this.checked;
        $('input[type="checkbox"]').each(function() {
            this.checked = checked;
        });
    })

    function editUser(id) {
        var url = "{{url('/organization/user/edit')}}/" + id;
        $.ajax({
            url: url,
            method: "GET",
        }).done(function(data) {
            $('.AddEditForm').html(data.editUser);
            $('#EditUserModal').modal('show');
        });
    }

    function showUser(id) {
        var url = "{{url('/organization/user/show')}}/" + id;
        $.ajax({
            url: url,
            method: "GET",
        }).done(function(data) {
            $('.AddUserInfo').html(data.UserInfo);
            $('#ShowUserModal').modal('show');
        });
    }

    // Function for delete User...
    function deleteUser(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to retrieve this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('organization-user-delete-' + id).submit();
            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            ) {
                Swal.fire(
                    'Cancelled',
                    'Your imaginary file is safe :)',
                    'error'
                );
            }
        })
    }
</script>
@endpush