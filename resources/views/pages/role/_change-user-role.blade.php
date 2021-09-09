<!-- form start -->
<form id="RoreForm" action="{{route('change.user.role.store', $user->id)}}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="card-body">
        <div class="form-group">
            <label for="password">User Name <code>*</code></label>
            <input type="text" name="name" class="form-control" id="name" value="{{ $user->name }}" required readonly="readonly">
            <span class="text-danger">{{ $errors->first('name') }}</span>
        </div>

        <div class="form-group">
            <label for="password">Select Name <code>*</code></label>
            <select id="role" class="form-control" name="role" required>
                @if(!$roles->isEmpty())
                @foreach($roles as $role)
                @if(!$user->roles->isEmpty())
                @foreach($user->roles as $urole)
                @if($urole->id == $role->id)
                <option selected value="{{$role->id}}">{{$role->name}}</option>
                @else
                <option value="{{$role->id}}">{{$role->name}}</option>
                @endif
                @endforeach
                @else
                <option value="{{$role->id}}">{{$role->name}}</option>
                @endif
                @endforeach
                @endif
            </select>
            <span class="text-danger">{{ $errors->first('name') }}</span>
        </div>

    </div>
    <!-- /.card-body -->
    <div class="card-footer modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <div class="float-right text-right"><button type="submit" class="btn btn-primary">Save</button></div>
    </div>
</form>