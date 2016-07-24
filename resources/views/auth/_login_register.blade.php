<div class="login_main">
    <div class="login_mainBody">
        <div class="login_mainBodyHeader">
            <h1 class="logo hide-text">DING</h1>
            <h4 class="logo_subtitle">Share your Knowledge with others</h4>
        </div>
        <div class="login_mainBodyOperation">
            <div class="login_nav">
                <div class="login_nav_options">
                    <a href="#signup" aria-controls="signup" role="tab" data-toggle="tab" onclick="moveSlideBar(0);" >Register</a>
                    <a href="#signin" aria-controls="signin" role="tab" data-toggle="tab" onclick="moveSlideBar(4.1);" >Login</a>
                    <span class="login_nav_slidebar"></span>
                </div>
            </div>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active login_signup" id="signup">
                    {{ Form::open(['url' => '/register', 'method' => 'POST', 'onsubmit' => 'return userRegister()'])}}
                    <div class="login_input_group">
                        <div class="login_input_wrapper">
                            <input id="register_name" type="text" class="form-control" name="name" placeholder="Name" onfocus="inputOnFocusHideHint(this)" value="{{ old('name') }}">
                            @if ($errors->has('name'))
                                <label class="login_input_error login_input_error_show">{{ $errors->first('name') }}</label>
                            @else
                                <label class="login_input_error">Please enter your name</label>
                            @endif

                        </div>
                        <div class="login_input_wrapper">
                            <input id="register_email" type="email" class="form-control" name="email" placeholder="E-Mail Address" onfocus="inputOnFocusHideHint(this)" value="{{ old('email') }}">
                            @if ($errors->has('email'))
                                <label class="login_input_error login_input_error_show">{{ $errors->first('email') }}</label>
                            @else
                                <label class="login_input_error">Please enter your email</label>
                            @endif
                        </div>
                        <div class="login_input_wrapper">
                            <input id="register_password" type="password" class="form-control" name="password" placeholder="Password" onfocus="inputOnFocusHideHint(this)">
                            @if ($errors->has('password'))
                                <label class="login_input_error login_input_error_show">{{ $errors->first('password') }}</label>
                            @else
                                <label class="login_input_error">Please enter your password</label>
                            @endif
                        </div>
                        <div class="login_input_wrapper">
                            <input id="register_password_confirmation" type="password" class="noborder form-control" name="password_confirmation" placeholder="Confirm Password" onfocus="inputOnFocusHideHint(this)">
                            @if ($errors->has('password_confirmation'))
                                <label class="login_input_error login_input_error_show">{{ $errors->first('password_confirmation') }}</label>
                            @else
                                <label class="login_input_error">Please enter your password</label>
                            @endif
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Register Ding</button>
                    <a href="/login/ivle">
                        <button type="submit" class="btn btn-warning">Login with IVLE</button>
                    </a>
                    <a href="/highlight">
                        <button type="submit" class="btn btn-link">Take a quick view</button>
                    </a>
                    {{ Form::close() }}
                </div>

                <div role="tabpanel" class="tab-pane fade login_signin" id="signin">
                    {{ Form::open(['url' => '/login', 'method' => 'POST', 'onsubmit' => 'return userLogin()'])}}
                    <div class="login_input_group">
                        <div class="login_input_wrapper">
                            <input id="login_email" type="email" class="form-control" name="email" placeholder="E-Mail Address" onfocus="inputOnFocusHideHint(this)">
                            @if ($errors->has('email'))
                                <label class="login_input_error login_input_error_show">{{ $errors->first('email') }}</label>
                            @else
                                <label class="login_input_error">Please enter your email</label>
                            @endif
                        </div>
                        <div class="login_input_wrapper">
                            <input id="login_password" type="password" class="form-control noborder" name="password" placeholder="Password" onfocus="inputOnFocusHideHint(this)">
                            @if ($errors->has('password'))
                                <label class="login_input_error login_input_error_show">{{ $errors->first('password') }}</label>
                            @else
                                <label class="login_input_error">Please enter your password</label>
                            @endif
                        </div>
                    </div>
                    <div class="clearfix">
                        <div class="float-left">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="remember" value="true"> Remember Me
                                </label>
                            </div>
                        </div>
                        <div class="float-right">
                            <div class="checkbox">
                                <label>
                                    <a href="/password/reset">Forget Password?</a>
                                </label>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Login Ding</button>
                    {{ Form::close() }}
                    <a href="/login/ivle">
                        <button type="submit" class="btn btn-warning">Login with IVLE</button>
                    </a>
                    <a href="/highlight">
                        <button type="submit" class="btn btn-link">Take a quick view</button>
                    </a>
                </div>


            </div>

        </div>
    </div>
</div>

<div class="login_footer">
    <span>&copy; 2016 Ding &amp; KKP</span>
    <span class="login_footer_dot">·</span>
    <a href="#">NUS SOC Orbital 2016</a>
    <span class="login_footer_dot">·</span>
    <a href="#">Blog</a>
    <span class="login_footer_dot">·</span>
    <a href="#">GitHub</a>
</div>