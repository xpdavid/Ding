<nav class="navbar navbar-inverse navbar_backgroud navbar-fixed-top">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Brand</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="navbar_active"><a href="#">Index</a></li>
                <li><a href="#">Topic</a></li>
                <li><a href="#">Hightlight</a></li>
                <li><a tabindex="0" role="button" id="user_notice">Message</a></li>
            </ul>

            <form class="navbar-form navbar-left" role="search">
                <div class="form-group">
                    <div class="input-group">
                        <input type="text" class="form-control typeahead" placeholder="Search for Answers.." autocomplete="off">
                        <div class="input-group-addon navbar_clickable">
                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                        </div>
                    </div>
                </div>
            </form>

            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle navbar_portraitBox" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><img class="navbar_portrait" src="xp.jpeg" alt="肖朴"> <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> My Home Page</a></li>
                        <li><a href="#"><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span> My Message</a></li>
                        <li><a href="#"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span> Setting</a></li>
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
