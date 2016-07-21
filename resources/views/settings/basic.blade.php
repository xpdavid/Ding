@extends('layouts.settings')

@section('content')
    @include('partials._user_settings_nav')
    <div class="container userSetting_content">
        <form role="form" data-toggle="validator" method="POST" action="/settings/update/{{ $user->id }}" class="form-horizontal" id="form1">
            {{ csrf_field() }}
            <div class="row userSetting_section" id="user_name_layout">
                <div class="col-md-8">
                    <div class="form-group" id="user_name">
                        <label class="col-sm-2 control-label">Name:</label>
                        <label class="col-sm-10 control-label userSetting_contentLabelLeft">{{ $user->name }}
                            <a href="#" id="user_name_edit_button" class="glyphicon glyphicon-pencil userSetting_EditA">Edit</a></label>
                    </div>
                    <div class="form-group noneDisplay m" id="user_name_edit">
                        <label class="col-sm-2 control-label">Name:</label>
                        <div class=" col-sm-9 userSetting_EditText">
                            <input type="text" class="form-control"
                                   name="name" id="nameChange" placeholder="Enter your name here.."
                                   value="{{ $user->name}}" autocomplete="off" required
                                   data-error="Bruh, enter your name here..">
                            <div class="help-block with-errors"></div>
                            <a href="#" class="float-right">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row userSetting_section">
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Your domain:</label>
                        <div class="col-sm-6">
                            <div class="input-group margin-top">
                                <div class="input-group-addon">http://nusding.com/</div>
                                <input type="text" class="form-control" name="personal_domain" placeholder="{{ $user->url_name }}"
                                       {{ $settings->personal_domain_modified || !Auth::user()->operation(10) ? 'disabled' : '' }}
                                >

                            </div>
                        </div>

                        <div class="col-sm-4">
                            <span class="userSetting_info">Permanent once edited</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row userSetting_section">
                <div class="col-md-8">
                    @if (count($errors) > 0)
                        <div class="panel panel-danger">
                            <div class="panel-heading">There is error in this form</div>
                            <div class="panel-body">
                                @foreach($errors->all() as $error)
                                    <p class="text-danger">{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="row userSetting_section noborder">
                <div class="col-md-8">
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('javascript')
    <script type="text/javascript">
        $(function() {
            genericUserProfileEditHover('#user_name');
            genericUserProfileEditToggleSetting('#user_name');
        });
    </script>
@endsection