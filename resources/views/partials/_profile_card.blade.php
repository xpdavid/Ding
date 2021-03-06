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
                            Not filled up yet
                        @endif
                    </a>
                  </span>
                  <span class="userHome_profileItem">
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
                            Not filled up yet
                        @endif
                    </a>
                  </span>
                  <span class="userHome_profileMoreItem">
                    <a href="#">
                      @if (count($user->jobs) > 0)
                            {{$user->jobs->first()->designation}}
                        @else
                            Not filled up yet
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
                </p>
                <p>
                    <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>
                  <span class="userHome_profileItem">
                    <a href="/people/{{ $user->url_name }}/more">
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
            <span class="label label-default">{{ $user->authGroup->name }}</span>
            Gain <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> <strong>{{ $user->totalVotes }}</strong> Vote,
            <span class="glyphicon glyphicon-heart" aria-hidden="true"></span> <strong>{{ $user->totalLikes }}</strong> Likes
        </div>

            <div class="float-right">
                @if(($user->id != Auth::user()->id) && ($user->canSubscribedBy(Auth::user())))
                    @if(Auth::user()->subscribe->checkHasSubscribed($user->id, 'user'))
                        <button class="btn btn-default" onclick="user_button_subscribe(this, '{{ $user->id }}', 'user_numSubscriber')">Unsubscribe</button>
                    @else
                        <button class="btn btn-success" onclick="user_button_subscribe(this, '{{ $user->id }}', 'user_numSubscriber')">Subscribe</button>
                    @endif
                @endif

                @if(($user->id != Auth::user()->id) && (Auth::user()->canSendMessageTo($user)))
                    <button class="btn btn-default"  data-toggle="modal" data-target="#sendModal"><span class="space-right"></span><span class="glyphicon glyphicon-envelope"></span></button>
                @endif
            </div>
    </div>

    <div class="userHome_profileNavbar">
        <a href="{{ route('people.index', $user->url_name) }}"
           class="{{ Route::current()->getName() == 'people.index' ? 'userHome_profileNavbar_active' : '' }}"
        >
            <span class="space-right"></span><span class="glyphicon glyphicon-menu-hamburger"></span>
        </a>
        <a href="{{ route('people.question', $user->url_name) }}"
           class="{{ Route::current()->getName() == 'people.question' ? 'userHome_profileNavbar_active' : '' }}">
            Questions
            <span>{{ $user->questions()->count() }}</span>
        </a>
        <a href="{{ route('people.answer', $user->url_name) }}"
           class="{{ Route::current()->getName() == 'people.answer' ? 'userHome_profileNavbar_active' : '' }}">
            Answers
            <span>{{ $user->answers()->count() }}</span>
        </a>

        <a href="{{ route('people.bookmark', $user->url_name) }}"
           class="{{ Route::current()->getName() == 'people.bookmark' ? 'userHome_profileNavbar_active' : '' }}">
            Bookmarks
            <span>{{ $user->bookmarks()->where('is_public', true)->count() }}</span>
        </a>
    </div>

</div>