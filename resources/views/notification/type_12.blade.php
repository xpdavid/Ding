{{--this is for notification {{User}} post a question {{Question}}}.--}}
<span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>
<a href="/people/{{ $subject->url_name }}">{{ $subject->name }}</a>
posts a question :
<a href="/question/{{ $object->id }}">{{ $object->title }}</a>
