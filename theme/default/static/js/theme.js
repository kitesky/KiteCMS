(function($) {
    "use strict"; // Start of use strict

    // Search
    $(".search-trigger").on('click', function() {
        $(".search-bar").addClass("active");
    });

    $(".search-close").on('click', function() {
        $(".search-bar").removeClass("active");
    });

    //post slider
    $('.post-single-slider').slick({
        dots: true,
        arrows: false,
        infinite: true,
        speed: 300,
        slidesToShow: 1,
        slidesToScroll: 1
    });

    $('#toggle-view li').on('click', function() {
        var text = $(this).children('div.toggle-panel');

        if (text.is(':hidden')) {
            text.slideDown('200');
            $(this).children('span').addClass("fa-angle-up").removeClass("fa-angle-down");
        } else {
            text.slideUp('200');
            $(this).children('span').addClass("fa-angle-down").removeClass("fa-angle-up");
        }
    });

    $('.home-slider').slick({
        dots: false,
        infinite: true,
        speed: 300,
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: true,
        prevArrow: $('.hs-prev'),
        nextArrow: $('.hs-next')
    });

	
    $(".tabs-menu a").on('click', function(event) {
        event.preventDefault();
        $(this).parent().addClass("current");
        $(this).parent().siblings().removeClass("current");
        var tab = $(this).attr("href");
        $(".tab-contents").not(tab).css("display", "none");
        $(tab).fadeIn();
    });
	
    $(".nav-trigger").on('click', function() {
        $("#sidebar-wrapper").addClass("active");
        $("body").addClass("sidemenu-active");
    });
	
    $(".body-overlay").on('click', function() {
        $("nav-trigger").removeClass("active");
        $("#sidebar-wrapper").removeClass("active");
        $("body").removeClass("sidemenu-active");
    });
		

})(jQuery); // End of use strict