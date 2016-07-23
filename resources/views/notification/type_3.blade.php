{{--this is for notification {{User}} @ you in his/her {{answer}}--}}
<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
<a href="{{ env('APP_URL') }}/people/{{ $subject->url_name }}">{{ $subject->name }}</a>
@ you in his/her answer:
<a href="{{ env('APP_URL') }}/question/{{ $object->question->id }}/answer/{{ $object->id  }}">
    {{ $object->question->title }}
</a>
