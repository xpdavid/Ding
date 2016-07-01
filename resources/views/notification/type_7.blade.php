{{--this is for notification {{User}} vote up (your) answer {{answer}}--}}
<span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span>
<a href="/people/{{ $subject->url_name }}">{{ $subject->name }}</a>
votes up
@if ($object->owner->id == Auth::user()->id)
your
@endif
answer in question :
<a href="/question/{{$object->question->id}}/answer/{{ $object->id }}">{{ $object->question->title }}</a>
