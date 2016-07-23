@extends('layouts.inbox')


@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="userMessage_Head">
                <div class="row">
                    <div class="col-md-12 clearfix">
                        <div class="float-left">
                            <h4>My Message <span class="badge">{{ $conversations->count() }}</span></h4>
                        </div>
                        <div class="float-right">
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#sendModal">Send New Message</button>
                        </div>
                    </div>

                </div>
            </div>
            <div class="userMessage_Content">
                @foreach($conversations as $conversation)
                    {{--we use the first message to display in the index page--}}
                    {{--There is at least one message in the conversation--}}
                    <!--{!! $message = $conversation->messages->all()[0] !!}-->
                    <div class="media">
                        <div class="media-left">
                            <a href="/people/{{ $message->sendBy->url_name }}">
                                <img class="media-object" src="{{ DImage($message->sendBy->settings->profile_pic_id, 50, 50) }}" alt="{{ $message->sendBy->name }}">
                            </a>
                        </div>
                        <div class="media-body">
                            <h5 class="media-heading"><a  href="/people/{{ $message->sendBy->url_name }}">{{ $message->sendBy->name }}</a></h5>
                            <div class="message_content">
                                {!! $message->content !!}
                            </div>
                            <div class="userMessage_ContentItemBottom">
                                <span class="userMessage_ContentItemBottomLeft space-right-big">{{ $conversation->created_at }}</span>
                            <span class="userMessage_ContentItemBottomLeft">
                                @foreach($conversation->users as $user)
                                    @if($user->id !== Auth::user()->id)
                                        <a href="#" class="space-right">{{ $user->name }}</a>
                                    @endif
                                @endforeach
                                also in this conversation
                            </span>
                                <a href="{{ route('inbox.show', $conversation->id) }}"> {{ $conversation->messages->count() }} Message(s)</a>
                                <span class="userMessage_verticalLine">|</span>
                                <a href="#" onclick="hideConversation({{ $conversation->id }})"> Hide </a>
                            </div>
                        </div>
                    </div>
                    <hr>
                @endforeach



            </div>

        </div>
        <div class="col-md-4">
            <div class="alert alert-success" role="alert">If you worry about spams, maybe <a href="/settings/notification" class="alert-link">Setting</a> is a good place to prevent it.</div>
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    Message failed to be sent:
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div> <!-- for row -->
</div>

{{--for message modal--}}
@include('partials._message_model')

@endsection

