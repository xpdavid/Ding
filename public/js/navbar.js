/**
 * this is a jquery function which will be execute when page is load
 */
$(function() {
    navbar_triggerMessageBar();
    navbar_searching();
});


/**
 * function: navbar_triggerMessageBar
 * description: when page load, running js to set trigger to the menu button 'message' so that it will
 * display content when click;
 */
function navbar_triggerMessageBar() {
    $('#user_notice').popover({
        animation: true,
        container: 'body',
        content: $('#contentForNoticeBar').html(), // need use ajax function next time
        html: true,
        placement: 'bottom',
    });

    $('#contentForNoticeBar').empty(); // in order for the js trigger function well.
}


/**
 * function: navbar_searching
 * description: set asynchronous searching post query
 */
function navbar_searching() {
    var $input = $('.typeahead');
    $input.typeahead({source:[
        {id: "someId1", name: "Display name 1", category: "skill"},
        {id: "someId2", name: "Display name 2", category: "game"},
        {id: "someId3", name: "Display Name 3", category: "game"}],
        autoSelect: true});
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
            url: "/topic/autocomplete",
            dataType: 'json',
            method: 'POST',
            delay: 250,
            data: function (params) {
                return {
                    query: params.term, // search term
                    max_match: 10,
                    use_similar: 0
                };
            },
            processResults: function(data, params) {
                var process_data = [];
                $.each(data, function(index, value) {
                    process_data.push({
                        id : index,
                        text : value
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
            $.post('/question/autocomplete', {
                query : query,
                use_similar : 0,
                max_match : 7,
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
            $.post('/question/autocomplete', {
                query : $('#ask_question_detail_input').val(),
                use_similar : 0,
                max_match : 6,
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
