@if($comments->count())
@foreach($comments as $comment)
<div class="media">
    <img class="mr-3 rounded-circle" alt="Bootstrap Media Preview" src="{{asset('assets/dist/img/avatar.png')}}" />
    <div class="media-body">
        <div class="row">
            <div class="col-8 d-flex">
                <h5>{{ $comment->creator_user->name }}</h5> <span>- {{$comment->created_at->diffForHumans()}}</span>
            </div>
            <div class="col-4 text-right">
                <div class="pull-right reply">
                    <a href="#" onclick="event.preventDefault(); commentReply('{{$comment->id}}');" class="replyBtn"><span><i class="fa fa-reply"></i> reply</span></a>
                </div>
            </div>
        </div>
        <p>{{ $comment->comment }}</p>
        <!-- Replay of comment -->
        <div class="media mt-4">
            <a class="pr-3" href="#">
                <img class="rounded-circle" alt="Bootstrap Media Another Preview" src="{{asset('assets/dist/img/avatar2.png')}}" />
            </a>
            <div class="media-body">
                <div class="row">
                    <div class="col-12 d-flex">
                        <h5>Simona Disa</h5> <span>- 3 hours ago</span>
                    </div>
                </div> letters, as opposed to using 'Content here, content here', making it look like readable English.
            </div>
        </div>
        <div class="replyFormParents mt-4">
            <form id="replyFormId" class="formReply_{{$comment->id}}" stle="display:none;">
                <input type="hidden" id="projectId" name="project_id" value="{{$comment->project_id}}">
                <input type="hidden" id="reportId" name="report_id" value="{{$comment->project_report_id}}">
                <input type="hidden" id="commentId" name="comment_id" value="{{$comment->id}}">
                <div class="form-group">
                    <label for="reply">Replay</label>
                    <textarea id="reply" name="reply" class="form-control" rows="4"></textarea>
                </div>
                <div class="form-group">
                    <button type="submit" id="replySubmit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endif