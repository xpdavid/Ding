/**
 * send ajax request to get notification item
 */
function getOneDayNotification() {
    $('#notification_more').prop('disabled', true);
    getOneDayNotificationHelper(function(results) {
        if (results.date) {
            $('#notification_more').html('No More Already');
        } else {
            $('#notification_more').prop('disabled', false);
        }
        notificationDay++;
    });
}
/**
 * Helper function for send ajax request to get norification
 */
var notificationDay = 1;
function getOneDayNotificationHelper(callback) {
    $.post('/notification', {
        day :  notificationDay
    }, function(results) {
        if (results.date) {
            var template = Handlebars.templates['_notification_day.html'];
            $('#notification').append(template(results));
        }
        if (callback && typeof callback == "function") {
            callback(results);
        }
    })
}