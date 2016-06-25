{{--this is for notification {{User}} send message to you.--}}
<span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>
<a href="/people/{{ $subject->url_name }}">{{ $subject->name }}</a>
sends message to you :
<a href="/inbox/{{ $object->conversation->id }}">{{ $object->content }}</a>
