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
