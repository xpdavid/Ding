{{--this is for notification {{User}} vote up your reply {{Reply}}--}}
<span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span>
<a href="{{ env('APP_URL') }}/people/{{ $subject->url_name }}">{{ $subject->name }}</a>
votes up your reply
<a href="{{ env('APP_URL') }}/reply/{{$object->id}}">
    {{ truncateText(clean($object->reply, 'nothing'), 50) }}
</a>
