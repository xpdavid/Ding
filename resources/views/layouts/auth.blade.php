<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=320, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Ding</title>

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

    {{--project css file--}}
    {!! Html::style('css/app.css') !!}

</head>
<body class="login_body">
<div id="login_bg_text">
</div>
<div id="login_bg_line">
</div>
@yield('content')


{{--jQuery (necessary for Bootstrap's JavaScript plugins)--}}
{!! Html::script('js/jquery-1.12.3.min.js') !!}

{{--Bootstrap javascript for supporting bootstrap js animation--}}
{!! Html::script('js/bootstrap.min.js') !!}

{{--sweetalert supporting file--}}
{!! Html::script('js/sweetalert.min.js') !!}

{{--app supporting js file--}}
{!! Html::script('js/main.js') !!}

{{--menu js file for suppporting asynchronous searching and popover--}}
{!! Html::script('js/bootstrap3-typeahead.js') !!}

{{--inbox javascript support--}}
{!! Html::script('js/login.js') !!}

{{--for recapacha support--}}
<script src="https://www.google.com/recaptcha/api.js?onload=CaptchaCallback&render=explicit" async defer></script>
<script type="text/javascript">
    var CaptchaCallback = function() {
        grecaptcha.render('RecaptchaField1', {'sitekey' : '{{ env('RECAPTCHA_PUBLIC_KEY') }}'});
        grecaptcha.render('RecaptchaField2', {'sitekey' : '{{ env('RECAPTCHA_PUBLIC_KEY') }}'});
    };
</script>

@yield('footer')
</body>
</html>