{{--this is for notification {{User}} subscribe to a question : {{Question}}.--}}
<span class="glyphicon glyphicon-bell" aria-hidden="true"></span>
<a href="/people/{{ $subject->url_name }}">{{ $subject->name }}</a>
has subscribed to a question :
<a href="/question/{{ $object->id }}">{{ $object->title }}</a>
