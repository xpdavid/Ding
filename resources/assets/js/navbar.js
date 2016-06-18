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
                console.log(process_data);
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
