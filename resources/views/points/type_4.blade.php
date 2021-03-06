<span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span>
<span class="label label-{{ $point->point < 0 ? 'danger' : 'primary' }}">{{ $point->point }}</span>
Receiving Vote from
<a href="/people/{{ \App\User::find($point->param2)->url_name }}">
    {{ \App\User::find($point->param2)->name }}
</a> in
<a href="/answer/{{ \App\Answer::find($point->param1)->id }}">
    {{ \App\Answer::find($point->param1)->question->title }}
</a>