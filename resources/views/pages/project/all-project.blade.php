@extends('layouts.layout')

@section('title', 'All Project')

@push('css')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
<style>
    .table td,
    .table th {
        border: 0;
    }
</style>
@endpush
@section('breadcrumb')
<li class="breadcrumb-item active">All Project</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All Project</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body" style="padding: 1.25rem 0.25rem;">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Project Title</th>
                            <th>Organization Name</th>
                            <th>Moderator</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($projects->reverse() as $project)
                        <tr>
                            <td><a href="{{route('admin.project.show', $project->id)}}" data-toggle="tooltip" data-placement="top" title="Project Details">{{ $project->title }}</a></td>
                            <td>@if(!is_null($project->organization)){{ $project->organization->name }}@endif</td>
                            <td>@if(!is_null($project->moderator)){{ $project->moderator->name }}@endif</td>
                            <td>{{ date('M d, Y', strtotime($project->start_date))}}</td>
                            <td>{{ date('M d, Y', strtotime($project->end_date))}}</td>
                            <td>
                                <a onclick="event.preventDefault(); editProject('{{ $project->id }}');" href="#" class="btn btn-xs btn-secondary" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fas fa-edit"></i></a>
                                <a href="#" onclick="event.preventDefault(); deleteProject('{{$project->id}}');" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fas fa-trash-alt"></i></a>

                                <form id="delete-project-{{ $project->id }}" action="{{route('admin.project.destroy', $project->id)}}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Project Title</th>
                            <th>Organization Name</th>
                            <th>Moderator</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                </table>
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

<script>
    $(function() {
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
    function editProject(id) {
        var url = "{{url('/assign/organization/member/edit')}}/" + id;
        $.ajax({
            url: url,
            method: "GET",
        }).done(function(data) {
            $('.AddEditForm').html(data.editOrganizationMember);
            $('#editOrganizationMemberModal').modal('show');
        });
    }

    // Function for delete Customer...
    function deleteOrganizationMember(id) {
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
                document.getElementById('delete-organization-member-' + id).submit();
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