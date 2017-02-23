jQuery(function($) {
    $('.accordion dt').on('click', function() {
        $(this).toggleClass('active').next('dd').toggleClass('active');
    });

    /*Parallax Scrolling*/
    var isMobile = (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent));
    if (!isMobile) {
        $(document).on('scroll', function() {
            $('.parallax').each(function() {
                var eTop = $(this).offset().top;
                var eTopScreen = eTop - $(window).scrollTop();
                $(this).css(
                    'background-position', '49% ' + eTopScreen / 2.0 + "px"
                )
            });
        });
    }
    else {
        $('.parallax').each(function() {
            $(this).addClass('mobile');
        });
    }

    /* ---- Product PDF Popup ---- */
    var productOverlay = $('.product-spec-popup');

    $('.catalog-product-view .icon-info-sign.popup').on('click', function() {
        var that = $(this);
        productOverlay.addClass('active');

        productOverlay.find('.pdf').eq($('.icon-info-sign.popup').index(that)).removeClass('hidden');
    });

    productOverlay.on('click', '.overlay, .close-button', function() {
        productOverlay.removeClass('active');
        productOverlay.find('.pdf:not(.hidden)').addClass('hidden');
    });

    var slider = $(".list-slider");

    slider.owlCarousel({
        items: 1,
        loop: true,
        nav: true,
        autoplay: true,
        autoplayTimeout: 10000,
        autoplayHoverPause: true
    });
});
