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
                    <label for="address">Country Code</label>
                    <select class="form-control" name="country_code">
                        <option value="">Select Country Code</option>
                        @if(!is_null($codes))
                        @foreach($codes as $code)
                        <option value="{{$code->code}}" {{ $user->country_code && $user->country_code == $code->code ? 'selected' : ''}}>{{$code->country}} ({{$code->code}})</option>
                        @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="phone">Phone Number <code>*</code></label>
                    <input type="text" name="phone" value="{{ $user->phone }}" class="form-control" id="phone" placeholder="Enter Phone Number" required>
                    <span class="text-danger">{{ $errors->first('phone') }}</span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" name="address" value="{{ $user->address }}" class="form-control" id="address" placeholder="Enter Address">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="address">Role</label>
                    <select class="form-control" id="role" name="role">
                        <option value="">Select Role</option>
                        @if(!is_null($roles))
                        @foreach($roles as $role)
                        @if(in_array($role->id, $roleArr))
                        <option selected value="{{$role->id}}">{{$role->name}}</option>
                        @else
                        <option value="{{$role->id}}">{{$role->name}}</option>
                        @endif
                        @endforeach
                        @endif
                    </select>
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