@extends('layouts.inbox')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="userMessage_Head">
                <div class="row">
                    <div class="col-md-9">
                        <a href="{{ route('inbox.index') }}">&lt;&lt;Back</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-9">
                        @if(!$conversation->can_reply)
                            <span class="font-grey">You cannot reply this message</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="userMessage_Content clearfix">
                @foreach($conversation->messages as $message)
                    <div class="media">
                        <div class="media-left">
                            <a href="#">
                                <img class="media-object" src="{{ DImage($message->sendBy->settings->profile_pic_id, 50, 50)  }}" alt="{{ $message->sendBy->name }}">
                            </a>
                        </div>
                        <div class="media-body">
                            <h5 class="media-heading"><a href="/people/{{ $message->sendBy->url_name }}">{{ $message->sendBy->name }}</a></h5>
                            <div class="message_content">
                                {!! $message->content !!}
                            </div>
                            <div class="userMessage_ContentItemBottom">
                                <span class="userMessage_ContentItemBottomTime">{{ $message->created_at }}</span>
                            </div>
                        </div>
                    </div>
                    <hr>
                @endforeach


                @if($conversation->can_reply)
                    <div class="form-group">
                        {!! Form::label('Reply', 'Reply:') !!}
                        {!! Form::textarea('reply', null, ['class' => 'form-control', 'id' => 'message_content', 'rows' => 5]) !!}
                    </div>
                    <button class="btn btn-warning float-right" type="button" onclick="sendReply('{{ $conversation->id }}')">Reply</button>
                    <div id="message_content_error" class="text-danger noneDisplay">You must input message content</div>
                @endif

            </div>

        </div>
        <div class="col-md-4">
            <div>
                <h5>Participators</h5>
                <hr class="small_hrLight">
                <div class="question_subscribe">
                    <div>
                        <a href="#">{{  $conversation->users->count() }}</a> people participate in this conversation.
                    </div>
                    <div class="margin-top">
                        @foreach($conversation->users as $user)
                            <a href="/people/{{ $user->url_name }}">
                                <img src="{{ DImage($user->settings->profile_pic_id, 50, 50) }}" alt="{{ $user->name }}" class="img-rounded avatar-img">
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            <hr>
            <div>
                <div class="alert alert-success" role="alert">If you worry about spams, maybe <a href="/settings/notification" class="alert-link">Setting</a> is a good place to prevent it.</div>
            </div>

            <div>
                @if (count($errors) > 0)
                    <div class="panel panel-danger">
                        <div class="panel-heading">There are errors in this form</div>
                        <div class="panel-body">
                            @foreach($errors->all() as $error)
                                <p class="text-danger">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div> <!-- for row -->
</div>

@endsection