/**
 * this is a jquery function which will be execute when page is load
 */
$(function() {
    navbar_searching();
    navbar_triggerNoticeBar();
});

/**
 * function: navbar_triggerNoticeBar
 * description: when page load, running js to set trigger to the menu button 'message' so that it will
 * display content when click;
 */
var navbar_noticeBarPage = 1;
function navbar_triggerNoticeBar() {
    var $object = $($('#contentForNoticeBar').html());
    $('#contentForNoticeBar').empty();

    $('#user_notice').popover({
        animation: true,
        container: 'body',
        content: '<div id="nav_notification"></div>', // need use ajax function next time
        html: true,
        placement: 'bottom',
        template : '<div class="popover navbar_noticeBarPopover" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>'
    });

    $('#user_notice').on('show.bs.popover', function() {
        navbar_noticeBarAJAX($object, function() {
            $('#nav_notification').html($object[0].outerHTML);
        });
    });

    // click other place close the popover
    // popover click outside auto hide
    $('html').on('click', function (e) {
        if (!$(e.target).parents().is("#nav_notification")
                && !$(e.target).is("#user_notice")
            && $(e.target).parents('.popover.in').length === 0) {
            $('#user_notice').popover('hide');
        }
    });


}

/**
 * function: navbar_noticBarAJAX
 * description: ajax to get content for notice bar
 */
function navbar_noticeBarAJAX($object, callback) {
    // ajax get notification content
    $.post('/notification', {
        page: navbar_noticeBarPage
    }, function (results) {
        if (results.items) {
            var items = results.items;
            var notice_notice = items.filter(function (item) {
                return $.inArray(item.type, [1, 2, 3, 4, 5, 6]) != -1;
            });

            var notice_user = items.filter(function (item) {
                return $.inArray(item.type, [10, 11, 12, 13, 14]) != -1;
            });

            var notice_thanks = items.filter(function (item) {
                return $.inArray(item.type, [7, 8, 9]) != -1;
            });

            $.each(notice_notice, function (index, item) {
                $object.find('#notice_notice').append('<div>' + item.content + '</div>');
            });

            $.each(notice_user, function (index, item) {
                $object.find('#notice_user').append('<div>' + item.content + '</div>');
            });

            $.each(notice_thanks, function (index, item) {
                $object.find('#notice_thanks').append('<div>' + item.content + '</div>');
            });
        }

        if (callback && typeof callback == 'function') {
            callback(results);
        }
    })
}



/**
 * function: navbar_searching
 * description: set asynchronous searching post query
 */
function navbar_searching() {
    var $input = $('#navbar_searching');
    $input.typeahead({
        delay : 500,
        source : function(query, process) {
            $.post('/api/autocomplete', {
                queries: [{
                    type : 'topic',
                    term : query, // search term
                    max_match: 3,
                    use_similar: 0,
                },{
                    type : 'people',
                    term : query, // search term
                    max_match: 3,
                    use_similar: 0,
                },{
                    type : 'question',
                    term : query, // search term
                    max_match: 8,
                    use_similar: 0,
                }]
            }, function(results) {
                process(results);
            })
        },
        displayText : function(item) {
            if (item.name) {
                return item.name;
            }

            if (item.title) {
                return item.title;
            }
        },
        afterSelect : function(item) {
            if (item && item.url) {
                window.location.replace(item.url);
            }
        },
        items : 'all',
        sorter : function(items) { return items}, // don't sort it!
        width : '540px',
        addItemBefore : function() {
            return {
                'name' : $('#navbar_searching').val(),
                'url' : '/search?type=question&query=' + $('#navbar_searching').val(),
                'category' : 'Press Enter for more results'
            };
        },
        beforeShownKeyup : function(e) {
            if (e.keyCode == 13) {
                navbar_searching_click();
            }
        },
        noResultHide : false
    });

    // bind enter event
    

}

/**
 * navbar search icon click redirect
 */
function navbar_searching_click() {
    if (typeof searchType == "undefined") {
        searchType = 'question';
    }
    window.location = '/search?type=' + searchType + '&query=' + $('#navbar_searching').val();
}

/**
 * function: navbar_ask_button
 * description: navbar ask button click
 */
function navbar_ask_button() {
    // if has input before, show the input directly.
    if (($('#_question_title').val().length != 0
        || tinymce.get('_question_detail').getContent().length != 0
        || $('#_question_topics').val())
        && $('#_question').data('mode') == 'ask') {
        navbar_show_ask_new_question_option();
        $('#_question_detail').data('autosave', true);
        $('#_question').modal('show');
        $('#ask_question').modal('hide');
    } else {
        // else show search box and them get recent draft
        $.post('/question/latestDraft', {} ,function(results) {
            // switch to ask mode
            _question_modal_UISwitch('ask');
            if (results.status) {
                $a_tag_restore = $('<a></a>');
                $a_tag_restore.html('You have question draft (Click to show)');
                $a_tag_restore.addClass('text-success');
                $a_tag_restore.addClass('space-right');
                $a_tag_show_all = $('<a></a>');
                $a_tag_show_all.html('Show all drafts');
                $a_tag_show_all.attr('href', '/draft');
                $a_tag_show_all.addClass('font-greyLight');
                // click action
                $a_tag_restore.click(function() {
                    $('#ask_question').modal('hide');

                    // set results to the input
                    navbar_question_json_set(results);

                    // show modal
                    $('#_question').modal('show');
                });

                var $wrapper = $('<p></p>');
                $wrapper.addClass('margin-top');
                $wrapper.append($a_tag_restore);
                $wrapper.append(' Or ');
                $wrapper.append($a_tag_show_all);
                $('#_question_draft_status').html($wrapper);
            }
            // show asking new question option
            navbar_show_ask_new_question_option();
            // show search box
            $('#ask_question').modal('show');
        });
    }
}

/**
 * Set json data to the input
 *
 * @param results
 */
function navbar_question_json_set(results) {
    // set title
    $('#_question_title').val(results.title);
    // clear content
    tinymce.get('_question_detail').setContent(
        changeTexToImage(results.content)
    );
    // clear topics
    var ids = [];
    var $topics = $('#_question_topics');
    $topics.empty();
    $.each(results.topics, function(index, topic) {
        $topics.append($("<option/>") //add option tag in select
            .val(topic.id) //set value for option to post it
            .text(topic.name)); //set a text for show in select
        ids.push(topic.id);
    });
    $topics.val(ids).trigger("change"); //apply to select2
    // clear id
    $('#_question_detail_draft_id').val(results.id);
    $('#_question_detail_draft_id').data('id', results.id);
    // clear id
    $('#_question_edit_id').val(results.id);
    $('#_question_edit_id').data('id', results.id);

    // set reward
    $('#_question_reward').val(results.reward);

    // fire search event
    $('#_question_title').trigger('change');
}


/**
 * Show ask new question option in _question modal
 */
function navbar_show_ask_new_question_option() {
    $a_tag = $('<a></a>');
    $a_tag.html('Ask New Question');
    $a_tag.addClass('text-success');
    $a_tag.click(function() {
        navbar_ask_clear_input();
    });
    $wrapper = $('<p></p>').addClass('margin-top').html($a_tag);
    $wrapper.addClass('pull-left');

    $('#_question_new_question').html($wrapper);
}

/**
 * Clear all inputs in _question model
 */
function navbar_ask_clear_input() {
    // clear title
    $('#_question_title').val('');
    // clear content
    tinymce.get('_question_detail').setContent('');
    // clear topics
    $('#_question_topics').empty();
    // clear id
    $('#_question_detail_draft_id').val('');
    $('#_question_detail_draft_id').data('id', '');
    // clear id
    $('#_question_edit_id').val('');
    $('#_question_edit_id').data('id', '');
    // clear reward
    $('#_question_reward').val('');
}

/**
 * description: autocomplete help select topic when ask question
 */
$(function () {
    topic_autocomplete('_question_topics');
});

/**
 * Ask question page serach autocomplete
 */
function navbar_serach_box_autocomplate() {
    $('#ask_question_input').typeahead({
        delay : 500,
        source : function(query, process) {
            $.post('/api/autocomplete', {
                queries: [{
                    type : 'question',
                    term : query, // search term
                    max_match: 7,
                    use_similar: 0,
                }]
            }, function(results) {
                process(results);
            })
        },
        displayText : function(item) {
            return item.title;
        },
        afterSelect : function(item) {
            if (item || item.url) {
                window.location.replace(item.url);
            }
        },
        bottomElement : {
            html : "<li class='nav_search_box_hint' onclick='navbar_ask_question_detail()'>I don't find my desired answer, keep asking</li>",
        },
        noResultHide : false,
        items: 8,
    });
}

$(function() {
    navbar_serach_box_autocomplate();
});

/**
 * Show question detail modal
 */
function navbar_ask_question_detail() {
    $('#ask_question').modal('hide');
    var old_input = $('#ask_question_input');
    var new_input = $('#_question_title');
    // switch to ask mode
    _question_modal_UISwitch('ask');
    // copy same query
    new_input.val(old_input.val());
    old_input.val('');
    // fire search event
    new_input.trigger('change');
    // show ask question detail page
    $('#_question').modal('show');
}

/**
 * Validation for navbar ask question form
 */
function navbar_question_form_process() {
    $('#_question_form').submit(function() {
        // question title empty
        if ($('#_question_title').val().length <= 5) {
            showError('_question_title', true);
            return false;
        }

        // question topics empty
        if (!$('#_question_topics').val()) {
            showError('_question_topics', true);
            return false;
        }

        // change tinymce content (tex image to formula)
        if (tinyMCE.get('_question_detail')) {
            tinyMCE.get('_question_detail').setContent(
                changeImageToTex(tinyMCE.get('_question_detail').getContent())
            );
        }

        return true;
    });
}

/**
 * Switch _question_detail mode
 * Two mode:
 * 1. ask
 * 2. edit
 * @param type
 * @private
 */
function _question_modal_UISwitch(type) {
    // record state
    $('#_question').data('mode', type);
    // change content
    $('[data-parent="_question"]').each(function () {
        var $t = $(this);
        if ($t.data('ask') != undefined && $t.data('edit') != undefined) {
            if ($t.data('change')) {
                $t.attr($t.data('change'), $t.data(type));
            } else if ($t.data('data_change')) {
                $t.data($t.data('data_change'), $t.data(type));
            } else {
                $t.html($t.data(type));
            }

        } else {
            if ($t.is(':visible')) {
                $t.hide();
            } else {
                $t.show();
            }
        }
    });

    if (type == "ask") {
        // clear content
        navbar_ask_clear_input();
    }

    // disable table
    navbar_search_table_disabled = (type == 'edit');
}

/**
 * Question detail page serach autocomplete
 */
var navbar_search_table_timer = null;
var navbar_search_table_disabled = false;
function navbar_serach_table_autocomplete() {
    $('#_question_title').bind('change keyup paste', function() {
        if (navbar_search_table_disabled) return ;
        clearTimeout(navbar_search_table_timer);
        navbar_search_table_timer = setTimeout(function() {
            $.post('/api/autocomplete', {
                queries: [{
                    type : 'question',
                    term : $('#_question_title').val(), // search term
                    max_match: 3,
                    use_similar: 0,
                }]
            }, function(results) {
                var search_table = $('#_question_title_search_table');
                if (results.length) {
                    // clear the table first
                    search_table.html("");
                    // process response
                    $.each(results, function(index, value) {
                        search_table.append('<tr><td><a href="' + value.url  + '">' + value.title + '</a> - ' + value.numAnswers + ' answer(s)</td></tr>');
                    });
                } else {
                    search_table.html('<tr><td>No Query Result, welcome to ask the question!</td></tr>');
                }

            });
        }, 500); // set query delay
    });

}

$(function() {
    navbar_serach_table_autocomplete();
});


// for question editor
$(function() {
    /**
     * disable auto save function when window close
     */
    $('#_question').on('hide.bs.modal', function() {
        $('#_question_detail').data('autosave', false);
    });

    tinyMCEeditor('_question_detail'); // navbar ask question (question detail)

    navbar_question_form_process(); // form validation

    // equation support
    math_editor();
});

