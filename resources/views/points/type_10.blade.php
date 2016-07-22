<span class="glyphicon glyphicon-send" aria-hidden="true"></span>
<span class="label label-{{ $point->point < 0 ? 'danger' : 'primary' }}">{{ $point->point }}</span>
Question
<a href="/question/{{ \App\History::find($point->param1)->forItem->id }}">
    {{ \App\History::find($point->param1)->forItem->title }}
</a>
editing rejected.
