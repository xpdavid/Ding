{{--this is for notification {{User}} answer the question {{Question}}--}}
<span class="glyphicon glyphicon-education" aria-hidden="true"></span>
<a href="{{ env('APP_URL') }}/people/{{ $subject->url_name }}">{{ $subject->name }}</a>
answered the question
<a href="{{ env('APP_URL') }}/question/{{ $object->question->id }}/answer/{{ $object->id  }}">
    {{ $object->question->title }}
</a>
