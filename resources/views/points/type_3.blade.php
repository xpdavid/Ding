<span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span>
<span class="label label-{{ $point->point < 0 ? 'danger' : 'primary' }}">{{ $point->point }}</span>
Vote for Answer
<a href="/answer/{{ \App\Answer::find($point->param1)->id }}">
    {{ \App\answer::find($point->param1)->question->title }}
</a>