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
