<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=320, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    @if (Route::current()->getName() == 'question.show')
        <title>{{ $question->title }} - NUSDing</title>
    @endif

    @if (Route::current()->getName() == 'answer.show')
        <title>{{ $answer->question->title }} - Answer from {{ $answer->owner->name }} - NUSDing</title>
    @endif

    @if (Route::current()->getName() == 'question.history')
        <title>{{ $question->title }} - History - NUSDing</title>
    @endif

    @if (Route::current()->getName() == 'answer.history')
        <title>{{ $question->title }} - Answer from {{ $answer->owner->name }} - History - NUSDing</title>
    @else
        <title>A</title>
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

    {{--for social share--}}
    <script type="text/javascript">(function(){window.switchTo5x=false;var e=document.createElement("script");e.type="text/javascript";e.async=true;e.onload=function(){try{stLight.options({publisher: "a72697fa-63bf-44fb-a0a9-c0ae69c85e45-a51c", doNotHash: false, doNotCopy: false, hashAddressBar: true});}catch(e){}};e.src=("https:" == document.location.protocol ? "https://ws" : "http://w") + ".sharethis.com/button/buttons.js";var s = document.getElementsByTagName("script")[0];s.parentNode.insertBefore(e, s);})();</script>
</head>
<body>

@include('partials._navbar')

<div class="container">
    <div class="row">
        <div class="col-md-8">
            @yield('left')
        </div>


        <div class="col-md-4">
            @yield('right')
        </div>

    </div> <!--For row-->

    <div class="row">
        <div class="col-12-md">
            @include('partials._site_copyright')
        </div>
    </div>

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

{{--form validator javascript support--}}
{!! Html::script('js/validator.js') !!}

{{--prism supporting js file--}}
{!! Html::script('js/prism.js') !!}

{{--tinymce support}}--}}
{!! Html::script('js/tinymce/tinymce.min.js') !!}
{!! Html::script('js/equation.js') !!}

{{--question javascript support--}}
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