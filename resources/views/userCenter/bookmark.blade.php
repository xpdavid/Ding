@extends('layouts.userCenter')


@section('content')
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active">
            <a href="#myBookmark" aria-controls="myBookmark" role="tab" data-toggle="tab">
                My bookmark <span class="badge">{{ $myBookmark_count }}</span>
            </a>
        </li>
        <li role="presentation">
            <a href="#subscribedBookmark" aria-controls="subscribedBookmark" role="tab" data-toggle="tab">
                Subscribed bookmark <span class="badge"> {{ $subscribedBookmark_count }}</span>
            </a>
        </li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade in active" id="myBookmark">
            <div id="myBookmark_content">

            </div>

            <div id="myBookmark_nav" class="text-center">

            </div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="subscribedBookmark">
            <div id="subscribedBookmark_content">

            </div>

            <div id="subscribedBookmark_nav" class="text-center">

            </div>
        </div>
    </div>
@endsection


@section('javascript')
<script type="text/javascript">
    $(function() {
        showBookmarkPage('myBookmark', 'user', null, 1, null);
        showBookmarkPage('subscribedBookmark', 'subscribed', null, 1, null);
    });
</script>
@endsection