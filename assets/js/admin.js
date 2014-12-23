/* ---------- Notifications ---------- */
$("#DateCountdown").TimeCircles({ time: {
    Days: { text: ' ' },
    Hours: { text: '' },
    Minutes: { text: '' },
    Seconds: { text: '' }
}});


	$('.noty').click(function(e){
		e.preventDefault();
		var options = $.parseJSON($(this).attr('data-noty-options'));
		noty(options);
	});

