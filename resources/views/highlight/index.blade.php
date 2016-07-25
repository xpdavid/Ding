@extends('layouts.highlight')

@section('left')
    <div class="clearfix">
        <a href="#" class="float-left"><span class="glyphicon glyphicon-star" aria-hidden="true"></span>Editor Recommendations</a>
        <a href="/highlight/recommendations" class="float-right">More &gt; &gt;</a>
    </div>
    <hr class="small_hr">

    <div id="highlight_recommend">

    </div>

    <br>
    <!-- Nav tabs for hot topics -->
    <div>

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#hotInWeek" aria-controls="hotInWeek" role="tab" data-toggle="tab">Hot in Week</a></li>
            <li role="presentation"><a href="#hotInMonth" aria-controls="hotInMonth" role="tab" data-toggle="tab">Hot in Month</a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="hotInWeek">
                <div id="highlight_week">

                </div>
                <br>
                <button type="button"
                        id='highlight_week_more'
                        class="btn btn-default btn-block"
                        onclick="getHotWeekQuestion()">
                    More</button>
            </div>
            <div role="tabpanel" class="tab-pane" id="hotInMonth">
                <div id="highlight_month">

                </div>
                <br>
                <button type="button"
                        id='highlight_month_more'
                        class="btn btn-default btn-block"
                        onclick="getHotMonthQuestion()">
                    More</button>
            </div>
        </div>

    </div>

    <br>
    @include('partials._show_comment_conversation')
@endsection


@section('right')
    @include('partials._highlight_side')
@endsection


@section('javascript')
    <script type="text/javascript">
        $(function() {
            getRecommendQuestion(null, null, processRecommendShort); // ajax get recommend question
            getHotWeekQuestion(); // ajax get hot week questions
            getHotMonthQuestion(function() { // callback
                // backup content
                hotMonthContent = $('#highlight_month').html();
                // remove to prevent duplicate id
                $('#highlight_month').html('');
            }); // ajax get hot month questions

            backupContent();
        })
    </script>
@endsection