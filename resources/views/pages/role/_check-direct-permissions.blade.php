<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="name">Role</label>
            @if(!is_null($userRoles))
            @foreach($userRoles as $role)
            <input type="text" value="{{$role}}" class="form-control" readonly>
            @endforeach
            @endif
        </div>
    </div>
</div>

<div class="row AddPermissions">
    <div class="col-md-12"><label for="name">Select Permissions <code>*</code></label></div>
    @if(!is_null($permissions))
    @foreach($permissions as $permission)
    <div class="col-md-3">
        <div class="form-group">
            <div class="form-check">
                @if(!is_null($userDirectPermissions))
                @foreach($userDirectPermissions as $udp)
                @if($permission->id == $udp->pivot->permission_id)
                <input class="form-check-input" name="permissions[]" value="{{ $permission->id }}" type="checkbox" checked>
                <label class="form-check-label">{{$permission->name}}</label>
                @else
                <input class="form-check-input" name="permissions[]" value="{{ $permission->id }}" type="checkbox">
                <label class="form-check-label">{{$permission->name}}</label>
                @endif
                @endforeach
                @else
                <input class="form-check-input" name="permissions[]" value="{{ $permission->id }}" type="checkbox">
                <label class="form-check-label">{{$permission->name}}</label>
                @endif

            </div>
        </div>
    </div>
    @endforeach
    @endif
</div>