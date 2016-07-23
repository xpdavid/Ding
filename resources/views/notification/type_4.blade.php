{{--this is for notification {{User}} @ you in his/her {{question}}--}}
<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
<a href="{{ env('APP_URL') }}/people/{{ $subject->url_name }}">{{ $subject->name }}</a>
@ you in his/her question: <a href="{{ env('APP_URL') }}/question/{{$object->id}}">{{ $object->title }}</a>
