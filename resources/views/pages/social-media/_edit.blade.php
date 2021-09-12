<!-- form start -->
<form action="{{route('social.media.update', $media->id)}}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="card-body">
        <div class="form-group">
            <label for="name">Name <code>*</code></label>
            <input type="text" name="name" value="{{$media->name}}" class="form-control" id="name" placeholder="Enter Name" required>
            <span class="text-danger">{{ $errors->first('name') }}</span>
        </div>
        <div class="form-group">
            <label for="icon">Icon Class <code>*</code></label>
            <input type="text" name="icon" value="{{$media->icon}}" class="form-control" id="icon" placeholder="Enter Icon Class" required>
            <span class="text-danger">{{ $errors->first('icon') }}</span>
        </div>
    </div>
    <!-- /.card-body -->
    <div class="card-footer modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <div class="float-right text-right"><button type="submit" class="btn btn-primary">Update</button></div>
    </div>
</form>