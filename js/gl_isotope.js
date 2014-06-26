// Isotope blog
var jQueryservice_style1 = jQuery('.gallery1');

jQuery(window).load(function () {
    // Initialize Isotope
    jQueryservice_style1.isotope({
        itemSelector: '.wl-gallery'
    });
});

jQuery(window).smartresize(function () {
   
    jQueryservice_style1.isotope('reLayout');

});