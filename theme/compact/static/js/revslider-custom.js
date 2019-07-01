jQuery(document).ready(function () {
	'use strict'; // use strict mode

    $('#revolution-slider').revolution({
        delay: 7000,
        startwidth: 1170,
        startheight: 590,
        hideThumbs: 10,
        fullWidth: "off",
        fullScreen: "off",
        fullScreenOffsetContainer: "",
        touchenabled: "on",
        navigationType: "none",
        onHoverStop: "off",
    });

    $('#revolution-slider-2').revolution({
        delay: 7000,
        startwidth: 1170,
        startheight: 590,
        hideThumbs: 10,
        fullWidth: "off",
        fullScreen: "off",
        fullScreenOffsetContainer: "",
        touchenabled: "on",
        navigationType: "none",
        onHoverStop: "on",
    });

    $('#revolution-slider-3').revolution({
        delay: 7000,
        startwidth: 1170,
        startheight: 590,
        hideThumbs: 10,
        fullWidth: "off",
        fullScreen: "off",
        fullScreenOffsetContainer: "",
        touchenabled: "on",
        navigationType: "none",
        onHoverStop: "off",
    });
});