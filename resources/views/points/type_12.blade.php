<span class="glyphicon glyphicon-send" aria-hidden="true"></span>
<span class="label label-{{ $point->point < 0 ? 'danger' : 'primary' }}">{{ $point->point }}</span>
Topic
<a href="/topic/{{ \App\History::find($point->param1)->forItem->id }}">
    {{ \App\History::find($point->param1)->forItem->name }}
</a>
editing rejected.