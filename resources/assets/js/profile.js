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