@extends('layouts.settings')

@section('content')
    @include('partials._user_settings_nav')
    <div class="container userSetting_content" >
        <form class="form-horizontal" method="POST" action="/settings/update/{{ $user->id }}">
            {{ csrf_field() }}
            <div class="row userSetting_section">
                <div class="col-md-12">
                    <h4>Message Settings</h4>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Receiving messages: </label>
                        <label class="col-sm-3 radio-inline">
                            <input class="active" type="radio" name="receiving_messages" id="inlineRadio1" value="2"
                                   @if ($settings->receiving_messages == 2) checked="checked" @endif> Everyone can message me
                        </label>
                        <label class="col-sm-5 radio-inline">
                            <input type="radio" name="receiving_messages" id="inlineRadio2" value="1"
                                   @if ($settings->receiving_messages == 1) checked="checked" @endif> Only people I subscribe to can messages me
                        </label>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-offset-3 col-sm-4 checkbox-inline">
                            <input type="checkbox" name="email_messages" id="inlineCheckbox1" value="1"
                                   @if ($settings->email_messages) checked="checked" @endif> Email notification of new messages
                        </label>
                    </div>
                </div>
            </div>

            <div class="row userSetting_section">
                <div class="col-md-12">
                    <h4>Notification Setting</h4>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Invitation to answer questions: </label>
                        <label class="col-sm-3 radio-inline">
                            <input type="radio" name="receiving_invitations" id="inlineRadio1" value="2"
                                   @if ($settings->receiving_invitations == 2) checked="checked" @endif> Everyone can invite me
                        </label>
                        <label class="col-sm-5 radio-inline">
                            <input type="radio" name="receiving_invitations" id="inlineRadio2" value="1"
                                   @if ($settings->receiving_invitations == 1) checked="checked" @endif> Only people I subscribe to can invite me.
                        </label>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-offset-3 col-sm-4 checkbox-inline">
                            <input type="checkbox" name="email_invitations" id="inlineCheckbox1" value="1"
                                   @if ($settings->email_invitations) checked="checked" @endif> Email notification of new invitations
                        </label>
                    </div>
                </div>
            </div>

            <div class="row userSetting_section">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Updates to subscribed questions: </label>
                        <label class="col-sm-3 radio-inline">
                            <input type="radio" name="receiving_updates" id="inlineRadio1" value="2"
                                   @if ($settings->receiving_updates == 2) checked="checked" @endif> Receive all updates
                        </label>
                        <label class="col-sm-5 radio-inline">
                            <input type="radio" name="receiving_updates" id="inlineRadio2" value="1"
                                   @if ($settings->receiving_updates == 1) checked="checked" @endif> Only receive updates from people I subscribed to
                        </label>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-offset-3 col-sm-4 checkbox-inline">
                            <input type="checkbox" name="email_updates" id="inlineCheckbox1" value="1"
                                   @if ($settings->email_updates) checked="checked" @endif> Email notification of updates
                        </label>
                    </div>
                </div>
            </div>

            <div class="row userSetting_section">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Replying me: </label>
                        <label class="col-sm-2 radio-inline">
                            <input type="radio" name="receiving_replies" id="inlineRadio1" value="2"
                                   @if ($settings->receiving_replies == 2) checked="checked" @endif> Everyone can reply me
                        </label>
                        <label class="col-sm-3 radio-inline">
                            <input type="radio" name="receiving_replies" id="inlineRadio2" value="1"
                                   @if ($settings->receiving_replies == 1) checked="checked" @endif> Only people I subscribe to can reply me.
                        </label>
                        <label class="col-sm-3 radio-inline">
                            <input type="radio" name="receiving_replies" id="inlineRadio2" value="0"
                                   @if ($settings->receiving_replies == 0) checked="checked" @endif> No one can reply me
                        </label>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-offset-3 col-sm-4 checkbox-inline">
                            <input type="checkbox" name="email_replies" id="inlineCheckbox1" value="1"
                                   @if ($settings->email_replies) checked="checked" @endif> Email notification of new replies
                        </label>
                    </div>
                </div>
            </div>

            <div class="row userSetting_section">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Votings of my answers: </label>
                        <label class="col-sm-2 radio-inline">
                            <input type="radio" name="receiving_votings" id="inlineRadio1" value="2"
                                   @if ($settings->receiving_votings == 2) checked="checked" @endif> Everyone can vote for my answers
                        </label>
                        <label class="col-sm-3 radio-inline">
                            <input type="radio" name="receiving_votings" id="inlineRadio2" value="1"
                                   @if ($settings->receiving_votings == 1) checked="checked" @endif> Only people I subscribe to can vote for my answers
                        </label>
                        <label class="col-sm-3 radio-inline">
                            <input type="radio" name="receiving_votings" id="inlineRadio2" value="0"
                                   @if ($settings->receiving_votings == 0) checked="checked" @endif> No one can vote for my answers
                        </label>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-offset-3 col-sm-4 checkbox-inline">
                            <input type="checkbox" name="email_votings" id="inlineCheckbox1" value="1"
                                   @if ($settings->email_votings) checked="checked" @endif> Email notification of new answers
                        </label>
                    </div>
                </div>
            </div>

            <div class="row userSetting_section">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Voting for my replies: </label>
                        <label class="col-sm-2 radio-inline">
                            <input type="radio" name="receiving_reply_votings" id="inlineRadio1" value="2"
                                   @if ($settings->receiving_reply_votings == 2) checked="checked" @endif> Everyone can vote
                        </label>
                        <label class="col-sm-3 radio-inline">
                            <input type="radio" name="receiving_reply_votings" id="inlineRadio2" value="1"
                                   @if ($settings->receiving_reply_votings == 1) checked="checked" @endif> Only people I subscribe to can vote
                        </label>
                        <label class="col-sm-3 radio-inline">
                            <input type="radio" name="receiving_reply_votings" id="inlineRadio2" value="0"
                                   @if ($settings->receiving_reply_votings == 0) checked="checked" @endif> No one can vote
                        </label>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-offset-3 col-sm-4 checkbox-inline">
                            <input type="checkbox" name="email_reply_votings" id="inlineCheckbox1" value="1"
                                   @if ($settings->email_reply_votings) checked="checked" @endif> Email notification of votings
                        </label>
                    </div>

                </div>
            </div>

            <div class="row userSetting_section">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Subscribing to me: </label>
                        <label class="col-sm-2 radio-inline">
                            <input type="radio" name="receiving_subscriptions" id="inlineRadio1" value="2"
                                   @if ($settings->receiving_subscriptions == 2) checked="checked" @endif> Everyone can subscribe to me
                        </label>
                        <label class="col-sm-3 radio-inline">
                            <input type="radio" name="receiving_subscriptions" id="inlineRadio2" value="1"
                                   @if ($settings->receiving_subscriptions == 1) checked="checked" @endif> Only people I subscribe to can
                        </label>
                        <label class="col-sm-3 radio-inline">
                            <input type="radio" name="receiving_subscriptions" id="inlineRadio2" value="0"
                                   @if ($settings->receiving_subscriptions == 0) checked="checked" @endif> No one can subscribe to me
                        </label>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-offset-3 col-sm-4 checkbox-inline">
                            <input type="checkbox" name="email_subscriptions" id="inlineCheckbox1" value="1"
                                   @if ($settings->email_subscriptions) checked="checked" @endif> Email notification of new subscription
                        </label>
                    </div>
                </div>
            </div>

            <div class="row userSetting_section">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-4">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('javascript')

@endsection