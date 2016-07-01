@extends('layouts.bookmark')


@section('left')
    <div class="clearfix">
        <a href="/people/{{ $bookmark->owner->url_name }}/bookmark" class="float-left">&lt;&lt; Back to {{ $bookmark->owner->name }}'s Bookmarks</a>
        <a class="float-right" href="/people/{{ Auth::user()->url_name }}/bookmark">To My Bookmarks &gt;&gt;</a>
    </div>

    <div class="clearfix">
        <div id="bookmark_show">
            <h3 class="font-black" id="bookmark_show_name">{{ $bookmark->name }}
                    <span class="label label-info {{ $bookmark->is_public ? 'noneDisplay' : '' }}" id="bookmark_private_label">Private</span>
            </h3>
            <p id="bookmark_show_description">{{ $bookmark->description }}</p>
        </div>
        @if ($bookmark->owner->id == Auth::user()->id)
            <div id="bookmark_edit" class="clearfix noneDisplay margin-top">
                <div class="form-group">
                    <label for="bookmark_name">Bookmark Name <span class="font-greyLight">(Your bookmark subscriber may be disturbed by the change of name)</span></label>
                    <input type="email" value="{{ $bookmark->name }}" class="form-control" id="bookmark_name" placeholder="Name">
                </div>
                <div class="form-group">
                    <label for="bookmark_description">Bookmark Description</label>
                    <textarea class="form-control" id="bookmark_description" placeholder="Description" rows="3">{{ $bookmark->description }}</textarea>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" value="true" name="bookmark_is_public" id="bookmark_public" {{ $bookmark->is_public ? 'checked' : '' }}> Pulbic
                                    <span class="font-greyLight">
                                        You can change to private if there is not subscribers
                                    </span>
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" value="false" name="bookmark_is_public" id="bookmark_private" {{ $bookmark->is_public ? '' : 'checked' }}> Private
                                    <span class="font-greyLight">
                                        You can only view the bookmark by yourself
                                    </span>
                    </label>
                </div>
                <button type="submit" class="btn btn-primary float-right"  onclick="bookmark_save_change('{{ $bookmark->id }}')">Edit</button>
                <button type="button" class="btn btn-link float-right" onclick="bookmark_edit()">Cancel</button>
            </div>
        @endif


        <div class="horizontal_item">
            <a href="#"
               id="bookmark_comment_{{ $bookmark->id }}_trigger"
               onclick="showComment(event, 'bookmark', '{{ $bookmark->id }}', 'bookmark_comment_{{ $bookmark->id }}');">
                <span class="glyphicon glyphicon-comment" aria-hidden="true"></span>
                Comment (<span id="bookmark_comment_{{ $bookmark->id }}_replies_count">{{ $bookmark->replies()->count() }}</span>)
            </a>
            @if ($bookmark->owner->id == Auth::user()->id)
                <a href="#" onclick="bookmark_edit(event)"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>Edit</a>
                <a href="#" onclick="bookmark_delete('{{ $bookmark->id }}', event)"><span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>Delete</a>
            @endif
            <a href="#"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>Report</a>
        </div>
        <div class="comment_box" id="bookmark_comment_{{ $bookmark->id }}">
            <div class="comment_spike">
                <div class="comment_spike_arrow"></div>
            </div>
            <div class="comment_list">
                <div class="comment_content" id="bookmark_comment_{{ $bookmark->id }}_content">

                </div>

                <div class="text-center" id="bookmark_comment_{{ $bookmark->id }}_nav">

                </div>

                <div class="comment_form clearfix">
                    <div class="form-group">
                        <input type="text"
                               class="form-control"
                               id="bookmark_comment_{{ $bookmark->id }}_input"
                               placeholder="Write Your Comment Here"
                               onfocus="show_form(event, 'bookmark_comment_{{ $bookmark->id }}_buttons')"
                        >
                    </div>
                    <div class="float-right form-group noneDisplay" id="bookmark_comment_{{ $bookmark->id }}_buttons">
                        <a href="#" role="button" class="space-right-big" onclick="cancel_from(event, 'bookmark_comment_{{ $bookmark->id }}_buttons')">Cancel</a>
                        <button class="btn btn-primary" type="submit"
                                onclick="saveComment('bookmark_comment_{{ $bookmark->id }}', '{{ $bookmark->id }}', 'bookmark')">Submit</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <hr class="small_hrLight">
    <div>

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#bookmark_answer" aria-controls="bookmark_answer" role="tab" data-toggle="tab">Answer <span class="badge">{{ $bookmark->answers()->count() }}</span></a></li>
            <li role="presentation"><a href="#bookmark_question" aria-controls="bookmark_question" role="tab" data-toggle="tab">Question <span class="badge">{{ $bookmark->questions()->count() }}</span></a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="bookmark_answer">
                <div id="bookmark_answer_content" class="margin-top"></div>
                <div id="bookmark_answer_nav" class="text-center"></div>
            </div>
            <div role="tabpanel" class="tab-pane" id="bookmark_question">
                <div id="bookmark_question_content" class="margin-top"></div>
                <div id="bookmark_question_nav" class="text-center"></div>
            </div>
        </div>

    </div>

@endsection



@section('right')

    <div class="sideBar_section">
        <div class="sideBar_sectionItem">
            <div>
                @if(Auth::user()->subscribe->checkHasSubscribed($bookmark->id, 'bookmark'))
                    <button type="button" class="btn btn-warning"
                            onclick="bookmark_subscribe_click(this, '{{ $bookmark->id }}')">
                        Unsubscribe</button>
                @else
                    <button type="button" class="btn btn-success"
                            onclick="bookmark_subscribe_click(this, '{{ $bookmark->id }}')">
                        Subscribe</button>
                @endif
                <div class="font-greyLight margin-top"><span id="bookmark_numSubscriber">{{ $bookmark->subscribers()->count() }}</span> Subscribers</div>
            </div>
        </div>
    </div>

    <div class="sideBar_section">
        <div class="sideBar_sectionItem">
            <h4>About Author</h4>
            <div class="media">
                <div class="media-left">
                    <a href="#">
                        <img class="media-object avatar-img" src="{{ DImage($bookmark->owner->settings->profile_pic_id,50, 50) }}"
                             alt="{{ $bookmark->owner->name }}">
                    </a>
                </div>
                <div class="media-body">
                    <button class="btn btn-success btn-xs float-right" onclick="user_button_subscribe(this, '{{ $bookmark->owner->id }}', null)">Subscribe</button>
                    <h4 class="media-heading">{{ $bookmark->owner->name }}</h4>
                    {{ $bookmark->owner->bio }}
                </div>
            </div>
        </div>
    </div>

    <div class="sideBar_section">
        <div class="sideBar_sectionItem">
            <div class="clearfix">
                <h4 class="float-left">Hot bookmarks</h4>
                <div class="float-right margin-top">
                    <a href="#" onclick="event.preventDefault()">
                        <span class="glyphicon glyphicon-refresh" onclick="getHotBookmark()"></span>
                    </a>
                </div>
            </div>
            <div id="hot_bookmark">

            </div>
        </div>
    </div>


@endsection


@section('javascript')
<script type="text/javascript">
    showBookmarkQuestionPage('bookmark_question', null, '{{ $bookmark->id }}', 1, null);
    showBookmarkAnswerPage('bookmark_answer', null, '{{ $bookmark->id }}', 1, null);
    getHotBookmark();
</script>
@endsection