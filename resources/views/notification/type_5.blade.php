{{--this is for notification {{User}} @ you in his/her {{reply}}--}}
<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
<a href="{{ env('APP_URL') }}/people/{{ $subject->url_name }}">{{ $subject->name }}</a>
@ you in his/her reply <a href="{{ env('APP_URL') }}/reply/{{$object->id}}">{{ $object->reply }}</a>
