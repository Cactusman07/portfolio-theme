$( document ).ready(function() {
	var $body = $('body'), $opaqueCanvas = $('.particles-js-canvas-el'), $headLogo = $('#header-logo'), 
	$about = $('#about');
	
	inView('.section-inView').on('enter', function(el){
		var color = $(el).attr('data-background-color');
		$body.animate({
			backgroundColor: color
		}, 500 );
	});
	
	/* Make Logo fade in & out of Header */
	inView('#logo-container').on('exit', function(el){
		$headLogo.animate({
			opacity: 1,
			top: 15
		}, 650 );
	});	
	inView('#logo-container').on('enter', function(el){
		$headLogo.animate({
			opacity: 0,
			top: -150
		}, 650 );
	});	
	
	/* Make Canvas fade in second section */
	inView('.section2').on('enter', function(el){
		$opaqueCanvas.animate({
			opacity: 1
		}, 500 );
		$about.animate({
			opacity: 1
		}, 900 );
	});	
});