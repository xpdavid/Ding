{{--this is for notification {{User}} vote up your reply {{Reply}}--}}
<span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span>
<a href="/people/{{ $subject->url_name }}">{{ $subject->name }}</a>
votes up your reply
<a href="/question/{{$object->for_item->id}}">{{ $object->reply }}</a>