@extends('layouts.settings')

@section('content')

<div class="container">
  <div class="row">
      <div class="col-md-12">
        <ul class="nav nav-tabs userSetting_nav">
          <li role="presentation" class="active"><a data-toggle="tab" href="#tab1">Basic Information</a></li>
          <li role="presentation"><a data-toggle="tab" href="#tab2">Account &amp; Password</a></li>
          <li role="presentation"><a data-toggle="tab" href="#tab3">Notification</a></li>
          <li role="presentation"><a data-toggle="tab" href="#tab4">Block</a></li>
        </ul>
      </div>
  </div>
</div>

<div class="tab-content">
	<div class="container userSetting_content tab-pane fade in active" id="tab1">
	  <form role="form" data-toggle="validator" method="POST" action="/settings/{{ $user->id }}" class="form-horizontal" id="form1">
	  	{{ method_field('PATCH')}}
	  	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	    <div class="row userSetting_section">
	      <div class="col-md-8">
	        <div class="form-group">
	            <label class="col-sm-2 control-label">Name:</label>
	            <label class="col-sm-10 control-label userSetting_contentLabelLeft">{{ $user->name }}<a href="#nameChange" class="glyphicon glyphicon-pencil userSetting_EditA">Edit</a></label>
	        </div>
	        <div class="form-group">
	          <div class="col-sm-offset-2 col-sm-9 userSetting_EditText">
	              <input type="text" class="form-control" name="name" id="nameChange" placeholder="{{ $user->name}}" data-minlength="6">
				  <div class="help-block with-errors"></div>	             
	          </div>
	        </div>
	      </div>
	    </div>
	    <div class="row userSetting_section">
	      <div class="col-md-8">
	        <div class="form-group">
	            <label class="col-sm-2 control-label">Your domain:</label>
	            <div class="col-sm-6">
	              <div class="input-group">
	                <div class="input-group-addon">http://nusding.com/</div>
	                <input type="text" class="form-control" name="personal_domain" placeholder="{{ $settings->personal_domain }}"
	                @if($settings->personal_domain_modified) 
	                	disabled
	                @endif
	                >
	                
	              </div>
	            </div>
	            
	            <div class="col-sm-4">
	               <span class="userSetting_info">Permanent once edited</span>
	            </div>
	        </div>
	      </div>
	    </div>

		@if (count($errors) > 0)
		    <div class="alert alert-danger">
		        <ul>
		            @foreach ($errors->all() as $error)
		                <li>{{ $error }}</li>
		            @endforeach
		        </ul>
		    </div>
		@endif

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

	<div class="container userSetting_content tab-pane fade" id="tab2">
	  <div class="row userSetting_section">
	    <div class="col-md-12">
	      <div class="userSetting_info">
	      Change your password and manage other login methods
	      </div>
	    </div>
	  </div>
	  <form method="POST" action="/settings/{{ $user->id }}" data-toggle="validator" role="form" class="form-horizontal" id="form2">
	  	{{ method_field('PATCH')}}
	  	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	    <div class="row userSetting_section">
	      <div class="col-md-8">
	        <div class="form-group">
	          <label for="inputEmail3" class="col-sm-3 control-label userSetting_contentLabelLeft">Old Password</label>
	          <div class="col-sm-5">
	            <input type="password" class="form-control" name="old_password" placeholder="Old Password">
	          </div>
	        </div>
	        <div class="form-group">
	          <label for="inputEmail3" class="col-sm-3 control-label userSetting_contentLabelLeft">New Password</label>
	          <div class="col-sm-5">
	            <input type="password" class="form-control" name="new_password" id="newPassword" placeholder="New Password" data-minlength="6">
	            <div class="help-block with-errors"></div>
	          </div>
	        </div>
	        <div class="form-group">
	          <label for="inputEmail3" class="col-sm-3 control-label userSetting_contentLabelLeft">Confirm Password</label>
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
	        <div class="form-group">
	            <div class="col-sm-offset-3 col-sm-10">
	              <button type="submit" class="btn btn-primary">Change</button>
	            </div>
	        </div>
	      </div>
	    </div>
	  </form>
	</div>
	
	<div class="container userSetting_content tab-pane fade" id="tab3">
	  <form class="form-horizontal" method="POST" action="/settings/{{ $user->id }}">
	  	{{ method_field('PATCH')}}
	  	<input type="hidden" name="_token" value="{{ csrf_token() }}">
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
	              @if ($settings->email_messages == true) checked="checked" @endif> Email notification of new messages
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
	              @if ($settings->email_invitations == true) checked="checked" @endif> Email notification of new invitations
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
	              @if ($settings->email_updates == true) checked="checked" @endif> Email notification of updates
	            </label>
	        </div>
	      </div>
	    </div>
	      
	    <div class="row userSetting_section">
	      <div class="col-md-12">
	        <div class="form-group">
	            <label class="col-sm-3 control-label">@ / Replying me: </label>
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
	              @if ($settings->email_replies == true) checked="checked" @endif> Email notification of new replies
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
	              @if ($settings->email_votings == true) checked="checked" @endif> Email notification of new answers
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
	              @if ($settings->email_reply_votings == true) checked="checked" @endif> Email notification of votings
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
	              @if ($settings->email_subscriptions == true) checked="checked" @endif> Email notification of new subscription
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

	<div class="container userSetting_content tab-pane fade" id="tab4">
	  <form class="form-horizontal" method="POST" action="/settings/{{ $user->id }}" data-toggle="validator" id="form4">
	  	{{ method_field('PATCH')}}
	  	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	    <div class="row userSetting_section">
	      <div class="col-md-8">
	        <div class="form-group">
	            <label for="inputEmail3" class="col-sm-2 control-label">Block User</label>
	            <div class="col-sm-5">
	              <input type="email" class="form-control" name="email_to_be_blocked" id="blockUserInput" placeholder="Email">
	              <div class="help-block with-errors"></div>	             
	            </div>
	            <div class="col-sm-5">
	              <div class="userSetting_info">
	                You will not see anything about these user
	              </div>
	            </div>
	        </div>

	        <div class="form-group">
	            <div class="col-sm-offset-2 col-sm-10 userSetting_Item">
	            @foreach ($blockings as $blocking)
					<a href="http://nusding.com/{{App\User::find($blocking->blocked_id)->settings->personal_domain}}">{{App\User::find($blocking->blocked_id)->name}}</a>
				@endforeach
	            </div>
	        </div>
	      </div>
	    </div>

	    <div class="row userSetting_section">
	      <div class="col-md-8">
	        <div class="form-group">
	            <div class="col-sm-offset-2 col-sm-10">
	              <button type="submit" class="btn btn-primary" id="btn_form4">Save</button>
	            </div>
	        </div>
	      </div>
	    </div>
	  </form>
	</div>

</div>
	
@endsection