<div class="card-body table-responsive p-0">
    <table class="table table-bordered">
        <tbody>
            <tr>
                <th>Name</th>
                <td>{{$user->name}}</td>
                <th>Email</th>
                <td>{{$user->email}}</td>
            </tr>
            <tr>
                <th>Phone</th>
                <td>{{$user->country_code}}{{$user->phone}}</td>
                <th>Designation</th>
                <td>{{$user->organization_member->designation}}</td>
            </tr>
            <tr>
                <th>Role</th>
                <td>{{ $user->organization_member->role }}</td>
                <th>Project</th>
                <td>
                    @if($user->organization_member->projects->count())
                    <ul>
                        @foreach($user->organization_member->projects as $project)
                        <li>{{ $project->title }}</li>
                        @endforeach
                    </ul>
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
</div>