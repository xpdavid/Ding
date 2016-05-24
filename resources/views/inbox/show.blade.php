@extends('layouts.inbox')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="userMessage_Head">
                <div class="row">
                    <div class="col-md-9">
                        <a href="#">&lt;&lt;Back</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-9">
                        <button class="btn btn-warning btn-sm" type="submit">Reply</button>
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

@endsection