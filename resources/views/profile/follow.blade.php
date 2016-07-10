@extends('layouts.profile')

@section('content')
    @include('partials._profile_card', ['user' => $user])


    <div class="userHome_layoutDiv">
        <div class="userHome_layoutDivHead">
            <a href="/people/{{$user->url_name}}" class="float-left">{{ $user->name }}</a> 's Follow
            <a href="/people/{{$user->url_name}}" class="float-right">&lt;&lt; Back to the index</a>
        </div>
        <div class="userHome_layoutDivContent profile_bottom_content" id="follow_content">

        </div>
        <div class="text-center" id="follow_nav">

        </div>
    </div>

@endsection

@section('side')
    @include('partials._user_home_sidebar', ['user' => $user])
@endsection

@section('javascript')
    <script type="text/javascript">
        $(function() {
            showFollowFollowerPage('follow', 'follow', '{{ $user->url_name }}', 1, null);
        });
    </script>
@endsection