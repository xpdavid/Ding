@extends('layouts.highlight')

@section('left')
    <div class="clearfix">
        <a href="#" class="float-left"><span class="glyphicon glyphicon-signal" aria-hidden="true"></span>Editor Recommendations</a>
        <a href="/highlight" class="float-right">&lt;&lt; Back</a>
    </div>
    <hr class="small_hr">

    <div id="highlight_recommend">

    </div>

    <button type="button"
            id='editorRecommendations_button'
            class="btn btn-default btn-block"
            onclick="getEditorRecommendations()">
        More</button>

    <br>
@endsection


@section('right')
    @include('partials._highlight_side')
@endsection


@section('javascript')
    <script type="text/javascript">
        $(function() {
            getEditorRecommendations();
        })
    </script>
@endsection