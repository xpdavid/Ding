<div class="userHome_layoutDiv">
    <div class="userHome_profileHeader">

        <div class="row userHome_profileHeaderTop">
            <div class="col-md-12">
                <h4><span class="userHome_name">{{ $user->name }}</span> , {{ $user->bio}}</h4>
            </div>
        </div>

        <div class="row">
            <div class="userHome_profileHeaderLeft">
                <img src="{{ DImage($user->settings->profile_pic_id, 100, 100) }}" alt="{{ $user->name }}" class="img-rounded">
                @if($user->id == Auth::user()->id)
                    <a class="btn btn-default btn-xs" href="/people/edit" role="button">Edit My Profile</a>
                @endif
            </div>

            <div class="userHome_profileHeaderRight">
                <p>
                    <span class="glyphicon glyphicon-globe" aria-hidden="true"></span>
                  <span class="userHome_profileItem">
                    <a href="#">
                      @if (count($user->educationExps) > 0)
                            {{$user->educationExps->first()->institution}}
                        @else
                            Not fill up yet
                        @endif
                    </a>
                  </span>
                  <span class="userHome_profileMoreItem">
                    <a href="#">
                      @if (count($user->educationExps) > 0)
                            {{$user->educationExps->first()->major}}
                      @endif
                    </a>
                  </span>
                  <span class="userHome_profileMoreItem">
                      {{ $user->sex }}
                  </span>
                </p>
                <p>
                    <span class="glyphicon glyphicon-briefcase" aria-hidden="true"></span>
                  <span class="userHome_profileItem">
                    <a href="#">
                      @if (count($user->jobs) > 0)
                            {{ $user->jobs->first()->organization }}
                        @else
                            Not fill up yet
                        @endif
                    </a>
                  </span>
                  <span class="userHome_profileMoreItem">
                    <a href="#">
                      @if (count($user->jobs) > 0)
                            {{$user->jobs->first()->designation}}
                        @else
                            Not fill up yet
                        @endif
                    </a>
                  </span>
                </p>
                <p>
                    <span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>
                  <span class="userHome_profileItem">
                    @if ($user->settings->display_email)
                          <a href="mailto:{{ $user->email }}">Email</a>
                      @else
                          Email
                      @endif
                  </span>
                  <span class="userHome_profileMoreItem">
                      @if ($user->settings->display_facebook)
                          <a href="#">Facebook</a>
                      @else
                          Facebook
                      @endif
                  </span>
                </p>
                <p>
                    <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>
                  <span class="userHome_profileItem">
                    <a href="#">
                      More Info..
                    </a>
                  </span>
                </p>
                <div class="userHome_selfIntro">
                    {{ $user->self_intro }}
                </div>
            </div>
        </div>

    </div>

    <div class="userHome_pointSummary clearfix">
        <div class="float-left margin-top">
            Gain <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> <span class="userHome_number">10</span> Vote, <span class="glyphicon glyphicon-heart" aria-hidden="true"></span> <span class="userHome_number">10</span> Thanks
        </div>
        @if(($user->id != Auth::user()->id) && ($user->canSubscribedBy(Auth::user())))
            <div class="float-right">
                @if(Auth::user()->subscribe->checkHasSubscribed($user->id, 'user'))
                    <button class="btn btn-default" onclick="user_button_subscribe(this, '{{ $user->id }}', 'user_numSubscriber')">Unsubscribe</button>
                @else
                    <button class="btn btn-success" onclick="user_button_subscribe(this, '{{ $user->id }}', 'user_numSubscriber')">Subscribe</button>
                @endif
                <button class="btn btn-default"><span class="space-right"></span><span class="glyphicon glyphicon-envelope"></span></button>
            </div>
        @endif
    </div>

    <div class="userHome_profileNavbar">
        <a href="#" class="glyphicon glyphicon-menu-hamburger"></a>
        <a href="#">
            My Question
            <span>{{ $user->questions()->count() }}</span>
        </a>
        <a href="#">
            My Answers
            <span>{{ $user->answers()->count() }}</span>
        </a>
        @if(Route::current()->getName() == 'people.bookmark')
            <a href="{{ route('people.bookmark', $user->url_name) }}" class="userHome_profileNavbar_active">
                My Bookmarks
                <span>{{ $user->bookmarks()->count() }}</span>
            </a>
        @else
            <a href="{{ route('people.bookmark', $user->url_name) }}">
                My Bookmarks
                <span>{{ $user->bookmarks()->count() }}</span>
            </a>
        @endif

        <a href="#">
            My Edit
            <span>0</span>
        </a>
    </div>

</div>