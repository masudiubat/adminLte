<!-- form start -->
<form id="newSkillForm" action="{{route('organization.update', $organization->id)}}" method="POST" enctype="multipart/form-data">
    @csrf
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
                    <label for="email">Email <code>*</code></label>
                    <input type="text" name="email" class="form-control" id="email" value="{{ $organization->email }}" required>
                    <span class=" text-danger">{{ $errors->first('email') }}</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="address">Address </label>
                    <input type="text" name="address" class="form-control" id="address" value="{{ $organization->address }}">
                    <span class=" text-danger">{{ $errors->first('address') }}</span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="country_code">Country Code</label>
                    <select class="form-control select2bs4 country_code" name="country_code" id="country_code" style="width: 100%;">
                        @if(!is_null($phoneCodes))
                        @foreach($phoneCodes as $code)
                        <option value="{{ $code->code }}" {{ $organization->country_code && $organization->country_code == $code->code ? 'selected' : ''}}>{{$code->country}} ({{$code->code}})</option>
                        @endforeach
                        @endif
                    </select>
                    <span class="text-danger">{{ $errors->first('country_code') }}</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" name="phone" class="form-control" id="phone" value="{{ $organization->phone }}" placeholder="1XXXX">
                    <span class="text-danger">{{ $errors->first('phone') }}</span>
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