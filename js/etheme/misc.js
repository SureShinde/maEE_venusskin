function hideImage(img) {
    jQuery(img).animate({
        'opacity' : 0
    },150);
}

function showImage(img) {

    jQuery(img).animate({
        'opacity' : 1
    },150);
}

function productHover(){
    jQuery('.img-hided').hover(function(){
	    if (window.innerWidth > 979) {
		    jQuery(this).animate({
			    'opacity' : 1
		    },200);
	    }
    }, function(){
	    if (window.innerWidth > 979) {
		    jQuery(this).animate({
			    'opacity' : 0
		    },200);
	    }
    });
}

function qtyDown(id){
	var qtyEl = document.getElementById('cart[' + id + '][qty]');
	var qty = qtyEl.value;
	if( qty == 1) {
		jQuery('.box-down' + id).prop('disabled', true);
	}
	if( !isNaN( qty ) && qty > 0 ){
		qtyEl.value--;
	}
	return false;
}

function qtyUp(id){
	var qtyEl = document.getElementById('cart[' + id + '][qty]');
	var qty = qtyEl.value;
	if( !isNaN( qty )) {
		qtyEl.value++;
	}
	jQuery('.box-down' + id).prop('disabled', false);
	return false;
}

function setAjaxData(data){
    if (data.status == 'ERROR'){
        console.log(data.message);
    }else{
        if (jQuery('.block-cart')){
            jQuery('.block-cart').replaceWith(data.sidebar);
        }
        if (jQuery('.top-cart')){
            jQuery('.top-cart').replaceWith(data.topcart);
        }
        jQuery.fancybox.close();
    }
}



jQuery(function($) {

    productHover();

    jQuery('#top-link').click(function(e) {
            jQuery('html, body').animate({scrollTop:0}, 'slow');
            return false;
    });

    /* Fixed menu */

    jQuery(window).scroll(function(){
    	var fixedHeader = jQuery('.fixed-header-area');
    	var scrollTop = jQuery(this).scrollTop();
    	var headerHeight = jQuery('.header-top').height() + jQuery('.header-container').height();

    	if(scrollTop > headerHeight){
    		if(!fixedHeader.hasClass('fixed-already')) {
		    	fixedHeader.stop().addClass('fixed-already');
    		}
    	}else{
    		if(fixedHeader.hasClass('fixed-already')) {
		    	fixedHeader.stop().removeClass('fixed-already');
    		}
    	}
    });

    // Nice Scroll
    //jQuery("html").niceScroll();

    /* Tabs
    -------------------------------------------------------------- */
    var tabs = jQuery('.tabs');
    jQuery('.tabs > p > a').unwrap('p');
    tabs.each(function(){
    	var currTab = jQuery(this);
	    currTab.find('.tab-title').click(function(e){
	        if(jQuery(this).hasClass('opened')){
	            jQuery(this).removeClass('opened').next().hide();
	            currTab.addClass('closed');
	        }else{
	            currTab.find('.tab-title').each(function(){
	                jQuery(this).removeClass('opened').next().hide();
	            });
	            jQuery(this).addClass('opened').next().show();
	        }
	    });
    });

    /* Mobile navigation
    -------------------------------------------------------------- */

    var navList = jQuery('div.menu > ul').clone();
    jQuery('div.menu > ul').removeClass(' menu-type-side menu-type-default');
    var etOpener = '<span class="open-child">(open)</span>';
    navList.removeClass('menu').addClass('et-mobile-menu');

    headerLinks = jQuery('#header-links ul.links li').clone();
    lastLink = navList.children('li:last-child');
    lastLink.after(headerLinks);

	navList.find('li:has(ul)',this).each(function() {
		jQuery(this).prepend(etOpener);
	})

    navList.find('.open-child').toggle(function(){
        jQuery(this).parent().addClass('over').find('>ul').slideDown(200);
    },function(){
        jQuery(this).parent().removeClass('over').find('>ul').slideUp(200);
    });

    jQuery('.header-container').after(navList[0]);
    jQuery('.mobile-menutype-side .et-mobile-menu').wrap('<div class="side-menu-wrap" />');
    jQuery('.et-mobile-menu').before('<div id="close-side-nav"></div>');
    jQuery('div.menu').after('<span class="et-menu-title"><i class="icon-reorder"></i></span>');

    jQuery('.et-menu-title').click(function(){
	    toggleMobileMenu();
    });
    jQuery('#close-side-nav').click(function(){
	    toggleMobileMenu();
    });

    jQuery('.mobile-menutype-default .et-menu-title').toggle(function(){
        jQuery('.et-mobile-menu').slideDown(200);
    },function(){
        jQuery('.et-mobile-menu').slideUp(200);
    });

    function toggleMobileMenu(){
	    if(jQuery('body').hasClass('mobile-menu-shown')) {
	        jQuery('body').removeClass('mobile-menu-shown');
	    }else {
	        jQuery('body').addClass('mobile-menu-shown');
	    }
    }

    // Review Tab

    jQuery('.rating-links a, .no-rating a').attr('href', '#product_review_tab')
        .click(function(){
	    jQuery('.tabs .opened').removeClass('opened');
	    jQuery('.tab-content').hide();
	    jQuery('#product_review_tab').addClass('opened');
	    jQuery('#product_review_tab_contents').show();
    });

	// Product detail/update qty control
	$('input[type=text].qty').on('keyup', function () {
		if ($(this).val() <= 0 || isNaN($(this).val())) {
			$('button.add-to-cart').prop('disabled', true);
			$('input[name=qty-down]').prop('disabled', true);
		} else {
			$('button.add-to-cart').prop('disabled', false);
			$('input[name=qty-down]').prop('disabled', false);
		}
	});

	$('input[name=qty-down]').on('click', function () {
		var $qty = $('input[type=text].qty');
		var currentVal = parseInt($qty.val());
		if (!isNaN(currentVal) && currentVal > 0) {
			$qty.val(currentVal - 1);
		} else {
			$qty.val(0);
		}

		if ($qty.val() == 0) {
			$('button.add-to-cart').prop('disabled', true);
			$(this).prop('disabled', true);
		}
	});

	$('input[name=qty-up]').on('click', function () {
		var $qty = $('input[type=text].qty');
		var currentVal = parseInt($qty.val());
		if (!isNaN(currentVal)) {
			$qty.val(currentVal + 1);
		} else {
			$qty.val(1);
		}

		if ($qty.val() > 0) {
			$('button.add-to-cart').prop('disabled', false);
			$('input[name=qty-down]').prop('disabled', false);
		}
	});

	// Carousel Control //
	$('span.carousel-control.prev').on('click', function () {
		$this = $(this);
		if ($this.hasClass('animating')) { return; }
		$this.addClass('animating');
		$('span.carousel-control.next').addClass('animating');

		var currentTab = $('.viewport .category-view .current-row');
		if (currentTab.is(':first-child')) {
			currentTab.siblings().last().addClass('prev-row');
		} else {
			currentTab.prev().addClass('prev-row');
		}

		var prevTab = $('.viewport .category-view .prev-row');

		setTimeout(function () {
			currentTab.addClass('next-row').removeClass('current-row');
			prevTab.addClass('current-row').removeClass('prev-row');
		}, 50);
		setTimeout(function () {
			currentTab.removeClass('next-row');
			$this.removeClass('animating');
			$('span.carousel-control.next').removeClass('animating');
		}, 1000);
	});

	$('span.carousel-control.next').on('click', function () {
		$this = $(this);
		if ($this.hasClass('animating')) { return; }
		$this.addClass('animating');
		$('span.carousel-control.prev').addClass('animating');

		var currentTab = $('.viewport .category-view .current-row');
		if (currentTab.is(':last-child')) {
			currentTab.siblings().first().addClass('next-row');
		} else {
			currentTab.next().addClass('next-row');
		}

		var nextTab = $('.viewport .category-view .next-row');

		setTimeout(function () {
			currentTab.addClass('prev-row').removeClass('current-row');
			nextTab.addClass('current-row').removeClass('next-row');
		}, 50);
		setTimeout(function () {
			currentTab.removeClass('prev-row');
			$this.removeClass('animating');
			$('span.carousel-control.prev').removeClass('animating');
		}, 1000);
	});

}); // end ready
