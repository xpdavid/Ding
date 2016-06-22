/**
 * Set up ajax request header for laravel
 */
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


/**
 * scroll to the element
 *
 * @param event
 * @param element_id
 */
function scroll_to(event, element_id) {
    event.preventDefault();

    $('html,body').animate({scrollTop: $('#' + element_id).offset().top}, 1000);
}

/**
 * toggle display of the element with id
 *
 * @param id
 */
function toggle(id, event) {
    if (event) {
        event.preventDefault();
    }
    $('#' + id).toggle();
}

//# sourceMappingURL=main.js.map
