{{--this is for notification {{User}} subscribe to a topic : {{Topic}}.--}}
<span class="glyphicon glyphicon-paperclip" aria-hidden="true"></span>
<a href="{{ env('APP_URL') }}/people/{{ $subject->url_name }}">{{ $subject->name }}</a>
has subscribed to a topic :
<a href="{{ env('APP_URL') }}/topic/{{ $object->id }}">{{ $object->name }}</a>
