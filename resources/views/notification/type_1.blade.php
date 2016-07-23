{{--this is for notification {{User}} invite you to answer question {{Question}}--}}
<span class="glyphicon glyphicon-send" aria-hidden="true"></span>
<a href="{{ env('APP_URL') }}/people/{{ $subject->url_name }}">{{ $subject->name }}</a>
has invited you to answer this question
<a href="{{ env('APP_URL') }}/question/{{$object->id}}">{{ $object->title }}</a>