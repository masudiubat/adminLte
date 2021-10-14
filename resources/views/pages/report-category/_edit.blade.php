<!-- form start -->
<form id="editCategoryForm" action="{{route('report.category.update', $category->id)}}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="card-body">
        <div class="form-group">
            <label for="name">Category Name <code>*</code></label>
            <input type="text" name="name" class="form-control" id="name" value="{{ $category->name }}" required>
            <span class="text-danger">{{ $errors->first('name') }}</span>
        </div>
        <div class="form-group">
            <label for="name">Description <code>*</code></label>
            <textarea name="description" class="form-control" required>{{ $category->description }}</textarea>
            <span class="text-danger">{{ $errors->first('description') }}</span>
        </div>

        <div class="form-group">
            <label for="name">CWE/CVE Reference <code>*</code></label>
            <input type="text" name="reference" class="form-control" id="reference" value="{{ $category->cwe_cve_reference }}" required>
            <span class="text-danger">{{ $errors->first('reference') }}</span>
        </div>
    </div>
    <!-- /.card-body -->
    <div class="card-footer modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <div class="float-right text-right"><button type="submit" class="btn btn-primary">Update</button></div>
    </div>
</form>