{{--this is for notification {{User}} send message to you.--}}
<span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>
<a href="{{ env('APP_URL') }}/people/{{ $subject->url_name }}">{{ $subject->name }}</a>
has sent a message to you :
<a href="{{ env('APP_URL') }}/inbox/{{ $object->conversation->id }}">{{ $object->content }}</a>
