/*global jQuery */
(function ($) {
    "use strict";

    /*===============================
        ----- JS Index -----

    01. Background Image JS
    02. Off Canvas JS
    03. Responsive Slicknav JS
    04. Counter To Up JS
    05. Sticky Header JS
    06. Scroll To Top JS
    07. Skills Bar JS

    00. All Slick Slider Active

    ==================================*/

    jQuery(document).ready(function ($) {
        /*--------------------------
            01. Background Image JS
        ---------------------------*/
        var bgSelector = $("[data-bg]");
        bgSelector.each(function (index, elem) {
            var element = $(elem),
                bgSource = element.data('bg');
            element.css('background-image', 'url(' + bgSource + ')');
        });


        /*------------------------
            02. Off Canvas JS
        --------------------------*/
        var canvasWrapper = $(".off-canvas-cog");
        $(".btn-cog").on('click', function () {
            canvasWrapper.addClass('active');
            $("body").addClass('fix');
        });

        $(".off-canvas-overlay, .btn-close").on('click', function () {
            $(".off-canvas-wrapper").removeClass('active');
            $("body").removeClass('fix');
        });


        /*------------------------------
            03. Responsive Slicknav JS
        --------------------------------*/
        $('.main-menu').slicknav({
            appendTo: '.res-mobile-menu',
            closeOnClick: true,
            removeClasses: true,
            closedSymbol: '+',
            openedSymbol: '-'
        });

        var resCanvasWrapper = $(".off-canvas-menu");
        $(".btn-menu").on('click', function () {
            resCanvasWrapper.addClass('active');
            $("body").addClass('fix');
        });


        /*--------------------------
           04. Counter To Up JS
        ----------------------------*/
        var counterId = $('.counter');
        if (counterId.length) {
            counterId.counterUp({
                delay: 10,
                time: 1000
            });
        }

        /*------------------------
          06. Scroll To Top JS
         -------------------------*/
        $(".btn-scroll-top").on('click', function () {
            $('html, body').animate({
                scrollTop: 0
            }, 1500);
        });


        /*------------------------
           07. Skills Bar JS
        -------------------------*/
        var skillsBar = $(".skill-progress-bar");
        skillsBar.appear(function () {
            skillsBar.each(function (index, elem) {
                var elementItem = $(elem),
                    skillBarAmount = elementItem.data('skill-amount');
                elementItem.animate({
                    width: skillBarAmount
                }, 100);
                elementItem.closest('.single-skill-bar').find('.skill-percent').text(skillBarAmount);
            });
        });


        /*--------------------------
           08. Instagram Feed JS
        ----------------------------*/
        // User Changeable Access
        var activeId = $("#instafeed"),
            myTemplate = '<div class="instagram-item"><a href="{{link}}" target="_blank" id="{{id}}"><img src="{{image}}" /></a></div>';

        if (activeId.length) {
            var userID = activeId.attr('data-userid'),
                accessTokenID = activeId.attr('data-accesstoken'),

                userFeed = new Instafeed({
                    get: 'user',
                    userId: userID,
                    accessToken: accessTokenID,
                    resolution: 'standard_resolution',
                    template: myTemplate,
                    sortBy: 'least-recent',
                    limit: 9,
                    links: false
                });
            userFeed.run();
        }


        /*--------------------------
          09. Ajax Contact Form JS
         ---------------------------*/
        var form = $('#contact-form');
        var formMessages = $('.form-message');

        $(form).submit(function (e) {
            e.preventDefault();
            var formData = form.serialize();
            $.ajax({
                type: 'POST',
                url: form.attr('action'),
                data: formData
            }).done(function (response) {
                // Make sure that the formMessages div has the 'success' class.
                $(formMessages).removeClass('alert alert-danger');
                $(formMessages).addClass('alert alert-success fade show');

                // Set the message text.
                formMessages.html("<button type='button' class='close' data-dismiss='alert'>&times;</button>");
                formMessages.append(response);

                // Clear the form.
                $('#contact-form input,#contact-form textarea').val('');
            }).fail(function (data) {
                // Make sure that the formMessages div has the 'error' class.
                $(formMessages).removeClass('alert alert-success');
                $(formMessages).addClass('alert alert-danger fade show');

                // Set the message text.
                if (data.responseText !== '') {
                    formMessages.html("<button type='button' class='close' data-dismiss='alert'>&times;</button>");
                    formMessages.append(data.responseText);
                } else {
                    $(formMessages).text('Oops! An error occurred and your message could not be sent.');
                }
            });
        });


        /*--------------------------------
            --. All Slick Slider Active
        ----------------------------------*/

        // Testimonial Slider JS
        $(".testimonial-content").slick({
            slidesToShow: 2,
            vertical: true,
            verticalSwiping: true,
            arrows: false,
            dots: true,
            appendDots: ".testimonial-carousel-dots",
            responsive: [
                {
                    breakpoint: 993,
                    settings: {
                        slidesToShow: 1,
                        appendDots: ".testimonial-content",
                    }
                }
            ]
        });

        // Home 2 Testimonial Content Slider JS
        $(".testimonial-content--2").slick({
            slidesToShow: 1,
            arrows: true,
            appendArrows: '.testimonial-arrows-2',
            prevArrow: '<button class="slick-prev"><i class="mdi mdi-arrow-left"></i></button>',
            nextArrow: '<button class="slick-next"><i class="mdi mdi-arrow-right"></i></button>',
            dots: false,
            asNavFor: '.testimonial-thumbnail'
        });

        // Home 2 Testimonial Thumbnail Slider JS
        $(".testimonial-thumbnail").slick({
            slidesToShow: 1,
            arrows: false,
            dots: false,
            asNavFor: '.testimonial-content--2',
            focusOnSelect: true
        });

        // Team Slider JS
        $(".team-content-wrap").slick({
            slidesToShow: 4,
            arrows: false,
            autoplay: true,
            dots: true,
            responsive: [
                {
                    breakpoint: 1550,
                    settings: {
                        slidesToShow: 3,
                    }
                },
                {
                    breakpoint: 700,
                    settings: {
                        slidesToShow: 2,
                    }
                },
                {
                    breakpoint: 550,
                    settings: {
                        slidesToShow: 1,
                    }
                }
            ]
        });

        // Brand Logo Slider JS
        $(".brand-logo-content").slick({
            slidesToShow: 4,
            arrows: false,
            autoplay: true,
            responsive: [
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 401,
                    settings: {
                        slidesToShow: 1
                    }
                }
            ]
        });

        // Service Details Slider
        $(".service-details-thumb").slick({
            slidesToShow: 1,
            arrows: false,
            dots: true
        });
    });

    $(window).on('scroll', function () {
        /*--------------------------
            05. Sticky Header JS
        ----------------------------*/
        if ($(window).scrollTop() >= 350) {
            $(".header-area").addClass('sticky');
        } else {
            $('.header-area').removeClass('sticky');
        }

        //Scroll top Hide Show
        if ($(window).scrollTop() >= 400) {
            $('.btn-scroll-top').addClass('show');
        } else {
            $('.btn-scroll-top').removeClass('show');
        }
    });

    jQuery(window).on('load', function () {

    }); // End Load Function
}(jQuery));