{{--this is for notification {{User}} invite you to answer question {{Question}}--}}
<span class="glyphicon glyphicon-send" aria-hidden="true"></span>
<a href="/people/{{ $subject->url_name }}">{{ $subject->name }}</a>
invite your to answer the question
<a href="/question/{{$object->id}}">{{ $object->title }}</a>