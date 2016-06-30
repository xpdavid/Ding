@extends('layouts.search')


@section('left')
    <ul class="nav nav-tabs">
        <li role="presentation" class="{{ $type == 'question' ? 'active' : '' }}">
            <a href="{{ route('search', ['type' => 'question', 'query' => $query]) }}">Question</a>
        </li>
        <li role="presentation" class="{{ $type == 'answer' ? 'active' : '' }}">
            <a href="{{ route('search', ['type' => 'answer', 'query' => $query]) }}">Answer</a>
        </li>
        <li role="presentation" class="{{ $type == 'user' ? 'active' : '' }}">
            <a href="{{ route('search', ['type' => 'user', 'query' => $query]) }}">User</a>
        </li>
        <li role="presentation" class="{{ $type == 'topic' ? 'active' : '' }}">
            <a href="{{ route('search', ['type' => 'topic', 'query' => $query]) }}">Topic</a>
        </li>
    </ul>

    @if ($type == "question" || $type == "answer")
        <div class="margin-top clearfix">
            <div class="pull-right">
                <a href="{{ route('search', ['type' => $type, 'query' => $query]) }}"
                   class="{{ $range == '' ? 'font-greyLight' : ''}}">Anytime</a> /
                <a href="{{ route('search', ['type' => $type, 'query' => $query, 'range' => 1]) }}"
                   class="{{ $range == 1 ? 'font-greyLight' : ''}}">Within One Day</a> /
                <a href="{{ route('search', ['type' => $type, 'query' => $query, 'range' => 31]) }}"
                   class="{{ $range == 31 ? 'font-greyLight' : ''}}">Within One Month</a> /
                <a href="{{ route('search', ['type' => $type, 'query' => $query, 'range' => 93]) }}"
                   class="{{ $range == 93 ? 'font-greyLight' : ''}}">Within Three Month</a>
            </div>
        </div>
        <hr class="small_hrLight">
    @endif



    <div class="margin-top page_search" id="search_page_content">

    </div>

    <div class="margin-top" id="search_page_nav">

    </div>

@endsection

@section('javascript')
<script type="text/javascript">
    $(function() {
        searchRange = '{{ $range }}';
        searchType = '{{ $type }}';
        showResultPage('search_page', '{{ $type }}', '{{ $query }}', 1, null);
    })
</script>
@endsection