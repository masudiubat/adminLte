<!-- form start -->
<form action="{{route('organization.user.update', $user->id)}}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="organization_id" value="{{$userOrgId->organization_id}}">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="organization_name">Organization <code>*</code></label>
                    <input type="text" name="organization_name" class="form-control {{ $errors->has('organization_id') ? 'has-error' : '' }}" value="{{$userOrgId->organization->name}}" required readonly>
                    <span class="text-danger">{{ $errors->first('organization_id') }}</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="projectUpdate">Select Project</label>
                    <select class="form-control select2bs4" multiple="multiple" id="projectUpdate" name="project[]" data-placeholder="Select Projects" style="width: 100%; color:black !important;">
                        @if($projects->count())
                        @foreach($projects as $project)
                        <option value="{{$project->id}}" {{ in_array($project->id, $exProjects) ? 'selected' : ''}}>{{$project->title}}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Member Name <code>*</code></label>
                    <input type="text" name="name" class="form-control" id="name" value="{{$user->name}}" required>
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="email">Member Email <code>*</code></label>
                    <input type="email" name="email" class="form-control" id="email" value="{{$user->email}}" required>
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="mobile">Select Country Code <code>*</code></label>
                    <select class="form-control select2bs4" name="country_code">
                        @if(!is_null($codes))
                        @foreach($codes as $code)
                        <option value="{{$code->id}}" {{$user->country_code == $code->code ? 'selected' : ''}}>{{$code->country}} ({{$code->code}})</option>
                        @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="mobile">Phone Number <code>*</code></label>
                    <input type="text" name="phone" class="form-control" id="phone" value="{{$user->phone}}" required>
                    <span class="text-danger">{{ $errors->first('phone') }}</span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="designation">Official Designation <code>*</code></label>
                    <input type="text" name="designation" class="form-control" id="designation" value="{{$user->organization_member->designation }}" required>
                    <span class="text-danger">{{ $errors->first('designation') }}</span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <label>Access Control List <code>*</code></label>
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox" name="all_credential" id="select-all-update" value="all credentials">
                        <label for="select-all-update" class="custom-control-label">Select All</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox" name="credentials[]" id="project-update" value="client project update" {{ in_array('client project update', $exPermisions) ? 'checked' : ''}}>
                        <label for="project-update" class="custom-control-label">Project Update</label>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <label> &nbsp; </label>
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox" name="credentials[]" id="project-show" value="client project read" {{ in_array('client project read', $exPermisions) ? 'checked' : ''}}>
                        <label for="project-show" class="custom-control-label">Project Show</label>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <label> &nbsp; </label>
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox" name="credentials[]" id="project-create" value="client project write" {{ in_array('client project write', $exPermisions) ? 'checked' : ''}}>
                        <label for="project-create" class="custom-control-label">Project Create</label>
                    </div>
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
<!-- Select2 -->
<script src="{{ asset('assets/plugins/select2/js/select2.full.min.js')}}"></script>
<script>
    $(function() {
        //Initialize Select2 Elements
        $('#projectUpdate').select2({
            placeholder: 'Select Projects'
        });

        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })
    });
</script>
<script>
    $('#select-all-update').click(function() {
        var checked = this.checked;
        $('input[type="checkbox"]').each(function() {
            this.checked = checked;
        });
    })
</script>