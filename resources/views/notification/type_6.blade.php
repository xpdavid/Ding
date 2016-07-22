{{--this is for notification {{User}} reply your reply {{reply}}--}}
<span class="glyphicon glyphicon-comment" aria-hidden="true"></span>
<a href="/people/{{ $subject->url_name }}">{{ $subject->name }}</a>
replied you: <a href="/reply/{{$object->id}}">{{ $object->reply }}</a>
