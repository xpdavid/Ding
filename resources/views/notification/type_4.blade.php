{{--this is for notification {{User}} @ you in his/her {{question}}--}}
<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
<a href="/people/{{ $subject->url_name }}">{{ $subject->name }}</a>
@ you in his/her question: <a href="/question/{{$object->id}}">{{ $object->title }}</a>
