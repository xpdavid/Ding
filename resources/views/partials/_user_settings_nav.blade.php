<div class="container">
    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-tabs userSetting_nav">
                <li role="presentation" class="{{ Request::is('settings/basic') ? 'active' : '' }}">
                    <a href="/settings/basic">Basic Information</a>
                </li>
                <li role="presentation"  class="{{ Request::is('settings/account') ? 'active' : '' }}">
                    <a href="/settings/account">Account &amp; Password</a>
                </li>
                <li role="presentation"  class="{{ Request::is('settings/notification') ? 'active' : '' }}">
                    <a href="/settings/notification">Notification</a>
                </li>
                <li role="presentation"  class="{{ Request::is('settings/blocking') ? 'active' : '' }}">
                    <a href="/settings/blocking">Block</a>
                </li>
            </ul>
        </div>
    </div>
</div>