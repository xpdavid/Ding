/*
Section for user profile edit
 */

/**
 * Bind the event 'toggle user setting form' to the edit button with id = $id
 *
 * @param $id
 */
function genericUserProfileEditToggleSetting(id) {
    // hide two div
    $(id + '_edit').toggle();

    $(id).find('a').click(function(e) {
        e.preventDefault();

        $(id).toggle();
        $(id + '_edit').toggle();
    });

    $(id + '_edit').find('a').click(function(e) {
        e.preventDefault();

        $(id).toggle();
        $(id + '_edit').toggle();
    });
}

$(window).on('load', function() {
    // try bind every button with the event 'click and then toggle'
    try {
        genericUserProfileEditToggleSetting('#user_sex');
        genericUserProfileEditToggleSetting('#user_display_facebook');
        genericUserProfileEditToggleSetting('#user_display_email');
        genericUserProfileEditToggleSetting('#user_bio');
        genericUserProfileEditToggleSetting('#user_self_intro');
        genericUserProfileEditToggleSetting('#user_education');
        genericUserProfileEditToggleSetting('#user_job');
        genericUserProfileEditToggleSetting('#user_specialization');

    } catch (e) {
        console.log('edit button event binding fail');
    }
})



/**
 * Show edit button when cursor hover
 *
 * @param id
 */
function genericUserProfileEditHover(id) {
    $(id).find('a').toggle();

    $(id).parent().parent().parent().hover(function() {
        $(id).find('a').toggle();
    });
}

$(function() {
    // try bind every div with hover event
    try {
        genericUserProfileEditHover('#user_sex');
        genericUserProfileEditHover('#user_display_facebook');
        genericUserProfileEditHover('#user_display_email');
        genericUserProfileEditHover('#user_bio');
        genericUserProfileEditHover('#user_self_intro');

    } catch (e) {
        console.log('hover event binding fail');
    }
})


/**
 * Popup candidate text for the input field (typehead)
 *
 */
function genericTypeHead(id, source_url) {
    $(id).typeahead({
        delay: 400,
        source: function (query, process) {
            $.ajax({
                url: source_url,
                data: {query: query},
                dataType: 'json'
            }).done(function(response) {
                return process(response);
            });
        }
    });
}

$(function() {
    // try bind every input field with typehead
    try {
        genericTypeHead('[name="institution"]', '/api/institution-list');
        genericTypeHead('[name="major"]', '/api/major-list');
    } catch (e) {
        console.log('hover event binding fail');
    }
})

/**
 * AJAX requent to the sever to change the user education experience
 * @param url_name
 */
function saveEducationExp(url_name) {
    $.post({
        url: '/people/' + url_name,
        data: {
            _method : 'PATCH',
            institution : $('[name="institution"]').val(),
            major : $('[name="major"]').val(),
            type : 'education'
        },
        dataType: 'json'
    }).done(function(data) {
        // display edit button
        $('#user_education_edit').find('a').click();

        // generate education experience name
        var educationExpName = $('[name="institution"]').val();
        if ($('[name="major"]').val() != "") {
            educationExpName = educationExpName + ' ⋅ ' + $('[name="major"]').val();
        }

        // append generate item to the list
        $('#user_education_list').append(
            generateItemUI(
                educationExpName,
                'detachEducationExp(' + data.educationExp_id  + ')'
            )
        );

        // clear value
        $('[name="institution"]').val("");
        $('[name="major"]').val("");

    }).fail(function() {
        console.log('save education experience fail.');
    });

    return false;
}

/**
 * generate ItemUI so that we can add item without refresh
 *
 * there need a function that auto find img by using some keywords
 *
 * @param title
 * @param deleteAction
 */
function generateItemUI(title, deleteAction) {
    titleUI = $('#itemContent_title');
    deleteButtonUI = $('#itemContent_delete');

    titleUI.html(title);
    deleteButtonUI.attr('onclick', deleteAction);

    titleUI.attr('id', '');
    deleteButtonUI.attr('id', '');

    $content = $('#itemContent').html();

    titleUI.attr('id', 'itemContent_title');
    deleteButtonUI.attr('id', 'itemContent_delete');

    return $content;
}
