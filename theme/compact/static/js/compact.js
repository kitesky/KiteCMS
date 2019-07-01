jQuery(document).ready(function() {
    "use strict";

    // Preload images
    $('document').ready(function() {
        var $container = $('body');
        $container.imagesLoaded( function() {
            $('#images-preloader').hide();
        });
    });

    //Tooltip
    $(document).ready(function() {
        $(".tipped").tipper();
    }); 
    
    $(window).width();
    $("nav.site-desktop-menu > ul li").hover(function() {
        $(this).find("> ul").stop(true, true).slideDown(200)
    }, function() {
        $(this).find("> ul").stop(true, true).slideUp(200)
    });

    $("#desktop-menu > ul").children().clone().appendTo($("#mobile-menu > ul")), $("#show-mobile-menu").on("click", function() {
        $(this).toggleClass("clicked"), $("#mobile-menu > ul").stop(true, true).slideToggle()
    }); 

    $("nav.site-mobile-menu > i").on("click", function() {
        $(this).parent().find("> ul").slideToggle();
    });

    $("nav.site-mobile-menu > ul li").each(function() {
        0 != $(this).find("> ul").length && $('<i class="fa fa-plus"></i>').prependTo($(this))
    }); 

    $("nav.site-mobile-menu > ul li i").on("click", function() {
        $(this).toggleClass("fa-minus").parent().find("> ul").slideToggle()
    }); 

    $(".myModal-search").css("top", "50%"), $("#btn-close-canvasmenu").on("click", function() {
        $("#offcanvas-menu").stop().animate({
            right: "-260px"
        }, 300), $("#btn-offcanvas-menu").removeClass("isLeft")
    });

    $("#btn-offcanvas-menu").on("click", function(a) {
        return a.preventDefault(), $(this).hasClass("isLeft") ? $("#offcanvas-menu").stop().animate({
            right: "-260px"
        }, 300) : $("#offcanvas-menu").stop().animate({
            right: "0px"
        }, 300), $(this).toggleClass("isLeft"), false
    });

    // --------------------------------------------------
    // Sticky Header
    // --------------------------------------------------
    var a = false,
        b = null;
    $(window).scroll(function() {
        $(window).scrollTop() > 200 ? a || (b = new Waypoint.Sticky({
            element: $("#sticked-menu")
        }), a = true, $("#sticked-menu").addClass("animated slideInDown")) : (b && (b.destroy(), a = false), $("#sticked-menu").removeClass("animated slideInDown"))
    }); 


    // --------------------------------------------------
    // magnificPopup
    // --------------------------------------------------

    $(".zoom-gallery").magnificPopup({
        delegate: "a",
        type: "image",
        closeOnContentClick: false,
        closeBtnInside: false,
        mainClass: "mfp-with-zoom mfp-img-mobile",
        image: {
            verticalFit: true,
            titleSrc: function(a) {
                return a.el.attr("title")
            }
        },
        gallery: {
            enabled: true
        },
        zoom: {
            enabled: true,
            duration: 300,
            opener: function(a) {
                return a.find("img")
            }
        }
    });

    $(".folio").magnificPopup({
        type: "image"
    });

    $(".popup-youtube, .popup-vimeo, .popup-gmaps").magnificPopup({
        disableOn: 700,
        type: "iframe",
        mainClass: "mfp-fade",
        removalDelay: 160,
        preloader: false,
        fixedContentPos: false
    });

    // --------------------------------------------------
    // paralax background
    // --------------------------------------------------
    var $window = jQuery(window);
    jQuery('section[data-type="background"]').each(function(){
    var $bgobj = jQuery(this); // assigning the object
                    
        jQuery(window).scroll(function() {
            var yPos = -($window.scrollTop() / $bgobj.data('speed')); 
            var coords = '50% '+ yPos + 'px';
            $bgobj.css({ backgroundPosition: coords });         
        });
    });
    document.createElement("article");
    document.createElement("section");

    jQuery(window).load(function() {
        // --------------------------------------------------
        // Custom height section
        // --------------------------------------------------
        var width = $(window).width(); 
        if (width >= 767){         
             // $('.latest-projects').each(function(){ 
             //     $(this).find(".latest-projects-intro").css("height",$(this).find(".latest-projects-intro").parent().css("height"));
             // });

            jQuery('.latest-projects').each(function(){ 
                jQuery(this).find(".latest-projects-intro").css("height",jQuery(this).find(".latest-projects-intro").parent().css("height"));
            });
        }
    });
    
    /* project-details */
    $('.latest-projects-wrapper .item').hover( function() {
        $(this).find('.project-details').stop(true, true).fadeIn().find('.folio-title').stop(true, true).animate({'top':20}).end().find('.folio-cate').stop(true, true).animate({'bottom': 20});
    }, function() {
        $(this).find('.project-details').stop(true, true).fadeOut().find('.folio-title').stop(true, true).animate({'top':-20}).end().find('.folio-cate').stop(true, true).animate({'bottom': -20});
    });

    // --------------------------------------------------
    // Back To Top
    // --------------------------------------------------
    var offset = 450;
    var duration = 500;
    jQuery(window).scroll(function() {
        if (jQuery(this).scrollTop() > offset) {
            jQuery('#to-the-top').fadeIn(duration);
        } else {
            jQuery('#to-the-top').fadeOut(duration);
        }
    });
            
    jQuery('#to-the-top').on("click", function(event) {
        event.preventDefault();
        jQuery('html, body').animate({scrollTop: 0}, duration);
        return false;
    });  

});