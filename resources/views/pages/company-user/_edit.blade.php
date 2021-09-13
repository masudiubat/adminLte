<!-- form start -->
<form id="newAssignForm" action="{{route('assign.company.user.store')}}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="card-body">
        <div class="form-group">
            <label for="name">Select Company <code>*</code></label>
            <select name="company" class="form-control">
                <option value="">Select Company</option>
                @if(!is_null($companies))
                @foreach($companies as $company)
                <option value="{{$company->id}}" {{ $companyUser->user_company_id == $company->id ? "selected" : "" }}>{{$company->name}}</option>
                @endforeach
                @endif
            </select>
            <span class="text-danger">{{ $errors->first('company') }}</span>
        </div>
        <div class="form-group">
            <label for="name">Select User <code>*</code></label>
            <select name="user" class="form-control">
                <option value="">Select User</option>
                @if(!is_null($users))
                @foreach($users as $user)
                <option value="{{$user->id}}" {{ $companyUser->user_id == $user->id ? "selected" : "" }}>{{$user->name}}</option>
                @endforeach
                @endif
            </select>
            <span class="text-danger">{{ $errors->first('user') }}</span>
        </div>
        <div class="form-group">
            <label for="name">Official Designation</label>
            <input type="text" name="designation" class="form-control" value="{{ $companyUser->designation }}" id="designation" placeholder="Enter Official Designation">
            <span class="text-danger">{{ $errors->first('designation') }}</span>
        </div>
        <div class="form-group">
            <div class="custom-control custom-checkbox">
                <input class="custom-control-input" type="checkbox" id="customCheckbox2" name="leading_person" value="1" {{ $companyUser->is_leading_person == 1 ? "checked" : ""}}>
                <label for="customCheckbox2" class="custom-control-label">Is Leading Person</label>
            </div>
            <span class="text-danger">{{ $errors->first('leading_person') }}</span>
        </div>
    </div>
    <!-- /.card-body -->
    <div class="card-footer modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <div class="float-right text-right"><button type="submit" class="btn btn-primary">Save</button></div>
    </div>
</form>