@extends('layouts.profile')

@section('content')
    @include('partials._profile_card', ['user' => $user])


    <!-- My Question -->
    <div class="userHome_layoutDiv">
        <div class="userHome_layoutDivHead">
            My Questions
            <a href="#"><span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span></a>
        </div>
        <div class="userHome_layoutDivContent">

        </div>
    </div>


    <!-- My Answers -->
    <div class="userHome_layoutDiv">
        <div class="userHome_layoutDivHead">
            My Answers
            <a href="#"><span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span></a>
        </div>
        <div class="userHome_layoutDivContent">

        </div>
    </div>



@endsection


@section('side')
    @include('partials._user_home_sidebar', ['user' => $user])
@endsection