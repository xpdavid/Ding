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
            <a class="navbar-brand" href="#">Ding</a>
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
                @if(Request::is('topic'))
                    <li class="navbar_active"><a href="/topic"><strong>Topic</strong></a></li>
                @else
                    <li><a href="/topic">Topic</a></li>
                @endif

                    {{--for Highlight button--}}
                @if(Request::is('highlight'))
                    <li class="navbar_active"><a href="/highlight"><strong>Highlight</strong></a></li>
                @else
                    <li><a href="/highlight">Highlight</a></li>
                @endif

                <li><a tabindex="0" role="button" id="user_notice">Notification</a></li>
            </ul>

            <form class="navbar-form navbar-left" role="search">
                <div class="form-group">
                    <div class="input-group">
                        <input type="text" class="form-control typeahead" placeholder="Search for Answers.." autocomplete="off" id="navbar_seraching">
                        <div class="input-group-addon navbar_clickable">
                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                        </div>
                    </div>
                </div>
            </form>

            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle navbar_portraitBox" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <img class="navbar_portrait"
                             src="{{ DImage(Auth::user()->settings->profile_pic_id, 25,25) }}"
                             alt="{{ Auth::user()->name }}">
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="/people/{{ Auth::user()->url_name }}"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> My Home Page</a></li>
                        <li><a href="/inbox"><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span> My Message</a></li>
                        <li><a href="/settings"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span> Setting</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="/logout"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> Logout</a></li>
                    </ul>
                </li>
            </ul>

            <div class="nav navbar-nav navbar-right">
                <button type="button" class="btn btn-primary navbar_ask_button" data-toggle="modal" data-target="#ask_question">Ask</button>
            </div>

        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>

@include('partials._noticebar_model')
