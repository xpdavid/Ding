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

    {{--equation support--}}
    <script type="text/javascript" async
            src="https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML">
    </script>

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
                        <li class="{{ Request::is('notification') ? 'sideBar_item_active' : '' }}">
                            <a href="/notification">
                                <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> Notification
                            </a>
                        </li>

                        <li><a href="/mybookmark" class="{{ Request::is('bookmark') ? 'sideBar_item_active' : ''}}">
                                <span class="glyphicon glyphicon-bookmark" aria-hidden="true"></span>
                                Bookmark</a>
                        </li>

                        <li class="{{ Request::is('draft') ? 'sideBar_item_active' : '' }}">
                            <a href="/draft"><span class="glyphicon glyphicon-file" aria-hidden="true"></span>
                                My Draft</a>
                        </li>

                        <li class="{{ Request::is('subscribed') ? 'sideBar_item_active' : '' }}">
                            <a href="/subscribed"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                                Subscribed Questions</a>
                        </li>

                        <li class="{{ Request::is('invitation') ?  'sideBar_item_active' : ''}}">
                            <a href="/invitation"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                Invitaion</a>
                        </li>
                </ul>
            </div>

            <div class="sideBar_section noborder">
                <ul class="sideBar_item">
                    <li><a href="/topics"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> Topics Palace</a></li>
                    <li><a href="#"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> My Edit Answer</a></li>
                </ul>
            </div>

            @include('partials._site_copyright')
        </div>

    </div> <!--For row-->

</div> <!--For contianer-->

@include('partials._question_model')

@include('partials._show_comment_conversation')

@include('partials._bookmark_model')

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

{{--cropper supporting file--}}
{!! Html::script('js/cropper.js') !!}
{!! Html::script('js/canvas-to-blob.js') !!}

{{--tinymce support}}--}}
{!! Html::script('js/tinymce/tinymce.min.js') !!}
{!! Html::script('js/equation.js') !!}

{{--userCenter javascript support--}}
{!! Html::script('js/userCenter.js') !!}
{!! Html::script('js/question.js') !!}
{!! Html::script('js/profile.js') !!}

{{--app supporting js file--}}
{!! Html::script('js/main.js') !!}

@yield('javascript')

</body>
</html>