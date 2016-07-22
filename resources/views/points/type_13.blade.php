<span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span>
<span class="label label-{{ $point->point < 0 ? 'danger' : 'primary' }}">{{ $point->point }}</span>
Question Closed
<a href="/question/{{ \App\Question::find($point->param1)->id }}">
    {{ \App\Question::find($point->param1)->title }}
</a>
by
<a href="/people/{{ \App\User::find($point->param2)->url_name }}">
    {{ \App\User::find($point->param2)->name }}
</a>