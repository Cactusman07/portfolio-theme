$( document ).ready(function() {

	/* Home page JS only */
	if ($("body").hasClass("home")){
		var $arrow 			= $('.arrow'),
			$browserNotice 	= $('#browser-note'),
			$close 			= $('#browser-note .close'),
			$arrowContainer = $('.arrow-container');
		
		/* Arrow function */
		$arrow.on('click', function(){
			$('html, body').animate({
				scrollTop: $('#about').offset().top
			}, 900);
		});

		/* Make Arrow fade out on scroll down */
		inView('#about-description').on('enter', function(el){
			$arrow.animate({
				opacity: 0
			}, 500 );
			$arrowContainer.animate({
				top: 0,
				bottom: 0,
				backgroundColor: '#CDD8F9'
			}, 500 );
		});	
		inView('#about-description').on('exit', function(el){
			$arrow.animate({
				opacity: 1
			}, 500 );
			$arrowContainer.removeAttr('style');
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
	}
	/* End Home Page JS */

	/* Portfolio JS */
	
});