<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=320, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    @if (Route::current()->getName() == 'topic.subscribed')
        <title>Subscribed Topics - NUSDing</title>
    @endif

    @if (Route::current()->getName() == 'topic.palace')
        <title>Topic Palace - NUSDing</title>
    @endif

    @if (Route::current()->getName() == 'topic.show')
        <title>{{ $topic->name }} - Topic - NUSDing</title>
    @endif

    @if (Route::current()->getName() == 'topic.edit')
        <title>{{ $topic->name }} - Topic - Edit - NUSDing</title>
    @endif

    @if (Route::current()->getName() == 'topic.organization')
        <title>{{ $topic->name }} - Topic - Organization - NUSDing</title>
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

<div class="container">
    <div class="row">
        <div class="col-md-8">
            @yield('left')
            <br>
        </div>


        <div class="col-md-4">
            @yield('right')

            @include('partials._site_copyright')
        </div>

    </div> <!--For row-->

</div> <!--For contianer-->

@include('partials._question_model')

@include('partials._bookmark_model')

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

{{--menu js file for suppporting asynchronous searching and popover--}}
{!! Html::script('js/bootstrap3-typeahead.js') !!}
{!! Html::script('js/navbar.js') !!}

{{--javascript template engine support--}}
{!! Html::script('js/handlebars-latest.js') !!}
{!! Html::script('js/templates.js') !!}

{{--tinymce support}}--}}
{!! Html::script('js/tinymce/tinymce.min.js') !!}
{!! Html::script('js/equation.js') !!}

{{--form validator javascript support--}}
{!! Html::script('js/validator.js') !!}

{{--prism supporting js file--}}
{!! Html::script('js/prism.js') !!}

{{--topic javascript support--}}
{!! Html::script('js/topic.js') !!}
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

</body>
</html>