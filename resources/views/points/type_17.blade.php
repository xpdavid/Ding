<span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
<span class="label label-{{ $point->point < 0 ? 'danger' : 'primary' }}">{{ $point->point }}</span>
Account Re-opened by
<a href="/people/{{ \App\User::find($point->param1)->url_name }}">
    {{ \App\User::find($point->param1)->name }}
</a>