
/*
* function: memu_triggerMessageBar
* description: when page load, running js to set trigger to the menu button 'message' so that it will
* display content when click;
*/
function memu_triggerMessageBar() {
	$('#user_notice').popover({
		animation: true,
		container: 'body',
		content: $('#contentForNoticeBar').html(), // need use ajax function next time
		html: true,
		placement: 'bottom',
	});

	$('#contentForNoticeBar').empty(); // in order for the js trigger function well.
}