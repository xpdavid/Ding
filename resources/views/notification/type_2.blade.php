{{--this is for notification {{User}} answer the question {{Question}}--}}
<span class="glyphicon glyphicon-education" aria-hidden="true"></span>
<a href="/people/{{ $subject->url_name }}">{{ $subject->name }}</a>
answer the question
<a href="/question/{{$object->id}}">{{ $object->title }}</a>