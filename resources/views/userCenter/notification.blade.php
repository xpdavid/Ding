@extends('layouts.userCenter')


@section('content')

    <h4 class="font-black">All Notification</h4>

    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#general" aria-controls="general" role="tab" data-toggle="tab">General</a></li>
        <li role="presentation"><a href="#point_detail" aria-controls="point_detail" role="tab" data-toggle="tab">Point</a></li>
    </ul>


    <hr class="small_hr">

    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="general">
            <div id="notification">

            </div>


            <button class="btn btn-default btn-block" type="button" id="notification_more" onclick="genericGet('notification');">More..</button>

        </div>
        <div role="tabpanel" class="tab-pane" id="point_detail">
            <div id="point" class="clearfix">
            </div>


            <button class="btn btn-default btn-block" type="button" id="point_more" onclick="genericGet('point');">More..</button>
        </div>
    </div>
@endsection

@section('javascript')
<script type="text/javascript">
    $(function() {
        genericGet('notification');
        genericGet('point');
    });
</script>
@endsection