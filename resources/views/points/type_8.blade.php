<span class="glyphicon glyphicon-send" aria-hidden="true"></span>
<span class="label label-{{ $point->point < 0 ? 'danger' : 'primary' }}">{{ $point->point }}</span>
Edit Topic
<a href="/topic/{{ \App\Topic::find($point->param1)->id }}">
    {{ \App\Topic::find($point->param1)->name }}
</a>