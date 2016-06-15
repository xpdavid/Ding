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
            <a href="#">Xiao Pu <span>Edit Profile</span></a>
        </div>
        <div class="userHome_layoutDivContent">
            <div class="userEdit_item">
                <div class="row">
                    <div class="col-sm-offset-1 col-sm-2">
                        Your Profile
                    </div>
                    <div class="col-sm-6">
                        <img src="xp_l.jpeg" alt="..." class="img-rounded" />
                    </div>
                </div>
            </div>

            <div class="userEdit_item">
                <div class="row">
                    <div class="col-sm-offset-1 col-sm-2">
                        Gender
                    </div>
                    <div class="col-sm-6">
                        <div id="user_sex">Male <a href="#" class="glyphicon glyphicon-pencil userSetting_EditA">Edit</a></div>
                        <div id="user_sex_edit">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="col-sm-3 radio-inline">
                                            <input type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1"> Secret
                                        </label>
                                        <label class="col-sm-3 radio-inline">
                                            <input type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2"> Male
                                        </label>
                                        <label class="col-sm-3 radio-inline">
                                            <input type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2"> Female
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

            </div>

            <div class="userEdit_item">
                <div class="row">
                    <div class="col-sm-offset-1 col-sm-2">
                        Display Facebook?
                    </div>
                    <div class="col-sm-6">
                        <div id="user_display_facebook">Yes <a href="#" class="glyphicon glyphicon-pencil userSetting_EditA">Edit</a></div>
                        <div id="user_display_facebook_edit">
                            <div class="userSetting_info">Show Facebook link in your profile card</div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="col-sm-3 radio-inline">
                                            <input type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2"> Yes
                                        </label>
                                        <label class="col-sm-3 radio-inline">
                                            <input type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2"> No
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
            </div>

            <div class="userEdit_item">
                <div class="row">
                    <div class="col-sm-offset-1 col-sm-2">
                        Display Email?
                    </div>
                    <div class="col-sm-6">
                        <div id="user_display_email">Yes <a href="#" class="glyphicon glyphicon-pencil userSetting_EditA">Edit</a></div>
                        <div id="user_display_email_edit">
                            <div class="userSetting_info">Show Email link in your profile card</div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="col-sm-3 radio-inline">
                                            <input type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2"> Yes
                                        </label>
                                        <label class="col-sm-3 radio-inline">
                                            <input type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2"> No
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
            </div>


            <div class="userEdit_item">
                <div class="row">
                    <div class="col-sm-offset-1 col-sm-2">
                        Your Bio <div class="userEdit_info">
                            (One sentence)
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div id="user_bio">
                            I am a programmer! <a href="#" class="glyphicon glyphicon-pencil userSetting_EditA">Edit</a>
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
            </div>

            <div class="userEdit_item">
                <div class="row">
                    <div class="col-sm-offset-1 col-sm-2">
                        Self description
                    </div>
                    <div class="col-sm-6">
                        <div id="user_self_intro">
                            I am a programmer! <a href="#" class="glyphicon glyphicon-pencil userSetting_EditA">Edit</a>
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
            </div>

            <div class="userEdit_item">
                <div class="row">
                    <div class="col-sm-offset-1 col-sm-2">
                        Eduction Experience
                    </div>
                    <div class="col-sm-9">
                        <div id="user_education">
                            <a href="#" class="glyphicon glyphicon-pencil">Add</a>
                        </div>
                        <div id="user_education_edit">
                            <div class="row">
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" placeholder="e.g NUS">
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" placeholder="e.g NUS">
                                </div>
                            </div>
                            <div class="row userEdit_buttonGroup">
                                <div class="col-sm-12">
                                    <button class="btn btn-primary btn-sm" type="submit">Save</button>
                                    <a href="#" class="userEdit_cancelButton">Cancel</a>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <ul class="userEdit_itemList">
                                <li>
                                    <div class="userEdit_itemListDetail">
                                        <div class="userEdit_itemListDetailImage">
                                            <img src="topic.png" class="img-rounded" />
                                        </div>
                                        <div class="userEdit_itemListDetailContent">
                                            <a href="#">National University of Singapore</a>
                                        </div>
                                    </div>
                                    <a href="#" class="glyphicon glyphicon-trash userSetting_EditA userEdit_itemListDetailButton">Delete</a>
                                </li>
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
                        <div id="user_job_edit">
                            <div class="row">
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" placeholder="e.g NUS">
                                </div>
                            </div>
                            <div class="row userEdit_buttonGroup">
                                <div class="col-sm-12">
                                    <button class="btn btn-primary btn-sm" type="submit">Save</button>
                                    <a href="#" class="userEdit_cancelButton">Cancel</a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <ul class="userEdit_itemList">
                                <li>
                                    <div class="userEdit_itemListDetail">
                                        <div class="userEdit_itemListDetailImage">
                                            <img src="topic.png" class="img-rounded" />
                                        </div>
                                        <div class="userEdit_itemListDetailContent">
                                            <a href="#">PaperBaton</a>
                                        </div>
                                    </div>
                                    <a href="#" class="glyphicon glyphicon-trash userSetting_EditA userEdit_itemListDetailButton">Delete</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
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
                            <div class="row">
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" placeholder="e.g NUS">
                                </div>
                            </div>
                            <div class="row userEdit_buttonGroup">
                                <div class="col-sm-12">
                                    <button class="btn btn-primary btn-sm" type="submit">Save</button>
                                    <a href="#" class="userEdit_cancelButton">Cancel</a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <ul class="userEdit_itemList">
                                <li>
                                    <div class="userEdit_itemListDetail">
                                        <div class="userEdit_itemListDetailImage">
                                            <img src="topic.png" class="img-rounded" />
                                        </div>
                                        <div class="userEdit_itemListDetailContent">
                                            <a href="#">Computer Sicence</a>
                                        </div>
                                    </div>
                                    <a href="#" class="glyphicon glyphicon-trash userSetting_EditA userEdit_itemListDetailButton">Delete</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection