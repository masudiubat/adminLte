@extends('layouts.layout')

@section('title', 'Report Categories')

@push('css')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endpush
@section('breadcrumb')
<li class="breadcrumb-item active">Report Categories</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Report Categories</h3>
                <div class="add-user-btn float-right" style="width:20%">
                    <button type="button" class="btn btn-block btn-outline-success" data-toggle="modal" data-target="#NewCategoryModal">New Category</button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Sl.</th>
                            <th>Name</th>
                            <th>CWE/CVE Reference</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $key => $category)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->cwe_cve_reference }}</td>
                            <td>
                                <a onclick="event.preventDefault(); categoryDetails('{{ $category->id }}');" href="#" class="btn btn-xs btn-secondary" data-toggle="tooltip" data-placement="top" title="Details"><i class="fa fa-eye"></i></a>
                                <a onclick="event.preventDefault(); editCategory('{{ $category->id }}');" href="#" class="btn btn-xs btn-secondary" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                                <a href="#" onclick="event.preventDefault(); deleteCategory('{{$category->id}}');" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>

                                <form id="delete-category-{{ $category->id }}" action="{{route('report.category.destroy', $category->id)}}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Sl.</th>
                            <th>Name</th>
                            <th>CWE/CVE Reference</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        <!-- Start Modal for create new category -->
        <div class="modal fade" id="NewCategoryModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">New Category</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card card-primary">
                            <!-- form start -->
                            <form id="newSkillForm" action="{{route('report.category.store')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="name">Category Name <code>*</code></label>
                                        <input type="text" name="name" class="form-control" id="name" placeholder="Enter Name" required>
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    </div>

                                    <div class="form-group">
                                        <label for="name">Description <code>*</code></label>
                                        <textarea name="description" class="form-control" required></textarea>
                                        <span class="text-danger">{{ $errors->first('description') }}</span>
                                    </div>

                                    <div class="form-group">
                                        <label for="name">CWE/CVE Reference <code>*</code></label>
                                        <input type="text" name="reference" class="form-control" id="reference" placeholder="Enter CWE/CVE Reference" required>
                                        <span class="text-danger">{{ $errors->first('reference') }}</span>
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
        <!-- End Modal for create new category -->
        <!-- Start Modal for update category -->
        <div class="modal fade" id="editCategoryModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Update Category</h4>
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
        <!-- End Modal for update category -->
        <!-- Start Modal for show category -->
        <div class="modal fade" id="detailsCategoryModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Category</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card card-primary showDetails">

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
    function editCategory(id) {
        var url = "{{url('/report/category/edit')}}/" + id;
        $.ajax({
            url: url,
            method: "GET",
        }).done(function(data) {
            $('.AddEditForm').html(data.editCategory);
            $('#editCategoryModal').modal('show');
        });
    }

    function categoryDetails(id) {
        var url = "{{url('/report/category/show')}}/" + id;
        $.ajax({
            url: url,
            method: "GET",
        }).done(function(data) {
            $('.showDetails').html(data.showCategory);
            $('#detailsCategoryModal').modal('show');
        });
    }

    // Function for delete Customer...
    function deleteSkill(id) {
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
                document.getElementById('delete-skill-' + id).submit();
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