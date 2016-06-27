@extends('layouts.userCenter')


@section('content')

    <h4 class="font-black">All Invitations ({{ $count }})</h4>
    <hr class="small_hr">

    <div id="invitation">

    </div>

    <br>
    <button class="btn btn-default btn-block" type="button" id="invitation_more" onclick="genericGet('invitation');">More..</button>

@endsection

@section('javascript')
    <script type="text/javascript">
        $(function() {
            genericGet('invitation');
        });
    </script>
@endsection