@extends('layouts.auth')

@section('content')
    <div class="login_main">
        <div class="login_mainBody">
            <div class="login_mainBodyHeader">
                <img src="/static/images/ding-logo_revert.png">
                <h4 class="logo_subtitle">Share your ? with others</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <h2 class="text-danger">Oops!</h2>
            <h3 class="text-warning">This is awkward</h3>
            <h4>You're reaching the boundary of Ding</h4>
            <h4>There is no questions here, nethier answers</h4>
            <a class="btn btn-primary" href="/">
                Try exploring our homepage?
            </a>
            <div class="margin-top"></div>
            <div class="margin-top"></div>
            <div class="margin-top"></div>
            <div class="margin-top"></div>
        </div>

        <div class="col-md-4 col-md-offset-4">
            <p class="text-right">
                <strong>Error (404)</strong>
            </p>
            <p class="text-right">
                We can't find the page you're looking for. Check out our
                <a href="blog.nusding.info">Blog</a> for updates or send email to
                ticket@nusding.info for technical support.
            </p>
        </div>
    </div>
@endsection

@section('footer')
    <script type="text/javascript">
        @if (session('status'))
        // redirect count
        $(function() {
            var total = 10;
            $('#count').html(total);
            var interval = setInterval(function() {
                $('#count').html(total);
                total--;
                if (total == 0) {
                    clearInterval(interval);
                    window.location.replace('/');
                }
            },1000);
        });
        @endif
    </script>
@endsection


