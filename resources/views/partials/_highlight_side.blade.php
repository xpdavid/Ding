<div class="sideBar_section">
    <div class="clearfix">
        <div class="float-left">Hot Topics</div>
        <div class="float-right font-grey"><a href="#">More &gt;&gt;</a></div>
    </div>
</div>

<div class="sideBar_section">
    @foreach(App\Topic::all()->take(4) as $topic)
        <div class="clearfix sideBar_sectionItem">
            <img class="img-rounded float-left space-right avatar-img" src="topics.png" alt="...">
            <a href="/topic/{{ $topic->id }}">{{ $topic->name }}</a>
            <div class="font-grey">{{ $topic->subscribers()->count() }} substribe</div>
        </div>
    @endforeach
</div>

<br>
<div class="sideBar_section">
    <div class="clearfix">
        <div class="float-left">Hot Bookmarks</div>
        <div class="float-right font-grey"><a href="#">Try Again? &gt;&gt;</a></div>
    </div>
</div>

<div class="sideBar_section noborder">
    <div class="clearfix sideBar_sectionItem">
        <img class="img-rounded float-left space-right avatar-img" src="topics.png" alt="...">
        <a href="#">Food Science</a>
        <div class="font-grey">3842 substribe</div>
    </div>
    <div><a href="#">Does't alcohal harm?</a></div>
    <div class="clearfix sideBar_sectionItem">
        <img class="img-rounded float-left space-right avatar-img" src="topics.png" alt="...">
        <a href="#">Food Science</a>
        <div class="font-grey">3842 substribe</div>
    </div>
</div>