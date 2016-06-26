@extends('layouts.userCenter')


@section('content')

    <div class="userHome_layoutDiv">
      <div class="userHome_profileHeader">

          <div class="row userHome_profileHeaderTop">
            <div class="col-md-12">
              <h4><span class="userHome_name">{{ $user->name }}</span> @if ($user->bio != '') , {{ $user->bio }} @else <a href="/people/edit">Edit your bio</a> @endif </h4>
            </div>
          </div>

          <div class="row">
            <div class="userHome_profileHeaderLeft">
              <img src="xp_l.jpeg" alt="..." class="img-rounded">
              <a class="btn btn-default btn-xs" href="/profile/edit" role="button">Edit My Profile</a>
            </div>

            <div class="userHome_profileHeaderRight">
              <p>
                <span class="glyphicon glyphicon-globe" aria-hidden="true"></span>
                <span class="userHome_profileItem">
                @if ($user->educationExps)
                    {{ $user->educationExps->first()->institution }}
                @else
                    <a href="/people/edit">Add your school </a>
                @endif
                </span>
                <span class="userHome_profileMoreItem">
                @if ($user->educationExps->first()->major!='')
                    {{ $user->educationExps->first()->major }}  
                @else  
                    <a href="/people/edit">Add your major </a>
                @endif
                </span>
                <span class="userHome_profileMoreItem">
                    {{ $user->sex }}
                </span>
              </p>
              <p>
                <span class="glyphicon glyphicon-briefcase" aria-hidden="true"></span>
                <span class="userHome_profileItem">
                    @if ($user->jobs->first() != '')
                        {{ $user->jobs->first()->designation }} , {{ $user->jobs->first()->organization}}
                        
                    @else
                        <a href="/people/edit">Add your work experiences </a>
                    @endif
                </span>
              </p>
              <p>
                <span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>
                <span class="userHome_profileItem">
                    @if ($settings->display_email)
                        <a href="mailto:{{ $user->email }}">
                            Email
                        </a>
                    @else
                        Email
                    @endif
                </span>
                <span class="userHome_profileMoreItem">
                    @if ($settings->display_facebook)
                        <a href="#">
                            Facebook
                        </a>
                    @else
                        Facebook
                    @endif
                </span>
              </p>
              <div class="userHome_selfIntro">
                @if ($user->self_intro != '')
                    {{ $user->self_intro }}
                @else
                    <a href="/people/edit">Add your self-intro</a>
                @endif
              </div>
            </div>
          </div>

      </div>

      <div class="userHome_pointSummary"> 
            Gained <span class="glyphicon glyphicon-ok" aria-hidden="true"></span><span class="userHome_number">{{ $voteCount }}</span> Votes

      </div>

      <div class="userHome_profileNavbar">
          <a href="#" class="glyphicon glyphicon-menu-hamburger"></a>
          <a href="#">
            My Question
            <span>{{ count($user->questions) }}</span>
          </a>
          <a href="#">
            My Answers
            <span>{{ count($user->answers) }}</span>
          </a>
          <a href="#">
            My Bookmarks
            <span>0</span>
          </a>
          <a href="#">
            My Edit
            <span>0</span>
          </a>
      </div>

    </div>
    <hr class="small_hrLight">

    <div id="user_home_question">

    </div>

    <button class="btn btn-default btn-block" type="button" id="home_question_more" onclick="getHomeQuestion()">More</button>

@endsection

@section('javascript')
    <script type="text/javascript">
        $(function() {
            getHomeQuestion();
            userHome_vote_button_trigger();
        });
    </script>
@endsection