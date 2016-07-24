@extends('layouts.auth')

@section('content')
    <div class="login_main">
        <div class="login_mainBody">
            <div class="login_mainBodyHeader">
                <h1 class="logo hide-text">DING</h1>
                <h4 class="logo_subtitle">Share your Knowledge with others</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading">Reset Password</div>
                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}, you will be redirected to login page in <strong><span id="count"></span></strong> second.
                        </div>
                    @else
                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/email') }}">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">E-Mail Address</label>

                                <div class="col-md-6">
                                    <input type="email" class="form-control" name="email" value="{{ old('email') }}">

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                    <div class="margin-top">
                                        {!! Recaptcha::render() !!}
                                    </div>
                                    @if ($errors->has('g-recaptcha-response'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-btn fa-envelope"></i>Send Password Reset Link
                                    </button>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
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


