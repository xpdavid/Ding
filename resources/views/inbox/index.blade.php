@extends('layouts.inbox')


@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="userMessage_Head">
                <div class="row">
                    <div class="col-md-9">
                        <h4>My Message <span class="badge">42</span></h4>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#sendModal">Send New Message</button>
                    </div>
                </div>
            </div>
            <div class="userMessage_Content">
                <div class="media">
                    <div class="media-left">
                        <a href="#">
                            <img class="media-object" src="message.png" alt="...">
                        </a>
                    </div>
                    <div class="media-body">
                        <h5 class="media-heading"><a href="#">KKP Admin :</a></h5>
                        Hello, Welcom to the Ding Answer Platform! <br>
                        Very Warm welcome to you! <br>
                        We're enhancing profiles on Stack Overflow. <br>
                        Update your profile in three easy steps <br>
                        <div class="userMessage_ContentItemBottom">
                            <span class="userMessage_ContentItemBottomTime">Jan 27 19:33</span>
                            <a href="#"> 3 Conversations</a>
                            <span class="userMessage_verticalLine">|</span>
                            <a href="#"> Delete </a>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="media">
                    <div class="media-left">
                        <a href="#">
                            <img class="media-object" src="message.png" alt="...">
                        </a>
                    </div>
                    <div class="media-body">
                        <h5 class="media-heading"><a href="#">KKP Admin :</a></h5>
                        Hello, Welcom to the Ding Answer Platform!
                        <div class="userMessage_ContentItemBottom">
                            <span class="userMessage_ContentItemBottomTime">Jan 27 19:33</span>
                            <a href="#"> 3 Conversations</a>
                            <span class="userMessage_verticalLine">|</span>
                            <a href="#"> Delete </a>
                        </div>
                    </div>
                </div>
                <hr>

            </div>

        </div>
        <div class="col-md-4">
            <div class="alert alert-success" role="alert">If you are worry about span, maybe <a href="#" class="alert-link">Setting</a> is a good place to prevent it.</div>
        </div>
    </div> <!-- for row -->
</div>

<div class="modal fade" id="sendModal" tabindex="-1" role="dialog" aria-labelledby="sendModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Send Message</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">User</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" id="inputEmail3" placeholder="Email">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">Content</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" rows="4"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Send</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@endsection