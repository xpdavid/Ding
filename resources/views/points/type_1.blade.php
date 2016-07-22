<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
<span class="label label-{{ $point->point < 0 ? 'danger' : 'primary' }}">{{ $point->point }}</span>
Post Question with Reward
<a href="/question/{{ \App\Question::find($point->param1)->id }}">
    {{ \App\Question::find($point->param1)->title }}
</a>