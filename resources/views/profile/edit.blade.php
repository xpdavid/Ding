@extends('layouts.profile')

@section('content')
    <div class="userHome_layoutDiv">
        <div class="userHome_profileHeader">
            <div class="row">
                <div class="col-md-3 userEdit_completeBar">
                    Profile Complete
                </div>
                <div class="col-md-9">
                    <div class="progress userEdit_completeBar">
                        <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                            60%
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- My Profile Edit -->
    <div class="userHome_layoutDiv">
        <div class="userHome_layoutDivHead">
            <a href="#">{{ $user->name }} <span>Edit Profile</span></a>
        </div>
        <div class="userHome_layoutDivContent">
            <div class="userEdit_item">
                <div class="row">
                    <div class="col-sm-offset-1 col-sm-2">
                        Your Profile
                    </div>
                    <div class="col-sm-6">
                        <img src="{{ $profile->profile_pic_name }}" alt="..." class="img-rounded" />
                    </div>
                </div>
            </div>
            <div class="userEdit_item">
                <div class="row">
                    <div class="col-sm-offset-1 col-sm-2">
                        Sex
                    </div>
                    <div class="col-sm-6">
                        <div id="user_sex"><span id="user_sex_status">{{ $profile->sex }}</span> <a href="#" class="glyphicon glyphicon-pencil userSetting_EditA">Edit</a></div>
                        <div id="user_sex_edit">
                            {{ Form::open(['url' => '#',
                            'data-toggle' => "validator",
                            'role' => "form",
                            'onsubmit' => 'return saveUserSex()']) }}
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group" id="user_sex_radio">
                                            <label class="col-sm-3 radio-inline">
                                                <input type="radio" name="user_sex" value="Secret" 
                                                @if ($profile->sex == 'Secret') checked="checked" @endif > Secret
                                            </label>
                                            <label class="col-sm-3 radio-inline">
                                                <input type="radio" name="user_sex" value="Male"
                                                @if ($profile->sex == 'Male') checked="checked" @endif > Male
                                            </label>
                                            <label class="col-sm-3 radio-inline">
                                                <input type="radio" name="user_sex" value="Female"
                                                @if ($profile->sex == 'Female') checked="checked" @endif> Female
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row userEdit_buttonGroup">
                                    <div class="col-sm-12 user_sex_btn">
                                        <button class="btn btn-primary btn-sm" type="submit">Save</button>
                                        <a href="#" class="userEdit_cancelButton">Cancel</a>
                                    </div>
                                </div>
                             {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="userEdit_item">
                {{ Form::open(['url' => '#',
                'data-toggle' => "validator",
                'role' => "form",
                'onsubmit' => 'return saveUserDisplayFacebook()']) }}
                    <div class="row">
                        <div class="col-sm-offset-1 col-sm-2">
                            Display Facebook?
                        </div>
                        <div class="col-sm-6">
                            <div id="user_display_facebook"><span id="user_display_facebook_status">@if ($profile->facebook) Yes @else No @endif <a href="#" class="glyphicon glyphicon-pencil userSetting_EditA">Edit</a></div>
                            <div id="user_display_facebook_edit">
                                <div class="userSetting_info">Show Facebook link in your profile card</div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group" id="user_display_facebook_radio">
                                            <label class="col-sm-3 radio-inline">
                                                <input type="radio" name="user_display_facebook" value="Yes" @if ($profile->facebook == true) checked="checked" @endif> Yes
                                            </label>
                                            <label class="col-sm-3 radio-inline">
                                                <input type="radio" name="user_display_facebook" value="No" @if ($profile->facebook == false) checked="checked" @endif> No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row userEdit_buttonGroup">
                                    <div class="col-sm-12">
                                        <button class="btn btn-primary btn-sm" type="submit">Save</button>
                                        <a href="#" class="userEdit_cancelButton">Cancel</a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                {{ Form::close()}}
            </div>

            <div class="userEdit_item">
                {{ Form::open(['url' => '#',
                            'data-toggle' => "validator",
                            'role' => "form",
                            'onsubmit' => 'return saveUserDisplayEmail()']) }}
                <div class="row">
                    <div class="col-sm-offset-1 col-sm-2">
                        Display Email?
                    </div>
                    <div class="col-sm-6">
                        <div id="user_display_email"><span id="user_display_email_status">@if ($profile->email) Yes @else No @endif</span> <a href="#" class="glyphicon glyphicon-pencil userSetting_EditA">Edit</a></div>
                        <div id="user_display_email_edit">
                            <div class="userSetting_info">Show Email link in your profile card</div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group" id="user_display_email_radio">
                                        <label class="col-sm-3 radio-inline">
                                            <input type="radio" name="user_display_email" id="inlineRadio2" value="Yes"> Yes
                                        </label>
                                        <label class="col-sm-3 radio-inline">
                                            <input type="radio" name="user_display_email" id="inlineRadio2" value="No"> No
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row userEdit_buttonGroup">
                                <div class="col-sm-12">
                                    <button class="btn btn-primary btn-sm" type="submit">Save</button>
                                    <a href="#" class="userEdit_cancelButton">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{ Form::close()}}
            </div>


            <div class="userEdit_item">
                {{ Form::open(['url' => '#',
                            'data-toggle' => "validator",
                            'role' => "form",
                            'onsubmit' => 'return saveUserBio()']) }}
                <div class="row">
                    <div class="col-sm-offset-1 col-sm-2">
                        Your Bio <div class="userEdit_info">
                            (One sentence)
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div id="user_bio">
                            <span id="user_bio_status">{{ $profile->bio }}</span> <a href="#" class="glyphicon glyphicon-pencil userSetting_EditA">Edit</a>
                        </div>
                        <div id="user_bio_edit">
                            <div class="row">
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" placeholder="Text input">
                                    <div class="userSetting_info">e.g Programmer, Student, Teacher</div>
                                </div>
                            </div>
                            <div class="row userEdit_buttonGroup">
                                <div class="col-sm-12">
                                    <button class="btn btn-primary btn-sm" type="submit">Save</button>
                                    <a href="#" class="userEdit_cancelButton">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{ Form::close()}}
            </div>

            <div class="userEdit_item">
                {{ Form::open(['url' => '#',
                            'data-toggle' => "validator",
                            'role' => "form",
                            'onsubmit' => 'return saveUserIntro()']) }}
                <div class="row">
                    <div class="col-sm-offset-1 col-sm-2">
                        Self description
                    </div>
                    <div class="col-sm-6">
                        <div id="user_self_intro">
                            <span id="user_self_intro_status">{{ $profile->description }}</span><a href="#" class="glyphicon glyphicon-pencil userSetting_EditA">Edit</a>
                        </div>
                        <div id="user_self_intro_edit">
                            <div class="row">
                                <div class="col-sm-12">
                                    <textarea class="form-control" rows="3"></textarea>
                                    <div class="userSetting_info">This will display in your profile</div>
                                </div>
                            </div>
                            <div class="row userEdit_buttonGroup">
                                <div class="col-sm-12">
                                    <button class="btn btn-primary btn-sm" type="submit">Save</button>
                                    <a href="#" class="userEdit_cancelButton">Cancel</a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                {{ Form::close()}}
            </div>

            <div class="userEdit_item">
                <div class="row">
                    <div class="col-sm-offset-1 col-sm-2">
                        Education Experience
                    </div>
                    <div class="col-sm-9">
                        <div id="user_education">
                            <a href="#" class="glyphicon glyphicon-pencil">Add</a>
                        </div>
                        <div id="user_education_edit">
                            {{ Form::open(['url' => '#',
                            'data-toggle' => "validator",
                            'role' => "form",
                            'onsubmit' => 'return saveEducationExp()']) }}
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Institution" name="institution" data-error="Institution Field Required" required>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Major (Optional)" name="major">
                                        </div>
                                    </div>
                                </div>
                                <div class="row userEdit_buttonGroup">
                                    <div class="col-sm-12 form-group">
                                        <button type="submit" class="btn btn-primary btn-sm">Save</button>
                                        <a href="#" class="userEdit_cancelButton">Cancel</a>
                                    </div>
                                </div>
                            {{ Form::close() }}
                        </div>

                        <div class="row">
                            <ul class="userEdit_itemList" id="user_education_list">
                                @foreach($user->educationExps as $educationExp)
                                    <li id="educationExp{{ $educationExp->id }}">
                                        <div class="userEdit_itemListDetail">
                                            <div class="userEdit_itemListDetailImage">
                                                <img src="topic.png" class="img-rounded" />
                                            </div>
                                            <div class="userEdit_itemListDetailContent">
                                                <a href="#">{{ $educationExp->full_name }}</a>
                                            </div>
                                        </div>
                                        <a href="#" class="glyphicon glyphicon-trash userSetting_EditA userEdit_itemListDetailButton" onclick="detachEducationExp(event, '{{ $educationExp->id }}')">Delete</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>


            <div class="userEdit_item">
                <div class="row">
                    <div class="col-sm-offset-1 col-sm-2">
                        Working Experience
                    </div>
                    <div class="col-sm-9">
                        <div id="user_job">
                            <a href="#" class="glyphicon glyphicon-pencil">Add</a>
                        </div>
                        {{ Form::open(['url' => '#',
                        'data-toggle' => "validator",
                        'role' => "form",
                        'onsubmit' => 'return saveJob()']) }}
                        <div id="user_job_edit">
                            <div class="row">
                                <div class="col-sm-6">
                                    <input type="text" name="job_name" class="form-control" placeholder="e.g student">
                                </div>
                            </div>
                            <div class="row userEdit_buttonGroup">
                                <div class="col-sm-12">
                                    <button class="btn btn-primary btn-sm" type="submit">Save</button>
                                    <a href="#" class="userEdit_cancelButton">Cancel</a>
                                </div>
                            </div>
                        </div>
                        {{ Form::close()}}

                        <div class="row">
                            <ul class="userEdit_itemList" id="user_job_list">
                                @foreach($user->jobs as $job)
                                    <li id="job{{ $job->id }}">
                                        <div class="userEdit_itemListDetail">
                                            <div class="userEdit_itemListDetailImage">
                                                <img src="topic.png" class="img-rounded" />
                                            </div>
                                            <div class="userEdit_itemListDetailContent">
                                                <a href="#">{{ $job->full_name }}</a>
                                            </div>
                                        </div>
                                        <a href="#" class="glyphicon glyphicon-trash userSetting_EditA userEdit_itemListDetailButton" onclick="detachJob(event, '{{ $job->id }}')">Delete</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                {{ Form::close()}}
            </div>

            <div class="userEdit_item">
                <div class="row">
                    <div class="col-sm-offset-1 col-sm-2">
                        Good at topics
                    </div>
                    <div class="col-sm-9">
                        <div id="user_specialization">
                            <a href="#" class="glyphicon glyphicon-pencil">Add</a>
                        </div>
                        <div id="user_specialization_edit">
                            {{ Form::open(['url' => '#',
                            'data-toggle' => "validator",
                            'role' => "form",
                            'onsubmit' => 'return saveSpecialization()']) }}
                            <div class="row">
                                <div class="col-sm-6">
                                    <input type="text" name="specialization_name" class="form-control" placeholder="e.g Computing">
                                </div>
                            </div>
                            <div class="row userEdit_buttonGroup">
                                <div class="col-sm-12">
                                    <button class="btn btn-primary btn-sm" type="submit">Save</button>
                                    <a href="#" class="userEdit_cancelButton">Cancel</a>
                                </div>
                            </div>
                            {{ Form::close()}}
                        </div>
                        <div class="row">
                            <ul class="userEdit_itemList" id="user_specialization_list">
                                @foreach($user->specializations as $specialization)
                                    <li id="specialization{{ $specialization->id }}">
                                        <div class="userEdit_itemListDetail">
                                            <div class="userEdit_itemListDetailImage">
                                                <img src="topic.png" class="img-rounded" />
                                            </div>
                                            <div class="userEdit_itemListDetailContent">
                                                <a href="#">{{ $specialization->full_name }}</a>
                                            </div>
                                        </div>
                                        <a href="#" class="glyphicon glyphicon-trash userSetting_EditA userEdit_itemListDetailButton" onclick="detachSpecialization(event, '{{ $specialization->id }}')">Delete</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="hide" id="itemContent">
                <li>
                    <div class="userEdit_itemListDetail">
                        <div class="userEdit_itemListDetailImage">
                            <img src="#" class="img-rounded" id="itemContent_img" />
                        </div>
                        <div class="userEdit_itemListDetailContent">
                            <a href="#" id="itemContent_title"></a>
                        </div>
                    </div>
                    <a href="#" class="glyphicon glyphicon-trash userSetting_EditA userEdit_itemListDetailButton" id="itemContent_delete">Delete</a>
                </li>
            </div>

        </div>
    </div>
@endsection