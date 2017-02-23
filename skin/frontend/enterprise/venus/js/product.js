jQuery(document).ready(function($) {
    $('.vip-banner, .vip-banner-button').on('click', function () {
        LiteModal.create({
            overlayClose: true,
            buttonClose: true,
            content: $('.modal-content').html()
        });
    });

    $('#product-tabs li').on('click', function () {
        var $that        = $(this),
            $description = $('.product-tabs__content .product-tabs__item:eq(' + $that.index() + ')');

        $that.addClass('active');
        $that.siblings().removeClass('active');
        $description.addClass('active');
        $description.siblings().removeClass('active');
    });

    $('.reviews-form__star-label').click(function() {
        var $this    = $(this),
            wrapper  = $this.parents('.reviews-form__stars'),
            parent   = $this.parents('.reviews-form__star'),
            position = parent.find('input').data('index');

        wrapper.find('input').removeClass('checked');
        for (var i = 0; i <= position; i++) {
            wrapper.find('.reviews-form__star input[data-index="' + i + '"]').addClass('checked');
        }
    });

    $('.ratings-summary__actions-button--write').click(function(event) {
        $("html, body").animate({
            scrollTop: $('#review-form-wrapper').offset().top
        }, 1000);
    });

    $('.ratings-summary__actions-button--read').click(function(event) {
        $("html, body").animate({
            scrollTop: $('#reviews-list').offset().top
        }, 1000);
    });

    $('#recurring_period').change(function(event) {
        var $this = $(this)
            value = $this.val(),
            form  = $('#product_addtocart_form');

        if (value.indexOf('http') !== -1) {
            form.attr('action', value);
        }
        else {
            form.attr('action', form.data('current-action'));
        }
    });

});
