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
                <a class="btn btn-default btn-xs" href="/people/edit" role="button">Edit My Profile</a>
            </div>

            <div class="userHome_profileHeaderRight">
                <p>
                    <span class="glyphicon glyphicon-globe" aria-hidden="true"></span>
                  <span class="userHome_profileItem">
                    <a href="#">
                      @if (count($user->educationExps) > 0)
                            {{$educationExp->first()->institution}}
                        @else
                            Fill up your institution
                        @endif
                    </a>
                  </span>
                  <span class="userHome_profileMoreItem">
                    <a href="#">
                      @if ((count($user->educationExps) > 0) && (count($user->educationExps->first()->major) > 0))
                            {{$user->educationExps->first()->major}}
                        @else
                            Fill up your major
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
                      @if (count($user->job) > 0)
                            {{ $user->job->first()->full_name }}
                        @else
                            Fill up your occupation
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
                          <a href="#">Email</a>
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

    <div class="userHome_pointSummary">
        Gain <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> <span class="userHome_number">10</span> Vote, <span class="glyphicon glyphicon-heart" aria-hidden="true"></span> <span class="userHome_number">10</span> Thanks
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
            <a href="#" class="userHome_profileNavbar_active">
                My Bookmarks
                <span>{{ $user->bookmarks()->count() }}</span>
            </a>
        @else
            <a href="#">
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