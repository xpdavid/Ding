@extends('layouts.userCenter')


@section('content')

    <h4 class="font-black">All Subscribed Question ({{ $count }})</h4>
    <hr class="small_hr">

    <div id="subscribed">

    </div>

    <br>
    <button class="btn btn-default btn-block" type="button" id="subscribed_more" onclick="genericGet('subscribed');">More..</button>

@endsection

@section('javascript')
    <script type="text/javascript">
        $(function() {
            genericGet('subscribed');
        });
    </script>
@endsection