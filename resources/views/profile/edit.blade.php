@extends('layouts.profile')

@section('content')
    @include('partials._crop_image_model', [
        'url' => '/people/upload',
        'image' => DImage($settings->profile_pic_id),
        'id' => '',
        'type' => ''
    ])

    <div class="userHome_layoutDiv">
        <div class="userHome_profileHeader">
            <div class="row">
                <div class="col-md-3 userEdit_completeBar">
                    Profile Complete
                </div>
                <div class="col-md-9">
                    <div class="progress userEdit_completeBar">
                        <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $progress }}%;">
                            {{ $progress }}%
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- My Profile Edit -->
    <div class="userHome_layoutDiv">
        <div class="userHome_layoutDivHead">
            <a href="/people/{{ $user->url_name }}">{{ $user->name }} <span>Edit Profile</span></a>
        </div>
        <div class="userHome_layoutDivContent">
            <div class="userEdit_item">
                <div class="row">
                    <div class="col-sm-offset-1 col-sm-2">
                        Your Profile
                    </div>
                    <div class="col-sm-6">
                        <img src="{{ DImage($settings->profile_pic_id, 100, 100) }}" alt="{{ $user->name }}" class="img-rounded" />
                        <!-- Button trigger crop modal -->
                        <div class="margin-top">
                            <button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#crop_image">
                                Upload New Image
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="userEdit_item" id="user_sex_layout">
                <div class="row">
                    <div class="col-sm-offset-1 col-sm-2">
                        Sex
                    </div>
                    <div class="col-sm-6">
                        <div id="user_sex"><span id="user_sex_status">{{ $user->sex }}</span>
                            <a href="#" class="glyphicon glyphicon-pencil userSetting_EditA" id="user_sex_edit_button">Edit</a></div>
                        <div id="user_sex_edit" class="noneDisplay">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group" id="user_sex_radio">
                                            <label class="col-sm-3 radio-inline">
                                                <input type="radio" name="user_sex" value="Secret"
                                                        {{ $user->sex == 'Secret' ? 'checked' : '' }}> Secret
                                            </label>
                                            <label class="col-sm-3 radio-inline">
                                                <input type="radio" name="user_sex" value="Male"
                                                        {{ $user->sex == 'Male' ? 'checked' : '' }} > Male
                                            </label>
                                            <label class="col-sm-3 radio-inline">
                                                <input type="radio" name="user_sex" value="Female"
                                                        {{ $user->sex == 'Female' ? 'checked' : '' }}> Female
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row userEdit_buttonGroup">
                                    <div class="col-sm-12 user_sex_btn">
                                        <button class="btn btn-primary btn-sm" type="button" onclick="saveUserSex()">Save</button>
                                        <a href="#" class="userEdit_cancelButton">Cancel</a>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="userEdit_item" id="user_display_facebook_layout">
                    <div class="row">
                        <div class="col-sm-offset-1 col-sm-2">
                            Display Facebook?
                        </div>
                        <div class="col-sm-6">
                            <div id="user_display_facebook">
                                <span id="user_display_facebook_status">
                                    {{ $settings->display_facebook ? 'Yes' : 'No' }}
                                </span>
                                <a href="#" class="glyphicon glyphicon-pencil userSetting_EditA" id="user_display_facebook_edit_button">Edit</a>
                            </div>
                            <div id="user_display_facebook_edit" class="noneDisplay">
                                <div class="userSetting_info">Show Facebook link in your profile card</div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group" id="user_display_facebook_radio">
                                            <label class="col-sm-3 radio-inline">
                                                <input type="radio" name="user_display_facebook" value="Yes" {{ $settings->display_facebook ? 'checked' : '' }}> Yes
                                            </label>
                                            <label class="col-sm-3 radio-inline">
                                                <input type="radio" name="user_display_facebook" value="No" {{ !$settings->display_facebook ? 'checked' : '' }}> No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row userEdit_buttonGroup">
                                    <div class="col-sm-12">
                                        <button class="btn btn-primary btn-sm" type="button" onclick="saveUserDisplayFacebook()">Save</button>
                                        <a href="#" class="userEdit_cancelButton">Cancel</a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
            </div>

            <div class="userEdit_item" id="user_display_email_layout">
                <div class="row">
                    <div class="col-sm-offset-1 col-sm-2">
                        Display Email?
                    </div>
                    <div class="col-sm-6">
                        <div id="user_display_email"><span id="user_display_email_status">{{ $settings->display_email ? 'Yes' : 'No' }}</span>
                            <a href="#" class="glyphicon glyphicon-pencil userSetting_EditA" id="user_display_email_edit_button">Edit</a>
                        </div>
                        <div id="user_display_email_edit" class="noneDisplay">
                            <div class="userSetting_info">Show Email link in your profile card</div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group" id="user_display_email_radio">
                                        <label class="col-sm-3 radio-inline">
                                            <input type="radio" name="user_display_email" id="inlineRadio2" value="Yes" {{ $settings->display_email ? 'checked' : '' }}> Yes
                                        </label>
                                        <label class="col-sm-3 radio-inline">
                                            <input type="radio" name="user_display_email" id="inlineRadio2" value="No" {{ !$settings->display_email ? 'checked' : '' }}> No
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row userEdit_buttonGroup">
                                <div class="col-sm-12">
                                    <button class="btn btn-primary btn-sm" type="button" onclick="saveUserDisplayEmail()">Save</button>
                                    <a href="#" class="userEdit_cancelButton">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="userEdit_item" id="user_bio_layout">
                <div class="row">
                    <div class="col-sm-offset-1 col-sm-2">
                        Your Bio <div class="userEdit_info">
                            (One sentence) (Optional)
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div id="user_bio">
                            <span id="user_bio_status">{{ $user->bio }}</span>
                            <a href="#" class="glyphicon glyphicon-pencil userSetting_EditA" id="user_bio_edit_button">Edit</a>
                        </div>
                        <div id="user_bio_edit" class="noneDisplay">
                            <div class="row">
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="user_bio_input" placeholder="e.g Programmer, Student, Teacher" value="{{ $user->bio }}">
                                    <div class="userSetting_info">Let more people know about you!</div>
                                </div>
                            </div>
                            <div class="row userEdit_buttonGroup">
                                <div class="col-sm-12">
                                    <button class="btn btn-primary btn-sm" type="button" onclick="saveUserBio()">Save</button>
                                    <a href="#" class="userEdit_cancelButton">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="userEdit_item" id="user_self_intro_layout">
                <div class="row">
                    <div class="col-sm-offset-1 col-sm-2">
                        Self description
                    </div>
                    <div class="col-sm-6">
                        <div id="user_self_intro">
                            <span id="user_self_intro_status">{{ $user->self_intro }}</span>
                            <a href="#" class="glyphicon glyphicon-pencil userSetting_EditA" id="user_self_intro_edit_button">Edit</a>
                        </div>
                        <div id="user_self_intro_edit" class="noneDisplay">
                            <div class="row">
                                <div class="col-sm-12">
                                    <textarea class="form-control" rows="3" id="user_self_intro_input">{{ $user->self_intro }}</textarea>
                                    <div class="userSetting_info">This will display in your profile</div>
                                </div>
                            </div>
                            <div class="row userEdit_buttonGroup">
                                <div class="col-sm-12">
                                    <button class="btn btn-primary btn-sm" type="button" onclick="saveUserIntro()">Save</button>
                                    <a href="#" class="userEdit_cancelButton">Cancel</a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="userEdit_item">
                <div class="row">
                    <div class="col-sm-offset-1 col-sm-2">
                        Education Experience
                    </div>
                    <div class="col-sm-9">
                        <div id="user_education" >
                            <a href="#" class="glyphicon glyphicon-pencil">Add</a>
                        </div>
                        <div id="user_education_edit" class="noneDisplay">
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
                                        <button type="button" onclick="saveEducationExp()" class="btn btn-primary btn-sm">Save</button>
                                        <a href="#" class="userEdit_cancelButton">Cancel</a>
                                    </div>
                                </div>
                        </div>

                        <div class="row">
                            <ul class="userEdit_itemList" id="user_education_list">
                                @foreach($user->educationExps as $educationExp)
                                    <li id="educationExp_{{ $educationExp->id }}">
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
                        <div id="user_job_edit" class="noneDisplay">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Organization" name="organization" data-error="Organization Name Required" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Designation" name="designation" data-error="Designation Required" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row userEdit_buttonGroup">
                                <div class="col-sm-12">
                                    <button class="btn btn-primary btn-sm" type="button" onclick="saveJob()">Save</button>
                                    <a href="#" class="userEdit_cancelButton">Cancel</a>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <ul class="userEdit_itemList" id="user_job_list">
                                @foreach($user->jobs as $job)
                                    <li id="job_{{ $job->id }}">
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
                        Specializations
                    </div>
                    <div class="col-sm-9">
                        <div id="user_specialization">
                            <a href="#" class="glyphicon glyphicon-pencil">Add</a>
                        </div>
                        <div id="user_specialization_edit" class="noneDisplay">
                            <div class="row">
                                <div class="col-sm-6">
                                    <select class="form-control"
                                            multiple="multiple"
                                            tabindex="-1"
                                            aria-hidden="true"
                                            name="specializations[]"
                                            id="specializations"
                                    >
                                    </select>
                                    <p class="font-greyLight">We will recommend questions for you based on your specializations</p>
                                </div>
                            </div>
                            <div class="row userEdit_buttonGroup">
                                <div class="col-sm-12">
                                    <button class="btn btn-primary btn-sm" type="submit" onclick="saveSpecialization()">Save</button>
                                    <a href="#" class="userEdit_cancelButton">Cancel</a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <ul class="userEdit_itemList" id="user_specialization_list">
                                @foreach($user->specializations as $specialization)
                                    <li id="specialization_{{ $specialization->id }}">
                                        <div class="userEdit_itemListDetail">
                                            <div class="userEdit_itemListDetailImage">
                                                <img src="{{ DImage($specialization->avatar_img_id, 50, 50) }}" class="img-rounded" />
                                            </div>
                                            <div class="userEdit_itemListDetailContent">
                                                <a href="#">{{ $specialization->name }}</a>
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

        </div>
    </div>
@endsection


@section('javascript')
    <script type="text/javascript">
        $(function() {
            // try bind every div with hover event
            try {
                genericUserProfileEditHover('#user_sex');
                genericUserProfileEditHover('#user_display_facebook');
                genericUserProfileEditHover('#user_display_email');
                genericUserProfileEditHover('#user_bio');
                genericUserProfileEditHover('#user_self_intro');

                // for listing (using li), bind every li with hover event
                genericUserProfileListEditHover('educationExp');
                genericUserProfileListEditHover('job');
                genericUserProfileListEditHover('specialization');

                genericUserProfileEditToggleSetting('#user_sex');
                genericUserProfileEditToggleSetting('#user_display_facebook');
                genericUserProfileEditToggleSetting('#user_display_email');
                genericUserProfileEditToggleSetting('#user_bio');
                genericUserProfileEditToggleSetting('#user_self_intro');
                genericUserProfileEditToggleSetting('#user_education');
                genericUserProfileEditToggleSetting('#user_job');
                genericUserProfileEditToggleSetting('#user_specialization');

                // open tooltipc option
                $('[data-toggle="tooltip"]').tooltip({container: 'body'});

                cropImage('crop_img', 1 / 1);

            } catch (e) {
                console.log('hover event binding fail');
            }
        })
    </script>
@endsection