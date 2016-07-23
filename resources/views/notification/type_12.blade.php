{{--this is for notification {{User}} post a question {{Question}}}.--}}
<span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>
<a href="{{ env('APP_URL') }}/people/{{ $subject->url_name }}">{{ $subject->name }}</a>
has posted a question :
<a href="{{ env('APP_URL') }}/question/{{ $object->id }}">{{ $object->title }}</a>
