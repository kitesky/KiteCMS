(function($) { "use strict";

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

    /* Intro Video*/
    $(document).ready(function(){
        $("a.btn-intro-video").click(function(e) {
            e.preventDefault();            
            $("#overlay-video").hide();
            $("#thevideo").show();
            $("#someFrame").attr("src", $(this).attr("href"));
        })
    });
    
})(jQuery); 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 





	