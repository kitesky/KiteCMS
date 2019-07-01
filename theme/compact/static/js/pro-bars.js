//	ProBars v1.0, Copyright 2014, Joe Mottershaw, https://github.com/joemottershaw/
//	===============================================================================

	function animateProgressBar() {
		$('.pro-bar').each(function(i, elem) {
			var	elem = $(this),
				percent = elem.attr('data-pro-bar-percent'),
				delay = elem.attr('data-pro-bar-delay');

			if (!elem.hasClass('animated'))
				elem.css({ 'width' : '0%' });

			if (elem.visible(true)) {
				setTimeout(function() {
					elem.animate({ 'width' : percent + '%'}, 2000, 'easeInOutExpo').addClass('animated');
				}, delay);
			} 
		});
	}

	$(document).ready(function() {
		animateProgressBar();
	});

	$(window).resize(function() {
		animateProgressBar();
	});

	$(window).scroll(function() {
		animateProgressBar();

		if ($(window).scrollTop() + $(window).height() == $(document).height())
			animateProgressBar();
	});