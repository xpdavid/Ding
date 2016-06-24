{{--this is for notification {{User}} @ you in his/her {{reply}}--}}
<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
<a href="/people/{{ $subject->url_name }}">{{ $subject->name }}</a>
@ you in his/her reply <a href="/reply/{{$object->id}}">{{ $object->reply }}</a>
