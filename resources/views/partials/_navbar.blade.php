<nav class="navbar navbar-inverse navbar_backgroud navbar-fixed-top" id="navbar">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">Ding</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="navbar-collapse">
            <ul class="nav navbar-nav">
                    {{--for index button--}}
                @if(Request::is('/'))
                    <li class="navbar_active"><a href="/"><strong>Index</strong></a></li>
                @else
                    <li><a href="/">Index</a></li>
                @endif

                {{--for Topic button--}}
                @if(Auth::user())
                    @if(Request::is('topic'))
                        <li class="navbar_active"><a href="/topic"><strong>Topic</strong></a></li>
                    @else
                        <li><a href="/topic">Topic</a></li>
                    @endif
                @else
                    @if(Request::is('topics'))
                        <li class="navbar_active"><a href="/topics"><strong>Topic</strong></a></li>
                    @else
                        <li><a href="/topics">Topic</a></li>
                    @endif
                @endif

                {{--for Highlight button--}}
                @if(Request::is('highlight'))
                    <li class="navbar_active"><a href="/highlight"><strong>Highlight</strong></a></li>
                @else
                    <li><a href="/highlight">Highlight</a></li>
                @endif

                @if(Auth::user())
                    <li><a tabindex="0" role="button" id="user_notice">Notification</a></li>
                @endif
            </ul>

            <form class="navbar-form navbar-left" role="search">
                <div class="form-group">
                    <div class="input-group">
                        <input type="text" value="{{ Request::get('query') }}" class="form-control typeahead" placeholder="Search for Answers.." autocomplete="off" id="navbar_searching">
                        <div class="input-group-addon clickable" onclick="navbar_searching_click()">
                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                        </div>
                    </div>
                </div>
            </form>

            @if (Auth::guest())
                <form class="navbar-form navbar-right">
                    <a class="btn btn-primary btn-block" href="/login">Login to See More</a>
                </form>
            @else
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle navbar_portraitBox" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <img class="_portrait"
                                 src="{{ DImage(Auth::user()->settings->profile_pic_id, 25,25) }}"
                                 alt="{{ Auth::user()->name }}">
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="/people/{{ Auth::user()->url_name }}">{{ Auth::user()->name }} <span class="badge">{{ Auth::user()->authGroup->name }}</span></a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="/people/{{ Auth::user()->url_name }}"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> My Home Page</a></li>
                            <li><a href="/inbox"><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span> My Message</a></li>
                            <li><a href="/settings/basic"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span> Settings</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="/logout"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> Logout</a></li>
                        </ul>
                    </li>
                </ul>

                <form class="navbar-form navbar-right" role="ask">
                    <button type="button" class="btn btn-primary btn-block" onclick="navbar_ask_button()">Ask</button>
                </form>
            @endif


        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>

{{--for dispaly add point--}}
<div id="_point_operation">

</div>

<button class="btn close_detail_button noneDisplay" id="close_detail_button_answer" data-toggle="hide">
    <span class="glyphicon glyphicon-chevron-up"></span>Close
</button>
<button class="btn close_detail_button noneDisplay" id="close_detail_button_question" data-toggle="hide">
    <span class="glyphicon glyphicon-chevron-up"></span>Close
</button>

<a href="#" id="back-to-top" title="Back to top">
    <div id="back-to-top-arrow"></div>
    <div id="back-to-top-stick"></div>
</a>

@if(Auth::user() && Auth::user()->authGroup_id == 8)
    <div class="alert alert-warning fixed_top_right">
        Please complete identification verification <a href="/settings/account"><strong>(Bind your account with IVLE)</strong></a>
    </div>
@endif

@include('partials._noticebar_model')

@include('partials._latex_equation_model')

@include('partials._crop_image_model', [
        'url' => '/user/upload',
        'image' => '/static/images/default.png',
        'id' => '_question_detail',
        'type' => '',
    ])
