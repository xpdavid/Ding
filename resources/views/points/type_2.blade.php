<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
<span class="label label-{{ $point->point < 0 ? 'danger' : 'primary' }}">{{ $point->point }}</span>
Post Answer for
<a href="/answer/{{ \App\Answer::find($point->param1)->id }}">
    {{ \App\Answer::find($point->param1)->question->title }}
</a>