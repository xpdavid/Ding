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
            // * Render the notification according to the template
            // * 1.	{{User}} invite you to answer question {{Question}}
            // * 2.	{{User}} answer the question {{Question}}
            // * 3.	{{User}} @ you in his/her {{answer}}
            // * 4.	{{User}} @ you in his/her {{question}}
            // * 5.	{{User}} @ you in his/her {{reply}}
            // * 6.	{{User}} reply your {{Reply}}
            // * 7.	{{User}} vote up your answers {{answer}}
            // * 8.	Someone vote down your answers {{answer}}
            // * 9.	{{User}} vote up your reply {{Reply}}
            // * 10.	{{User}} subscribe you.
            // * 11.	{{User}} send message to you
            var notice_notice = items.filter(function (item) {
                return $.inArray(item.type, [1, 2, 3, 4, 5, 6]) != -1;
            });

            var notice_user = items.filter(function (item) {
                return $.inArray(item.type, [10, 11]) != -1;
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
    $('#ask_question_detail').modal('show');
}

/**
 * function: navbar_question_topic_autocomplete
 * description: autocomplete help select topic when ask question
 */
function navbar_question_topic_autocomplete() {
    $("#question_topics").select2({
        width: '100%',
        dropdownAutoWidth : true,
        placeholder: 'Please select some topics',
        minimumInputLength : 1,
        ajax: {
            url: "/api/autocomplete",
            dataType: 'json',
            method: 'POST',
            delay: 250,
            data: function (params) {
                return {
                    queries: [{
                        type : 'topic',
                        term : params.term, // search term
                        max_match: 10,
                        use_similar: 0,
                    }]
                };
            },
            processResults: function(data, params) {
                var process_data = [];
                $.each(data, function(index, item) {
                    process_data.push({
                        id : item.id,
                        text : item.name
                    });
                });
                return {
                    results : process_data
                }
            }
        },
    });
}

$(function () {
    navbar_question_topic_autocomplete();
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
        items: 8,
    });
}

$(function() {
    navbar_serach_box_autocomplate();
});

function navbar_ask_question_detail() {
    $('#ask_question').modal('hide');
    var old_input = $('#ask_question_input');
    var new_input = $('#ask_question_detail_input');
    // copy same query
    new_input.val(old_input.val());
    old_input.val('');
    // fire search event
    new_input.trigger('change');
    // show ask question detail page
    $('#ask_question_detail').modal('show');
}

/**
 * Question detail page serach autocomplete
 */
var navbar_search_table_timer = null;
function navbar_serach_table_autocomplete() {
    $('#ask_question_detail_input').bind('change keyup paste', function() {
        clearTimeout(navbar_search_table_timer);
        navbar_search_table_timer = setTimeout(function() {
            $.post('/api/autocomplete', {
                queries: [{
                    type : 'question',
                    term : $('#ask_question_detail_input').val(), // search term
                    max_match: 6,
                    use_similar: 0,
                }]
            }, function(results) {
                var search_table = $('#ask_question_detail_search_table');
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

