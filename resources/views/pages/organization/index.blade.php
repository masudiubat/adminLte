@extends('layouts.layout')

@section('title', 'Organization')

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
<li class="breadcrumb-item active">Organization</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Organization</h3>
                <div class="add-user-btn float-right" style="width:20%">
                    <button type="button" class="btn btn-block btn-outline-success" data-toggle="modal" data-target="#NewCompanyModal">New Organization</button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Address</th>
                            <th>Logo</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($organizations->reverse() as $key => $organization)
                        <tr>
                            <td><a href="{{route('organization.show', $organization->id)}}" data-toggle="tooltip" data-placement="top" title="Edit">{{ $organization->name }}</a></td>
                            <td>{{ $organization->code_name }}</td>
                            <td>{{ $organization->address }}</td>
                            <td>
                                @if(!is_null($organization->logo))
                                <img class="img-fluid" src="{{ asset('images/organization/logos/'. $organization->logo)}}" alt="Photo" height="120px" width="90px">
                                @endif
                            </td>
                            <td>
                                <a href="{{route('organization.show', $organization->id)}}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="top" title="Details"><i class="fa fa-eye"></i></a>
                                <a onclick="event.preventDefault(); editCompany('{{ $organization->id }}');" href="#" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                                <a href="#" onclick="event.preventDefault(); deleteCompany('{{$organization->id}}');" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>

                                <form id="delete-organization-{{ $organization->id }}" action="{{route('organization.destroy', $organization->id)}}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Address</th>
                            <th>Logo</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        <!-- Start Modal for create new skill -->
        <div class="modal fade" id="NewCompanyModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">New Organization</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card card-primary">
                            <!-- form start -->
                            <form id="newSkillForm" action="{{route('organization.store')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="role" value="4">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name">Organization Name <code>*</code></label>
                                                <input type="text" name="name" class="form-control" id="name" placeholder="Organization Name" required>
                                                <span class="text-danger">{{ $errors->first('name') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="code_name">Code Name</label>
                                                <input type="text" name="code_name" class="form-control" id="code_name" placeholder="Enter Code Name">
                                                <span class="text-danger">{{ $errors->first('code_name') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="address">Organization Address </label>
                                                <input type="text" name="address" class="form-control" id="address" placeholder="Organization Address">
                                                <span class="text-danger">{{ $errors->first('address') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="email">Lead Communicator Name</label>
                                                <input type="text" name="communicator" class="form-control" id="communicator" placeholder="Communicator Name">
                                                <span class="text-danger">{{ $errors->first('communicator') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="country_code">Communicator Country Code</label>
                                                <select class="select2bs4" name="country_code" id="country_code" data-placeholder="Select Country code" style="width: 100%;">
                                                    @if(!is_null($phoneCodes))
                                                    @foreach($phoneCodes as $code)
                                                    <option value="{{ $code->id }}">{{$code->country}} ({{$code->code}})</option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                                <span class="text-danger">{{ $errors->first('country_code') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="phone">Communicator Phone</label>
                                                <input type="text" name="phone" class="form-control" id="phone2" placeholder="1XXXX">
                                                <span class="text-danger">{{ $errors->first('phone') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="email">Communicator Email</label>
                                                <input type="text" name="email" class="form-control" id="email" placeholder="Communicator Email">
                                                <span class="text-danger">{{ $errors->first('email') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="email">Communicator Designation</label>
                                                <input type="text" name="designation" class="form-control" id="designation" placeholder="Official Designation">
                                                <span class="text-danger">{{ $errors->first('designation') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="custom-file-input">Organization Logo</label>
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input type="file" name="image" class="custom-file-input" id="image" accept="image/*" onchange="loadFile(event)">
                                                        <label class="custom-file-label" for="image">Choose file</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label> &nbsp; </label>
                                                <img id="output" style="margin-top: 10px" />
                                            </div>
                                        </div>
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
        <!-- End Modal for create new skill -->
        <!-- Start Modal for update skill -->
        <div class="modal fade" id="editCompanyModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Update Organization</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card card-primary AddEditForm">

                        </div>
                        <!-- /.card -->
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
        <!-- End Modal for update skill -->
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
            "ordering": false,
            /*"buttons": ["csv", "excel", "pdf", "print"] */
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
    function editCompany(id) {
        var url = "{{url('/organization/edit')}}/" + id;
        $.ajax({
            url: url,
            method: "GET",
        }).done(function(data) {
            $('.AddEditForm').html(data.editCompany);
            $('#editCompanyModal').modal('show');
        });
    }

    // Function for delete Customer...
    function deleteCompany(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-organization-' + id).submit();
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