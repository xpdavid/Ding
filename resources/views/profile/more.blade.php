@extends('layouts.profile')

@section('content')
    @include('partials._profile_card', ['user' => $user])


    <div class="userHome_layoutDiv">
        <div class="userHome_layoutDivHead">
            <a href="/people/{{$user->url_name}}" class="float-left">{{ $user->name }}</a> 's Profile
            <a href="/people/{{$user->url_name}}" class="float-right">&lt;&lt; Back to the index</a>
        </div>
        <div class="userHome_layoutDivContent profile_bottom_content row">
            <div class="col-md-3 font-black">
                <span class="glyphicon glyphicon-tower"></span> Achievement:
            </div>
            <div class="col-md-9">
                <span class="space-right-big"></span>
                <span class="space-right"></span>
                <span class="glyphicon glyphicon-ok font-greyLight" aria-hidden="true"></span> <strong>{{ $user->totalVotes }}</strong> Vote,
                <span class="glyphicon glyphicon-heart font-greyLight" aria-hidden="true"></span> <strong>{{ $user->totalLikes }}</strong> Likes,
                <span class="glyphicon glyphicon-heart font-greyLight"></span><strong>{{ $user->bookmarks()->where('is_public', true)->count() }}</strong> Bookmarks,
                <span class="glyphicon glyphicon-gift font-greyLight"></span><strong>0</strong> Share
            </div>
        </div>
        <hr class="small_hrLight">
        <div class="userHome_layoutDivContent profile_bottom_content row">
            <div class="col-md-3 font-black">
                <span class="glyphicon glyphicon-briefcase"></span> Jobs:
            </div>
            <div class="col-md-9">
                <ul class="userEdit_itemList" id="user_job_list">
                    @foreach($user->jobs as $job)
                        <li id="job_{{ $job->id }}">
                            <div class="userEdit_itemListDetail">
                                <div class="userEdit_itemListDetailImage">
                                    <img src="topic.png" class="img-rounded" />
                                </div>
                                <div class="userEdit_itemListDetailContent">
                                    <a href="#">{{ $job->full_name }}</a>
                                </div>
                            </div>
                            <a href="#" class="glyphicon glyphicon-trash userSetting_EditA userEdit_itemListDetailButton" onclick="detachJob(event, '{{ $job->id }}')">Delete</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <hr class="small_hrLight">
        <div class="userHome_layoutDivContent profile_bottom_content row">
            <div class="col-md-3 font-black">
                <span class="glyphicon glyphicon-education"></span> Education Exp:
            </div>
            <div class="col-md-9">
                <ul class="userEdit_itemList" id="user_education_list">
                    @foreach($user->educationExps as $educationExp)
                        <li id="educationExp_{{ $educationExp->id }}">
                            <div class="userEdit_itemListDetail">
                                <div class="userEdit_itemListDetailImage">
                                    <img src="topic.png" class="img-rounded" />
                                </div>
                                <div class="userEdit_itemListDetailContent">
                                    <a href="#">{{ $educationExp->full_name }}</a>
                                </div>
                            </div>
                            <a href="#" class="glyphicon glyphicon-trash userSetting_EditA userEdit_itemListDetailButton" onclick="detachEducationExp(event, '{{ $educationExp->id }}')">Delete</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>


@endsection

@section('side')
    @include('partials._user_home_sidebar', ['user' => $user])
@endsection

@section('javascript')
    <script type="text/javascript">
        $(function() {

        });
    </script>
@endsection