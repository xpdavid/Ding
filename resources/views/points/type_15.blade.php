<span class="glyphicon glyphicon-send" aria-hidden="true"></span>
<span class="label label-{{ $point->point < 0 ? 'danger' : 'primary' }}">{{ $point->point }}</span>
Topic Closed
<a href="/topic/{{ \App\Topic::find($point->param1)->id }}">
    {{ \App\Topic::find($point->param1)->name }}
</a>
by
<a href="/people/{{ \App\User::find($point->param2)->url_name }}">
    {{ \App\User::find($point->param2)->name }}
</a>