<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=500px, initial-scale=2">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>My Message - Ding</title>

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

@yield('content')

@include('partials._question_model')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            @include('partials._site_copyright')
        </div>
    </div>
</div>

{{--jQuery (necessary for Bootstrap's JavaScript plugins)--}}
{!! Html::script('js/jquery-1.12.3.min.js') !!}

{{--Bootstrap javascript for supporting bootstrap js animation--}}
{!! Html::script('js/bootstrap.min.js') !!}

{{--sweetalert supporting file--}}
{!! Html::script('js/sweetalert.min.js') !!}

{{--select2 supporting file--}}
{!! Html::script('js/select2.full.js') !!}

{{--tinymce support}}--}}
{!! Html::script('js/tinymce/tinymce.min.js') !!}
{!! Html::script('js/equation.js') !!}

{{--cropper supporting file--}}
{!! Html::script('js/cropper.js') !!}
{!! Html::script('js/canvas-to-blob.js') !!}

{{--app supporting js file--}}
{!! Html::script('js/main.js') !!}

{{--menu js file for suppporting asynchronous searching and popover--}}
{!! Html::script('js/bootstrap3-typeahead.js') !!}
{!! Html::script('js/navbar.js') !!}

{{--inbox javascript support--}}
{!! Html::script('js/inbox.js') !!}

@yield('javascript')
</body>
</html>