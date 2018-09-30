$( document ).ready(function() {
	// var $headLogo = $('#header-logo'), 
	var $arrow 			= $('.arrow'),
		$browserNotice 	= $('#browser-note'),
		$close 			= $('#browser-note .close');
	
	/* Make Logo fade in & out of Header 
	inView('#about-description').on('enter', function(el){
		$headLogo.animate({
			opacity: 1,
			top: 15
		}, 650 );
	});	
	inView('#about-description').on('exit', function(el){
		$headLogo.animate({
			opacity: 0,
			top: -150
		}, 650 );
	});	*/

	/* Arrow function */
	$arrow.on('click', function(){
		$('html, body').animate({
			scrollTop: $('#about').offset().top
		}, 900);
	});

	/* Hide Browser Notice */
	$close.on('click', function(){
		$browserNotice.animate({
			opacity: 0,
			top: "-100"
		}, 600, function(){
			$browserNotice.css("display", "none");
		});
	});
});