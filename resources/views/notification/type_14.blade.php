{{--this is for notification {{User}} subscribe to a topic : {{Topic}}.--}}
<span class="glyphicon glyphicon-paperclip" aria-hidden="true"></span>
<a href="/people/{{ $subject->url_name }}">{{ $subject->name }}</a>
has subscribed to a topic :
<a href="/topic/{{ $object->id }}">{{ $object->name }}</a>
