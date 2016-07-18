<div class="userHome_follows">
    <a href="/people/{{ $user->url_name }}/follower">
        <span> Follower </span>
        <br>
        <strong id="user_numSubscriber"> {{ $user->subscribers()->count() }} </strong>
        <label> People </label>
    </a>
    <a href="/people/{{ $user->url_name }}/follow" class="userHome_followsMore">
        <span> Follow </span>
        <br>
        <strong> {{ $user->subscribe->users()->count() }} </strong>
        <label> People </label>
    </a>
</div>

<div class="userHome_sideBarSection">
    <div class="userHome_followTopicTitile">
        Subscribed Topics
    </div>
    <div class="userHome_followTopicItems">
        @foreach($user->subscribe->topics as $topic)
            <a href="/topic/{{ $topic->id }}">
                <img src="{{ DImage($topic->avatar_img_id, 25, 25) }}" class="img-rounded"
                 alt="{{ $topic->name }}"/>
            </a>
        @endforeach
    </div>
</div>

@if (Auth::user()->id != $user->id)
    <div class="userHome_sideBarSection">
        <div class="userHome_sideBarSectionInner">
            <div>
                @if($user->isBlockedBy(Auth::user()))
                    <form action="/settings/update" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="cancel_block" value="{{ $user->id }}">
                        <button type="submit" class="btn btn-danger"><span class="glyphicon glyphicon-ban-circle"></span>Cancel block</button>
                    </form>
                @else
                    <form action="/settings/update" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="block_users[]" value="{{ $user->id }}">
                        <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-ban-circle"></span>Block the user</button>
                    </form>
                @endif

            </div>
            <div class="margin-top">
                <button type="submit" class="btn btn-default"><span class="glyphicon  glyphicon-remove-circle"></span>Report the user</button>
            </div>
        </div>
    </div>
@endif

@if(Auth::user()->operation(17) && $user->id != Auth::user()->id)
    @if ($user->isBaned())
        <div class="userHome_sideBarSection noborder">
            <div class="userHome_sideBarSectionInner">
                <button class="btn btn-warning" data-action="user_operation" data-type="cancel" data-url_name="{{ $user->url_name }}">Cancel Ban</button>
            </div>
        </div>
    @else
        <div class="userHome_sideBarSection noborder">
            <div class="userHome_sideBarSectionInner">
                <button class="btn btn-danger" data-action="user_operation" data-type="ban" data-url_name="{{ $user->url_name }}">Ban User</button>
            </div>
        </div>
    @endif

@endif

<div class="userHome_sideBarSection noborder">
    <div class="userHome_sideBarSectionInner">
        This page has been viewed <span class="userHome_number"> {{ $user->hit->total }} </span> times.
    </div>
</div>

@include('partials._site_copyright')