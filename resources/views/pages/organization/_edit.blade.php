<!-- form start -->
<form id="newSkillForm" action="{{route('organization.update', $organization->id)}}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="role" value="4">
    <div class="card-body" style="padding:.5rem">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Organization Name <code>*</code></label>
                    <input type="text" name="name" class="form-control" id="name" value="{{ $organization->name }}" required>
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="code_name">Code Name</label>
                    <input type="text" name="code_name" class="form-control" id="code_name" value="{{ $organization->code_name }}">
                    <span class=" text-danger">{{ $errors->first('code_name') }}</span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="address">Organization Address </label>
                    <input type="text" name="address" class="form-control" id="address" value="{{$organization->address}}">
                    <span class="text-danger">{{ $errors->first('address') }}</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="custom-file-input">Organization Logo</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" name="image" class="custom-file-input" id="image" accept="image/*" onchange="loadFile(event)">
                            <label class="custom-file-label" for="image">Choose file</label>
                        </div>
                    </div>
                    <img id="outputForUpdate" src="{{asset('images/organization/logos/'. $organization->logo)}}" height="150px" width="140px" style="margin-top: 10px" />
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
<script>
    var loadFile = function(event) {
        var outputForUpdate = document.getElementById('outputForUpdate');
        outputForUpdate.src = URL.createObjectURL(event.target.files[0]);
        $('#outputForUpdate').height(150);
        $('#outputForUpdate').width(140);
    };
</script>