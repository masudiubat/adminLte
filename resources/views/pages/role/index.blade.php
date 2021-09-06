@extends('layouts.layout')

@section('title', 'Roles & Permissions')

@push('css')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endpush
@section('breadcrumb')
<li class="breadcrumb-item active">Roles and Permissions</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6" id="accordion">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Roles Table</h3>
                <div class="add-user-btn float-right" style="width:30%">
                    <button type="button" class="btn btn-block btn-outline-success" data-toggle="modal" data-target="#NewRoleModal">Add New Role</button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!is_null($roles))
                        @foreach($roles as $key => $role)
                        <tr>
                            <td>
                                <div class="card card-primary card-outline" style="margin-bottom:0px;">
                                    <a class="d-block w-100" data-toggle="collapse" href="#roleNameCollapse_{{$key+1}}">
                                        <div class="card-header">
                                            <h4 class="card-title">
                                                {{ $roles->firstItem() + $key }}. {{ ucwords($role->name) }}
                                            </h4>
                                            <div class="float-right">
                                                <a onclick="event.preventDefault(); editRole('{{$role->id}}');" href="#" class="btn btn-xs btn-secondary" data-toggle="tooltip" data-placement="top" title="Edit Role"><i class="fa fa-edit"></i></a>
                                            </div>
                                        </div>
                                    </a>
                                    <div id="roleNameCollapse_{{$key+1}}" class="collapse" data-parent="#accordion">
                                        <div class="card-body" style="padding:5px;">
                                            @if(!is_null($role->permissions))
                                            <ul class="list-group">
                                                @foreach($role->permissions as $permission)
                                                <li class="list-group-item" style="padding: 5px 10px;">{{ $permission->name }}</li>
                                                @endforeach
                                            </ul>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
            <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-right">
                    {{ $roles->links() }}
                </ul>
            </div>
        </div>
        <!-- /.card -->
        <!-- Start Modal for create new role -->
        <div class="modal fade" id="NewRoleModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Create new role</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card card-primary">
                            <!-- form start -->
                            <form id="RoreForm" action="{{route('role.store')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="password">Role Name <code>*</code></label>
                                        <input type="text" name="name" class="form-control" id="name" placeholder="Enter Name" required>
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <div class="float-right text-right"><button type="submit" class="btn btn-primary">Save</button></div>
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
        <!-- End Modal for create new role -->

        <!-- Start Modal for create new role -->
        <div class="modal fade" id="editRoleModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Create new role</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card card-primary">
                            <!-- form start -->
                            <form id="RoreForm" action="{{route('role.store')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="password">Role Name <code>*</code></label>
                                        <input type="text" name="name" class="form-control" id="name" placeholder="Enter Name" required>
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <div class="float-right text-right"><button type="submit" class="btn btn-primary">Save</button></div>
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

        <!-- End Modal for create new role -->
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="card-title">Permissions Table</h3>
                    </div>
                    <div class="col-md-6">
                        <div class="add-user-btn">
                            <button type="button" class="btn btn-block btn-outline-success" data-toggle="modal" data-target="#PermissionsToRoleModal">Permissions to Role</button>
                        </div>
                    </div>
                </div>


            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Permissions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!is_null($permissions))
                        @foreach($permissions as $key => $permission)
                        <tr>
                            <td style="padding: 5px 10px;">{{$key+1}}. &nbsp; {{$permission->name}}</td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
            <div class="card-footer clearfix">

            </div>
            <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-right">
                    {{ $permissions->links() }}
                </ul>
            </div>
        </div>
        <!-- /. end card -->
        <!-- Start Modal for permissions to role -->
        <div class="modal fade" id="PermissionsToRoleModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Assign permissions to role</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card card-primary">
                            <!-- form start -->
                            <form action="{{route('role.privilege.store')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="name">Select Role <code>*</code></label>
                                                <select class="form-control adminRole" name="role">
                                                    <option>Select Role</option>
                                                    @if(!is_null($allRoles))
                                                    @foreach($allRoles as $role)
                                                    <option value="{{$role->id}}">{{$role->name}}</option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row AddPermissions">
                                        <div class="col-md-12"><label for="name">Select Permissions <code>*</code></label></div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" id="selectAll" name="" value="" type="checkbox">
                                                    <label class="form-check-label">Select All</label>
                                                </div>
                                            </div>
                                        </div>
                                        @if(!is_null($allPermissions))
                                        @foreach($allPermissions as $permission)
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" name="permissions[]" value="{{ $permission->id }}" type="checkbox">
                                                    <label class="form-check-label">{{$permission->name}}</label>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                        @endif
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
        <!-- End Modal for permissions to role -->

        <!-- Start Modal for permissions to user -->
        <div class="modal fade" id="PermissionsToUserModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Assign permissions to User</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card card-primary">
                            <!-- form start -->
                            <form action="{{route('role.privilege.store')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="name">Select User <code>*</code></label>
                                                <select class="form-control userName" name="name">
                                                    <option>Select User</option>
                                                    @if(!is_null($users))
                                                    @foreach($users as $user)
                                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="AddDirectPermissions">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="name">Role</label>
                                                    <input type="text" class="form-control" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row AddPermissions">
                                            <div class="col-md-12"><label for="name">Select Permissions <code>*</code></label></div>
                                            @if(!is_null($allPermissions))
                                            @foreach($allPermissions as $permission)
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="permissions[]" value="{{ $permission->id }}" type="checkbox">
                                                        <label class="form-check-label">{{$permission->name}}</label>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                            @endif
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
        <!-- End Modal for permissions to user -->
    </div>
</div>
@endsection

@push('js')
<script>
    // edit role modal
    function editRole(id) {
        $('#editRoleModal').modal('show');
    }

    // select all btn activation
    $('body').on('click', '#selectAll', function() {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });

    // show role permisison detail
    $('body').on('change', '.adminRole', function() {
        var id = $('.adminRole').val();
        var url = "{{url('check/role/permissions')}}/" + id;
        $.ajax({
            url: url,
            method: "GET",
        }).done(function(data) {
            $('.AddPermissions').html(data.checkResult);
            $('#PermissionsToRoleModal').modal('show');
        });
    });

    // show user permission detail
    $('body').on('change', '.userName', function() {
        var id = $('.userName').val();
        var url = "{{url('check/user/direct/permission')}}/" + id;
        $.ajax({
            url: url,
            method: "GET",
        }).done(function(data) {
            $('.AddDirectPermissions').html(data.checkDirectPermissions);
            $('#PermissionsToUserModal').modal('show');
        });
    });
</script>
@endpush