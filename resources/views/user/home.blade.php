@extends('layouts.user')


@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="userHome_layoutDiv">
                <div class="userHome_profileHeader">

                    <div class="row userHome_profileHeaderTop">
                        <div class="col-md-12">
                            <h4><span class="userHome_name">Xiao Pu</span> , I am programmer!</h4>
                        </div>
                    </div>

                    <div class="row">
                        <div class="userHome_profileHeaderLeft">
                            <img src="xp_l.jpeg" alt="..." class="img-rounded">
                            <a class="btn btn-default btn-xs" href="#" role="button">Edit My Profile</a>
                        </div>

                        <div class="userHome_profileHeaderRight">
                            <p>
                                    <span class="glyphicon glyphicon-globe" aria-hidden="true"></span>
                                  <span class="userHome_profileItem">
                                    <a href="#">
                                      National University of Singapore
                                    </a>
                                  </span>
                                  <span class="userHome_profileMoreItem">
                                    <a href="#">
                                      Computer Science
                                    </a>
                                  </span>
                                  <span class="userHome_profileMoreItem">
                                      Male
                                  </span>
                            </p>
                            <p>
                                <span class="glyphicon glyphicon-briefcase" aria-hidden="true"></span>
                                  <span class="userHome_profileItem">
                                    <a href="#">
                                      PaperBaton
                                    </a>
                                  </span>
                            </p>
                            <p>
                                <span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>
                                  <span class="userHome_profileItem">
                                    <a href="#">
                                      Email
                                    </a>
                                  </span>
                            </p>
                            <div class="userHome_selfIntro">
                                I am a happy programmer!
                            </div>
                        </div>
                    </div>

                </div>

                <div class="userHome_pointSummary">
                    Gain <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> <span class="userHome_number">10</span> Vote, <span class="glyphicon glyphicon-heart" aria-hidden="true"></span> <span class="userHome_number">10</span> Thanks
                </div>

                <div class="userHome_profileNavbar">
                    <a href="#" class="glyphicon glyphicon-menu-hamburger"></a>
                    <a href="#">
                        My Question
                        <span>0</span>
                    </a>
                    <a href="#">
                        My Answers
                        <span>0</span>
                    </a>
                    <a href="#">
                        My Bookmarks
                        <span>0</span>
                    </a>
                    <a href="#">
                        My Edit
                        <span>0</span>
                    </a>
                </div>

            </div>


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
        </div>
    </div>
</div>



@endsection