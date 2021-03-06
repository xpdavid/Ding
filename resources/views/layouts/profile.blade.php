<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=320, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    @if (Route::current()->getName() == 'people.index')
        <title>{{ $user->name }}'s Profile - NUSDing</title>
    @endif

    @if (Route::current()->getName() == 'people.edit')
        <title>Edit {{ $user->name }}'s Profile - NUSDing</title>
    @endif

    @if (Route::current()->getName() == 'people.follow')
        <title>{{ $user->name }} Subscribe Users - NUSDing</title>
    @endif

    @if (Route::current()->getName() == 'people.follower')
        <title>{{ $user->name }}'s Subscribers - NUSDing</title>
    @endif

    @if (Route::current()->getName() == 'people.question')
        <title>{{ $user->name }}'s Questions</title>
    @endif

    @if (Route::current()->getName() == 'people.answer')
        <title>{{ $user->name }}'s Answers</title>
    @endif

    @if (Route::current()->getName() == 'people.more')
        <title>{{ $user->name }}'s Complete Profile</title>
    @endif

    @if (Route::current()->getName() == 'people.bookmark')
        <title>{{ $user->name }}'s Bookmarks</title>
    @endif

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

    {{--cropper supporting file--}}
    {!! Html::style('css/cropper.css') !!}

    {{--codesample supporting file--}}
    {!! Html::style('css/prism.css') !!}

    {{--project css file--}}
    {!! Html::style('css/app.css') !!}

    {{--equation support--}}
    <script type="text/javascript" async
            src="https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML">
    </script>

</head>
<body>

@include('partials._navbar')

@include('partials._question_model')

<div class="container">
    <div class="row">
        <div class="col-md-8">
            @yield('content')
        </div>


        <div class="col-md-4">
            @yield('side')
        </div>

    </div> <!--For row-->

</div> <!--For contianer-->

@include('partials._message_model', ['receiver_select' => [ $user->id => $user->name]])

{{--jQuery (necessary for Bootstrap's JavaScript plugins)--}}
{!! Html::script('js/jquery-1.12.3.min.js') !!}

{{--Bootstrap javascript for supporting bootstrap js animation--}}
{!! Html::script('js/bootstrap.min.js') !!}

{{--sweetalert supporting file--}}
{!! Html::script('js/sweetalert.min.js') !!}

{{--select2 supporting file--}}
{!! Html::script('js/select2.full.js') !!}

{{--cropper supporting file--}}
{!! Html::script('js/cropper.js') !!}
{!! Html::script('js/canvas-to-blob.js') !!}

{{--javascript template engine support--}}
{!! Html::script('js/handlebars-latest.js') !!}
{!! Html::script('js/templates.js') !!}

{{--menu js file for suppporting asynchronous searching and popover--}}
{!! Html::script('js/bootstrap3-typeahead.js') !!}
{!! Html::script('js/navbar.js') !!}

{{--tinymce support}}--}}
{!! Html::script('js/tinymce/tinymce.min.js') !!}
{!! Html::script('js/equation.js') !!}

{{--cropper supporting file--}}
{!! Html::script('js/cropper.js') !!}
{!! Html::script('js/canvas-to-blob.js') !!}

{{--form validator javascript support--}}
{!! Html::script('js/validator.js') !!}

{{--prism supporting js file--}}
{!! Html::script('js/prism.js') !!}

{{--inbox javascript support--}}
{!! Html::script('js/inbox.js') !!}

{{--profile javascript support--}}
{!! Html::script('js/profile.js') !!}
{!! Html::script('js/question.js') !!}

{{--app supporting js file--}}
{!! Html::script('js/main.js') !!}

@yield('javascript')

@if (Session::has('add_point'))
    <script type="text/javascript">
        $(function() {
            pointUI({{ Session::get('add_point') }});
        })

    </script>
@endif

@if (Auth::user()->operation(17))
    <script type="text/javascript">
        bindBanUserEvent();
    </script>
@endif

</body>
</html>