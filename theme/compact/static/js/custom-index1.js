(function($) { "use strict";
	
	// --------------------------------------------------
    // owlCarousel
    // --------------------------------------------------

    /* latest-projects-items */
    $("#latest-projects-items").owlCarousel({
        items: 3,
        itemsCustom : false,
        itemsDesktop : [1199, 3],
        itemsDesktopSmall : [979, 2],
        itemsTablet : [768, 2],
        itemsTabletSmall : false,
        itemsMobile : [479, 1],
        navigation: true,
        pagination: false,
        navigationText: [
          "<i class='fa fa-angle-left'></i>",
          "<i class='fa fa-angle-right'></i>"
        ],
    });

    /* latest-news-items */
    $(".latest-news-items").owlCarousel({
        items : 1,
        singleItem:true,    
        navigation : false,
        pagination : false,
        autoPlay : false,
        navigationText: [
          "<i class='fa fa-angle-left'></i>",
          "<i class='fa fa-angle-right'></i>"
        ],
        slideSpeed : 400,
    });    

    // Custom Navigation owlCarousel - ".latest-news-items"
    $(".latest-next").on("click", function() {
        $(this).parent().parent().find('.latest-news-items').trigger('owl.next');
    });
    $(".latest-prev").on("click", function() {
        $(this).parent().parent().find('.latest-news-items').trigger('owl.prev');
    });  

    /*testimonials-slider */
    $(".testimonials-slider").owlCarousel({
        items : 1,
        singleItem:true,    
        navigation : false,
        pagination : false,
        autoPlay : false,
        slideSpeed : 400,
    }); 

    // Custom Navigation owlCarousel - ".testimonials-slider"
    $(".testi-next").on("click", function() {
        $(this).parent().parent().find('.testimonials-slider').trigger('owl.next');
    });
    $(".testi-prev").on("click", function() {
        $(this).parent().parent().find('.testimonials-slider').trigger('owl.prev');
    });

    
})(jQuery); 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 





	