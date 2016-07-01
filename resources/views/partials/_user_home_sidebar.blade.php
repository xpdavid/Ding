<div class="userHome_follows">
    <a href="#">
        <span> Follower </span>
        <br>
        <strong id="user_numSubscriber"> {{ $user->subscribers()->count() }} </strong>
        <label> People </label>
    </a>
    <a href="#" class="userHome_followsMore">
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

<div class="userHome_sideBarSection noborder">
    <div class="userHome_sideBarSectionInner">
        This page has been viewed <span class="userHome_number"> 32 </span> times.
    </div>
</div>

@include('partials._site_copyright')