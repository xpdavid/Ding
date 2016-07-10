@extends('layouts.profile')

@section('content')
    @include('partials._profile_card', ['user' => $user])


    <!-- My bookmark -->
    <div class="userHome_layoutDiv">
        <div class="userHome_layoutDivHead">
            <a href="/people/{{$user->url_name}}" class="float-left">{{ $user->name }}</a> 's Bookmark
            <a href="/people/{{$user->url_name}}" class="float-right">&lt;&lt; Back to the index</a>
        </div>
        <div class="userHome_layoutDivContent" id="bookmark_content">

        </div>
        <div class="text-center" id="bookmark_nav">

        </div>
    </div>

@endsection

@section('side')
    @include('partials._user_home_sidebar', ['user' => $user])
@endsection

@section('javascript')
    <script type="text/javascript">
        $(function() {
            showBookmarkPage('bookmark', null, '{{ $user->id }}', 1, null);
        });
    </script>
@endsection