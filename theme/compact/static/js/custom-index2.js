(function($) { "use strict";
	
    // --------------------------------------------------
    // owlCarousel
    // --------------------------------------------------
    $("#latest-projects-items-2").owlCarousel({
        items: 4,
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

    /* latest-news-items-2 */
    $(".latest-news-items-2").owlCarousel({
        items : 2,
        itemsCustom : false,
        itemsDesktop : [1199, 2],
        itemsDesktopSmall : [979, 2],
        itemsTablet : [768, 2],
        itemsTabletSmall : [768, 1],
        itemsMobile : [479, 1],
        singleItem:false,    
        navigation : true,
        pagination : false,
        autoPlay : false,
        slideSpeed : 400,
        navigationText: [
          "<i class='fa fa-angle-left'></i>",
          "<i class='fa fa-angle-right'></i>"
        ],
    });  

    $(".testimonials-slider-2").owlCarousel({
        items : 1,
        singleItem:true,    
        navigation : true,
        pagination : false,
        autoPlay : false,
        slideSpeed : 400,
        navigationText: [
          "<i class='fa fa-angle-left'></i>",
          "<i class='fa fa-angle-right'></i>"
        ],
    }); 

    /*Clients Logo */
    $("#client-logo").owlCarousel({
        items : 6,
        itemsCustom : false,
        itemsDesktop : [1199, 5],
        itemsDesktopSmall : [979, 4],
        itemsTablet : [768, 2],
        itemsTabletSmall : [768, 3],
        itemsMobile : [479, 1],
        singleItem:false,    
        navigation : false,
        pagination : true,
        autoPlay : false,
        slideSpeed : 400,
    });   
 
})(jQuery); 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 





	