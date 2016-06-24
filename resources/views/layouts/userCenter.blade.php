<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=500px, initial-scale=2">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>User Center - Ding</title>

    <!-- Bootstrap -->
{!! Html::style('css/bootstrap.min.css') !!}

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    {{--sweetalert supporting file--}}
    {!! Html::style('css/sweetalert.css') !!}

    {{--select2 supporting file--}}
    {!! Html::style('css/select2.css') !!}

    {{--project css file--}}
    {!! Html::style('css/app.css') !!}

</head>
<body>

@include('partials._navbar')

<div class="container">
    <div class="row">
        <div class="col-md-8">
            @yield('content')
        </div>


        <div class="col-md-4">
            <div class="sideBar_section">
                <ul class="sideBar_item">
                    <li><a href="#"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> Notification</a></li>
                    <li><a href="#"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> My Draft</a></li>
                    <li><a href="#"><span class="glyphicon glyphicon-bookmark" aria-hidden="true"></span> My Bookmark</a></li>
                    <li><a href="#"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> My subscribe Question</a></li>
                    <li><a href="#"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Wait for me to Answer <span class="badge">4</span></a></li>
                </ul>
            </div>

            <div class="sideBar_section noborder">
                <ul class="sideBar_item">
                    <li><a href="#"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> My Edit Answer</a></li>
                </ul>
            </div>

            @include('partials._site_copyright')
        </div>

    </div> <!--For row-->

</div> <!--For contianer-->

{{--jQuery (necessary for Bootstrap's JavaScript plugins)--}}
{!! Html::script('js/jquery-1.12.3.min.js') !!}

{{--Bootstrap javascript for supporting bootstrap js animation--}}
{!! Html::script('js/bootstrap.min.js') !!}

{{--sweetalert supporting file--}}
{!! Html::script('js/sweetalert.min.js') !!}

{{--select2 supporting file--}}
{!! Html::script('js/select2.full.js') !!}

{{--menu js file for suppporting asynchronous searching and popover--}}
{!! Html::script('js/bootstrap3-typeahead.js') !!}
{!! Html::script('js/navbar.js') !!}

{{--javascript template engine support--}}
{!! Html::script('js/handlebars-latest.js') !!}
{!! Html::script('js/templates.js') !!}

{{--form validator javascript support--}}
{!! Html::script('js/validator.js') !!}

{{--userCenter javascript support--}}
{!! Html::script('js/userCenter.js') !!}

{{--app supporting js file--}}
{!! Html::script('js/main.js') !!}

@yield('javascript')

</body>
</html>