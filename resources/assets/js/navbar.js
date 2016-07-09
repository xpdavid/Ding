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
    // ajax get notification content
    navbar_noticeBarAJAX(function(results) {
        $('#user_notice').popover({
            animation: true,
            container: 'body',
            content: $('#contentForNoticeBar').html(), // need use ajax function next time
            html: true,
            placement: 'bottom',
            template : '<div class="popover navbar_noticeBarPopover" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>'
        });


        $('#contentForNoticeBar').empty(); // in order for the js trigger function well.
    });

}

/**
 * function: navbar_noticBarAJAX
 * description: ajax to get content for notice bar
 */
function navbar_noticeBarAJAX(callback) {
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
                $('#notice_notice').append('<div>' + item.content + '</div>');
            });

            $.each(notice_user, function (index, item) {
                $('#notice_user').append('<div>' + item.content + '</div>');
            });

            $.each(notice_thanks, function (index, item) {
                $('#notice_thanks').append('<div>' + item.content + '</div>');
            });
        }

        if (callback && typeof callback == 'function') {
            callback(results);
        }
    });
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
 * function: navbar_question_detail
 * description: trigger question detail model, fade ask question model
 */
function navbar_question_detail() {
    $('#ask_question').modal('hide');
    $('#_question').modal('show');
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
    // copy same query
    new_input.val(old_input.val());
    old_input.val('');
    // switch to ask mode
    _qeustion_modal_UISwitch('ask');
    // fire search event
    new_input.trigger('change');
    // show ask question detail page
    $('#_question').modal('show');
}

function navbar_question_form_process() {
    var timer1 = null;
    var timer2 = null;
    $('#_question_form').submit(function() {
        // question title empty
        if ($('#_question_title').val().length <= 5) {
            clearTimeout(timer1);
            $('#_question_title_error').fadeIn();
            timer1 = setInterval(function() {
                $('#_question_title_error').fadeOut();
            }, 2000);
            return false;
        }

        // question topics empty
        if (!$('#_question_topics').val()) {
            clearTimeout(timer2);
            $('#_question_topics_error').fadeIn();
            timer2 = setInterval(function() {
                $('#_question_topics_error').fadeOut();
            }, 2000);
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
function _qeustion_modal_UISwitch(type) {
    $('[data-parent="_question"]').each(function () {
        var $t = $(this);
        if ($t.data('ask') && $t.data('edit')) {
            if ($t.data('change')) {
                $t.attr($t.data('change'), $t.data(type));
            } else {
                $t.html($t.data(type));
            }

        }
        if ($t.is(':visible')) {
            $t.hide();
        } else {
            $t.show();
        }
    });
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
    tinyMCEeditor('_question_detail'); // navbar ask question (question detail)

    navbar_question_form_process(); // form validation

    // equation support
    math_editor();
});

