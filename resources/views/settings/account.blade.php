@extends('layouts.settings')

@section('content')
    @include('partials._user_settings_nav')
    <div class="container userSetting_content">
        <div class="row userSetting_section">
            <div class="col-md-12">
                <div class="userSetting_info">
                    Change your password and manage other login methods
                </div>
            </div>
        </div>
        <form method="POST" action="/settings/update/{{ $user->id }}" data-toggle="validator" role="form" class="form-horizontal" id="form2">
            {{ csrf_field() }}
            <div class="row userSetting_section">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="old_password" class="col-sm-3 control-label userSetting_contentLabelLeft">Old Password</label>
                        <div class="col-sm-5">
                            <input type="password" class="form-control" name="old_password" placeholder="Old Password">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="new_password" class="col-sm-3 control-label userSetting_contentLabelLeft">New Password</label>
                        <div class="col-sm-5">
                            <input type="password" class="form-control" name="new_password" id="newPassword" placeholder="New Password" data-minlength="6">
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password" class="col-sm-3 control-label userSetting_contentLabelLeft">Confirm Password</label>
                        <div class="col-sm-5">
                            <input type="password" name="confirm_password" class="form-control" placeholder="Confirm password" id="newPasswordConfirm" data-match="#newPassword" data-match-error="Passwords do not match">
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row userSetting_section">
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="col-sm-3 control-label userSetting_contentLabelLeft">Other Login Methods</label>
                        <div class="col-sm-9 control-label userSetting_contentLabelLeft userSetting_Item">
                            <a href="#">NUS Open ID</a>
                            <a href="#">IVLE</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row userSetting_section">
                <div class="col-md-8">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>


            <div class="row userSetting_section">
                <div class="col-md-8">
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-10">
                            <button type="submit" class="btn btn-primary">Change</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('javascript')

@endsection