(function($) { "use strict";
	
	// --------------------------------------------------
    // owlCarousel
    // --------------------------------------------------   

    /* latest-news-items */
    $(".latest-news-items-3").owlCarousel({
        items : 2,
        itemsDesktop : [1199, 2],
        itemsDesktopSmall : [979, 2],
        itemsTablet : [768, 1],
        itemsTabletSmall : false,
        itemsMobile : [479, 1],
		pagination : false,
		autoPlay : false,
		slideSpeed : 300,
    });    

    // Custom Navigation owlCarousel - ".latest-news-items"
    $(".latest-next").on("click", function() {
        $(this).parent().parent().parent().find('.latest-news-items-3').trigger('owl.next');
    });
    $(".latest-prev").on("click", function() {
        $(this).parent().parent().parent().find('.latest-news-items-3').trigger('owl.prev');
    });  

    /*testimonials-slider */
    $(".testimonials-slider-3").owlCarousel({
        items : 3,
        itemsDesktop : [1199, 2],
        itemsDesktopSmall : [979, 2],
        itemsTablet : [768, 2],
        itemsTabletSmall : [768, 1],
        itemsMobile : [479, 1], 
		pagination : false,
		autoPlay : false,
		slideSpeed : 300
    }); 

    // Custom Navigation owlCarousel - ".testimonials-slider"
    $(".testi-next").on("click", function() {
        $(this).parent().parent().parent().find('.testimonials-slider-3').trigger('owl.next');
    });
    $(".testi-prev").on("click", function() {
        $(this).parent().parent().parent().find('.testimonials-slider-3').trigger('owl.prev');
    });

     /*client-logo-slider */
    $("#client-logo").owlCarousel({
        items : 6,
        itemsCustom : false,
        itemsDesktop : [1199, 5],
        itemsDesktopSmall : [979, 4],
        itemsTablet : [768, 2],
        itemsTabletSmall : [768, 3],
        itemsMobile : [479, 1],
		pagination : false,
		autoPlay : false,
		slideSpeed : 300
    }); 

    // Custom Navigation owlCarousel - ".testimonials-slider"
    $(".partner-next").on("click", function() {
        $(this).parent().parent().find('#client-logo').trigger('owl.next');
    });
    $(".partner-prev").on("click", function() {
        $(this).parent().parent().find('#client-logo').trigger('owl.prev');
    });
    
    /* Portfolio Sorting */
    jQuery(document).ready(function($){
        (function ($) { 
        
            var container = $('#projects-grid');
        
            function getNumbColumns() { 
                var winWidth = $(window).width(), 
                    columnNumb = 1;                
                if (winWidth > 1500) {
                    columnNumb = 4;
                } else if (winWidth > 1200) {
                    columnNumb = 3;
                } else if (winWidth > 900) {
                    columnNumb = 2;
                } else if (winWidth > 600) {
                    columnNumb = 2;
                } else if (winWidth > 300) {
                    columnNumb = 1;
                }
                
                return columnNumb;
            }
            
            function setColumnWidth() { 
                var winWidth = $(window).width(), 
                    columnNumb = getNumbColumns(), 
                    postWidth = Math.floor(winWidth / columnNumb);

            }
            
            $('#portfolio-filter #filter a').click(function () { 
                var selector = $(this).attr('data-filter');
                
                $(this).parent().parent().find('a').removeClass('current');
                $(this).addClass('current');
                
                container.isotope( { 
                    filter : selector 
                });
                
                setTimeout(function () { 
                    reArrangeProjects();
                }, 300);
                
                
                return false;
            });
            
            function reArrangeProjects() { 
                setColumnWidth();
                container.isotope('reLayout');
            }
            
            container.imagesLoaded(function () { 
                setColumnWidth();
                
                
                container.isotope( { 
                    itemSelector : '.project-item', 
                    layoutMode : 'masonry', 
                    resizable : false 
                } );
            } );
            
            $(window).on('debouncedresize', function () { 
                reArrangeProjects();                
            } );            
        } )(jQuery);
    } );

    /* DebouncedResize Function */
    (function ($) { 
        var $event = $.event, 
            $special, 
            resizeTimeout;
        
        
        $special = $event.special.debouncedresize = { 
            setup : function () { 
                $(this).on('resize', $special.handler);
            }, 
            teardown : function () { 
                $(this).off('resize', $special.handler);
            }, 
            handler : function (event, execAsap) { 
                var context = this, 
                    args = arguments, 
                    dispatch = function () { 
                        event.type = 'debouncedresize';
                        
                        $event.dispatch.apply(context, args);
                    };
                
                
                if (resizeTimeout) {
                    clearTimeout(resizeTimeout);
                }
                
                
                execAsap ? dispatch() : resizeTimeout = setTimeout(dispatch, $special.threshold);
            }, 
            threshold : 150 
        };
    } )(jQuery);

    /* project-details */
    $('.project-item').hover(function() {
        $(this).find('.project-details').stop(true, true).fadeIn().find('.folio-title').stop(true, true).animate({'top':20}).end().find('.folio-cate').stop(true, true).animate({'bottom': 20});
    }, function() {
        $(this).find('.project-details').stop(true, true).fadeOut().find('.folio-title').stop(true, true).animate({'top':-20}).end().find('.folio-cate').stop(true, true).animate({'bottom': -20});
    });

})(jQuery); 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 





	