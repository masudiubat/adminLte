<!-- form start -->
<form action="{{route('user.update', $user->id)}}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Name <code>*</code></label>
                    <input type="text" name="name" value="{{ $user->name }}" class="form-control" id="name" placeholder="Enter Name" required>
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="email">Email address <code>*</code></label>
                    <input type="email" name="email" value="{{ $user->email }}" class="form-control" id="email" placeholder="Enter email" required>
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="mobile">Mobile Number <code>*</code></label>
                    <input type="text" name="mobile" value="{{ $user->mobile }}" class="form-control" id="mobile" placeholder="Enter Mobile Number" required>
                    <span class="text-danger">{{ $errors->first('mobile') }}</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" name="address" value="{{ $user->address }}" class="form-control" id="address" placeholder="Enter Address">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="exampleInputFile">Profile Image</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" name="image" class="custom-file-input" id="image" accept="image/*" onchange="loadFile(event)">
                            <label class="custom-file-label" for="image">Choose file</label>
                        </div>
                    </div>
                    <img id="outputForUpdate" src="{{asset('images/users/'. $user->image)}}" style="margin-top: 10px" height="150px" />
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

<script>
    var loadFile = function(event) {
        var outputForUpdate = document.getElementById('outputForUpdate');
        outputForUpdate.src = URL.createObjectURL(event.target.files[0]);
        $('#outputForUpdate').height(150);
    };
</script>