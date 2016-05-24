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
                                <img class="media-object" src="message.png" alt="...">
                            </a>
                        </div>
                        <div class="media-body">
                            <h5 class="media-heading"><a href="#">{{ $message->sendBy->name }}</a></h5>
                            <div class="message_content">
                                {{ $message->content }}
                            </div>
                            <div class="userMessage_ContentItemBottom">
                                <span class="userMessage_ContentItemBottomTime">{{ $message->created_at }}</span>
                                <a href="#"> Delete </a>
                            </div>
                        </div>
                    </div>
                    <hr>
                @endforeach


                @if($conversation->can_reply)
                    {!! Form::open(['url' => route('inbox.update', $conversation->id), 'method' => 'PATCH']) !!}
                        <div class="form-group">
                            {!! Form::label('Reply', 'Reply:') !!}
                            {!! Form::textarea('reply', null, ['class' => 'form-control', 'rows' => 5]) !!}
                        </div>
                        <button class="btn btn-warning float-right" type="submit">Reply</button>
                    {{ Form::close() }}
                @endif

            </div>

        </div>
        <div class="col-md-4">
            <div class="alert alert-success" role="alert">If you are worry about span, maybe <a href="#" class="alert-link">Setting</a> is a good place to prevent it.</div>
        </div>
    </div> <!-- for row -->
</div>

@endsection