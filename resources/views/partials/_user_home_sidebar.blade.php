<div class="userHome_follows">
    <a href="#">
        <span> Follower </span>
        <br>
        <strong> {{ Auth::user()->subscribers()->count() }} </strong>
        <label> People <label>
    </a>
    <a href="#" class="userHome_followsMore">
        <span> Follow </span>
        <br>
        <strong> {{ Auth::user()->subscribe->users()->count() }} </strong>
        <label> People <label>
    </a>
</div>

<div class="userHome_sideBarSection">
    <div class="userHome_followTopicTitile">
        Follows
    </div>
    <div class="userHome_followTopicItems">
        @foreach(Auth::user()->subscribe->users as $user)
            <a href="/people/{{ $user->url_name }}">
                <img src="{{ DImage($user->settings->profile_pic_id, 25, 25) }}" class="img-rounded"
                 alt="{{ $user->name }}"/>
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