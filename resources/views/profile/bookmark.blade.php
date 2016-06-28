@extends('layouts.profile')

@section('content')
    @include('partials._profile_card', ['user' => $user])


    <!-- My Question -->
    <div class="userHome_layoutDiv">
        <div class="userHome_layoutDivHead">
            <a href="/people/{{$user->url_name}}" class="float-left">{{ $user->name }}</a> 's Bookmark
            <a href="/people/{{$user->url_name}}" class="float-right">&lt;&lt; Back to the index</a>
        </div>
        <div class="userHome_layoutDivContent" id="bookmark_0_content">

        </div>
        <div class="text-center" id="bookmark_0_nav">

        </div>
    </div>

@endsection

@section('javascript')
    <script type="text/javascript">
        $(function() {
            showBookmarkPage(0, null, '{{ $user->id }}', 1, null);
        });
    </script>
@endsection