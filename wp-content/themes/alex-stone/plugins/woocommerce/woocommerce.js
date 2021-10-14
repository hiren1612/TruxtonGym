/* global jQuery:false */
/* global ALEX_STONE_STORAGE:false */

(function() {
	"use strict";

	jQuery(document).on('action.ready_alex_stone', function() {
		
		// Change display mode
		jQuery('.woocommerce,.woocommerce-page').on('click', '.alex_stone_shop_mode_buttons a', function(e) {
			var mode = jQuery(this).hasClass('woocommerce_thumbs') ? 'thumbs' : 'list';
			alex_stone_set_cookie('alex_stone_shop_mode', mode, 365);
			jQuery(this).siblings('input').val(mode).parents('form').get(0).submit();
			e.preventDefault();
			return false;
		});
		// Add buttons to quantity on first run
		if (jQuery('.woocommerce div.quantity .q_inc,.woocommerce-page div.quantity .q_inc').length == 0) {
			var woocomerce_inc_dec = '<span class="q_inc"></span><span class="q_dec"></span>';
			jQuery('.woocommerce div.quantity,.woocommerce-page div.quantity').append(woocomerce_inc_dec);
			jQuery('.woocommerce div.quantity,.woocommerce-page div.quantity').on('click', '>span', function(e) {
				woocomerce_inc_dec_click(jQuery(this));
				e.preventDefault();
				return false;
			});
		}
		// Add buttons to quantity after the cart is updated
		jQuery(document.body).on('updated_wc_div', function() {
			if (jQuery('.woocommerce div.quantity .q_inc,.woocommerce-page div.quantity .q_inc').length == 0) {
				jQuery('.woocommerce div.quantity,.woocommerce-page div.quantity').append(woocomerce_inc_dec);
				jQuery('.woocommerce div.quantity,.woocommerce-page div.quantity').on('click', '>span', function(e) {
					woocomerce_inc_dec_click(jQuery(this));
					e.preventDefault();
					return false;
				});
			}
		});
		// Inc/Dec quantity on buttons inc/dec
		function woocomerce_inc_dec_click(button) {
			var f = button.siblings( 'input' );
			if (button.hasClass( 'q_inc' )) {
				f.val( ( f.val() == '' ? 0 : parseInt( f.val(), 10 ) ) + 1 ).trigger( 'change' );
			} else {
				f.val( Math.max( 0, ( f.val() == '' ? 0 : parseInt( f.val(), 10 ) ) - 1 ) ).trigger( 'change' );
			}
		}
		
		// Make buttons from links
		var wishlist = jQuery('.woocommerce .product .yith-wcwl-add-to-wishlist');
		if (wishlist.length > 0) {
			wishlist.find('.add_to_wishlist').addClass('button');
			if (jQuery('.woocommerce .product .compare').length > 0) jQuery('.woocommerce .product .compare').insertBefore(wishlist);
		}

		// Make buttons from links
		var price = jQuery('.price_slider_amount .price_label');
		if (price.length > 0) {
			price.html(price.html().replace("—", "<span>-</span>"));
		}

		var separate = jQuery('.product_meta span.posted_in');
		if (separate.length > 0) {
			separate.html(separate.html().replace(/,/g, "<span class=\"separate\">,</span>"));
		}
		var separate2 = jQuery('.product_meta span.tagged_as');
		if (separate2.length > 0) {
			separate2.html(separate2.html().replace(/,/g, "<span class=\"separate\">,</span>"));
		}

	});
	
	// Generate 'scroll' event after the cart is filled
	jQuery( document.body ).on( 'wc_fragment_refresh', function() {
		jQuery(window).trigger('scroll');
	});
	
	// Add stretch behaviour to WooC tabs area
	jQuery(document).on('action.prepare_stretch_width', function() {
		if (ALEX_STONE_STORAGE['stretch_tabs_area'] > 0)
			jQuery('.single-product .woocommerce-tabs').wrap('<div class="trx-stretch-width"></div>');
	});


})();