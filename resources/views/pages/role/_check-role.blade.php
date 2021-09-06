<div class="col-md-12"><label for="name">Select Permissions <code>*</code></label></div>
<div class="col-md-12">
    <div class="form-group">
        <div class="form-check">
            <input class="form-check-input" id="selectAll" name="" value="" type="checkbox">
            <label class="form-check-label">Select All</label>
        </div>
    </div>
</div>
@if(!is_null($permissions))
@foreach($permissions as $permission)
<div class="col-md-3">
    <div class="form-group">
        <div class="form-check">
            @if(in_array($permission->id, $permissionList))
            <input class="form-check-input" name="permissions[]" value="{{ $permission->id }}" type="checkbox" checked>
            <label class="form-check-label">{{$permission->name}}</label>
            @else
            <input class="form-check-input" name="permissions[]" value="{{ $permission->id }}" type="checkbox">
            <label class="form-check-label">{{$permission->name}}</label>
            @endif
        </div>
    </div>
</div>
@endforeach
@endif

<script>
    $('body').on('click', '#selectAll', function() {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });
</script>