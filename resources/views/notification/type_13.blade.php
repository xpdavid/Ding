{{--this is for notification {{User}} subscribe to a question : {{Question}}.--}}
<span class="glyphicon glyphicon-bell" aria-hidden="true"></span>
<a href="{{ env('APP_URL') }}/people/{{ $subject->url_name }}">{{ $subject->name }}</a>
has subscribed to a question :
<a href="{{ env('APP_URL') }}/question/{{ $object->id }}">{{ $object->title }}</a>
