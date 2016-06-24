{{--this is for notification {{User}} @ you in his/her {{answer}}--}}
<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
<a href="/people/{{ $subject->url_name }}">{{ $subject->name }}</a>
@ you in his/her answer: <a href="/question/{{$object->question->id}}">{{ $object->question->title }}</a>
