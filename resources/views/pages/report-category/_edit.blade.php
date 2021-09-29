<!-- form start -->
<form id="editCategoryForm" action="{{route('report.category.update', $category->id)}}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="card-body">
        <div class="form-group">
            <label for="name">Category Name <code>*</code></label>
            <input type="text" name="name" class="form-control" id="name" value="{{ $category->name }}" required>
            <span class="text-danger">{{ $errors->first('name') }}</span>
        </div>
    </div>
    <!-- /.card-body -->
    <div class="card-footer modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <div class="float-right text-right"><button type="submit" class="btn btn-primary">Update</button></div>
    </div>
</form>