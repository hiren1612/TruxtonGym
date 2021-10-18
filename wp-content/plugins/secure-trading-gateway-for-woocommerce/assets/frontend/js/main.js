jQuery( document ).ready(
	function ($) {
		function str_remove_names_from_fields() {
			$( '#' + st_object.id + '-card-cvc' ).removeAttr( 'name' );
			$( '#' + st_object.id + '-card-number' ).removeAttr( 'name' );
		}
		var form_id = 'st-payment';
		if ( $( 'body.woocommerce-order-pay #order_review' ).length ) {
			form_id = 'order_review';
		} else if ( $( 'body.woocommerce-add-payment-method #add_payment_method' ).length ) {
			form_id = 'add_payment_method';
		}
		str_remove_names_from_fields();
		$( 'form[name="checkout"]' ).attr( 'id', 'st-payment' );
		$( document ).on(
			'change',
			'input[name="wc-st_gateway-payment-token"]',
			function(){
				str_remove_names_from_fields();
				if ( $( this ).val() !== 'new' ) {
					$( '#' + st_object.id + '-card-cvc' ).removeAttr( 'required' );
					$( '#' + st_object.id + '-card-number' ).removeAttr( 'required' );
					$( '#' + st_object.id + '-card-expiry' ).removeAttr( 'required' );
				} else {
					$( '#' + st_object.id + '-card-cvc' ).attr( 'required', 'required' );
					$( '#' + st_object.id + '-card-number' ).attr( 'required', 'required' );
					$( '#' + st_object.id + '-card-expiry' ).attr( 'required', 'required' );
				}
			}
		)
		$( document ).on(
			'change',
			'form#' + form_id + ' input[name="payment_method"]:checked',
			function () {

				new SecureTrading.Standard(
					{
						sitereference: st_object.sitereference,
						locale: "en_gb",
						formId: form_id,
						submitFormCallback: function (data) {

							// make sure we have JS enabled.
							if ($( 'form#' + form_id + ' input[name="payment_method"]:checked' ).val() == st_object.id) {

								str_remove_names_from_fields();
								$( '#st-message' ).empty();
								if ($( '#wc-' + st_object.id + '-payment-token-new' ).is( ':checked' ) || form_id === 'add_payment_method' || $( '#wc-' + st_object.id + '-payment-token-new' ).length === 0 ) {

									// Check there are no errors.
									if ( typeof data['response'][0]['cachetoken'] != "undefined" && data['response'][0]['errorcode'] === 0 ) {
										
										// Check we have a valid cachetoken before continuing.
										if (typeof data['response'][0]['cachetoken'] == "undefined") {
											$( '.woocommerce-error, .woocommerce-message' ).remove();
											var $form = $( 'form#' + form_id );
											$form.prepend( '<div class="woocommerce-NoticeGroup woocommerce-NoticeGroup-updateOrderReview"><div class="woocommerce-error">There was a problem processing your order.</div></div>' );
											$( 'html, body' ).animate(
												{
													scrollTop: ($form.offset().top - 100)
												},
												1000
											);
											return;
										}

										var cachetoken = data['response'][0]['cachetoken'];
										var d          = new Date();
										$( 'formform#' + form_id ).data( 'token-time', d.getTime() );
										$( "#" + st_object.id + "-cachetoken" ).val( cachetoken );
										$( 'form#' + form_id ).trigger( 'submit' );

									} else {
										// If there was an error stop.
										$( '.woocommerce-error, .woocommerce-message' ).remove();
										var $form = $( 'form#' + form_id );
										$form.prepend( '<div class="woocommerce-NoticeGroup woocommerce-NoticeGroup-updateOrderReview"><div class="woocommerce-error">There was a problem processing your order.</div></div>' );
										$( 'html, body' ).animate(
											{
												scrollTop: ($form.offset().top - 100)
											},
											1000
										);
										return;
									}

								} else {
									$( 'form#' + form_id ).trigger( 'submit' );
								}
							}
						},
						validateRequestObjectCallback: function( data ) {
							if ( verify_cc_form() ) {
								str_remove_names_from_fields();
								if ( $( 'form#' + form_id + ' input[name="payment_method"]:checked' ).val() == st_object.id && $( '#wc-' + st_object.id + '-payment-token-new' ).is( ':checked' ) ) {
									if ( ! data ) {
										var $form = $( 'form#' + form_id );
										$form.prepend( '<div class="woocommerce-NoticeGroup woocommerce-NoticeGroup-updateOrderReview"><div class="woocommerce-error">Credit Card details required</div></div>' );
										$( 'html, body' ).animate(
											{
												scrollTop: ($form.offset().top - 100)
											},
											1000
										);
										$form.unblock();
									}
									return data;
								} else {
									return true;
								}
							} else {
								return false;
							}
						},
						errorCallback: function (data) {
							var $form = $( 'form#' + form_id );

							str_remove_names_from_fields();
							if ($( 'form#' + form_id + ' input[name="payment_method"]:checked' ).val() == st_object.id && ( $( '#wc-' + st_object.id + '-payment-token-new' ).length === 0 || $( '#wc-' + st_object.id + '-payment-token-new' ).is( ':checked' ) || form_id === 'add_payment_method' ) ) {
								$( '.woocommerce-error, .woocommerce-message' ).remove();
								// Add new errors returned by this event
								var messages = '';
								$.each(
									data,
									function (key, value) {
										messages += '<div class="woocommerce-error message-' + key + '">' + value[1] + '</div>';
									}
								)
								$form.prepend( '<div class="woocommerce-NoticeGroup woocommerce-NoticeGroup-updateOrderReview">' + messages + '</div>' );
								$( 'html, body' ).animate(
									{
										scrollTop: ($form.offset().top - 100)
									},
									1000
								);
								$form.unblock();
							} else {
								return true;
							}
						}
					}
				);
			}
		);
		$( document ).on(
			'change',
			'#' + st_object.id + '-card-number, #' + st_object.id + '-card-cvc, #' + st_object.id + '-card-expiry',
			function () {
				$( "#" + st_object.id + "-cachetoken" ).val( '' );
			}
		);
		function verify_cc_form() {
			if ( $( 'form#' + form_id + ' input[name="payment_method"]:checked' ).val() !== st_object.id ) {
				return false;
			}
			$return = true;
			if ( $( '#wc-' + st_object.id + '-payment-token-new' ).is( ':checked' ) || $( '#wc-' + st_object.id + '-payment-token-new' ).length === 0 ) {
				if ( ! verify_valid_date() ) {
					$return = false;
				}
				if ( ! verify_cc_ccv() ) {
					$return = false;
				}
				if ( ! verify_cc_number() ) {
					$return = false;
				}
			}
			return $return;
		}
		function verify_cc_ccv() {
			var $return = true;

			if ( $( '#' + st_object.id + '-card-cvc' ).val().length !== 3 && $( '#' + st_object.id + '-card-cvc' ).val().length !== 4 ) {
				st_display_error_message( 'Invalid security code length.', $( '#' + st_object.id + '-card-cvc' ) );
				$return = false;
			}
			return $return;
		}
		function validate_cc_date( $this ) {
			var length = 8;
			if ( st_object.two_digits_date === '1' ) {
				length = 6;
			}
			if ( $this.val().length >= length ) {
				$this.val( $this.val().slice( 0, length + 1 ) );
			}
			if ( $this.val().indexOf( ' / ' ) === -1 && $this.val().indexOf( '/' ) >= 0 ) {
				$this.val( $this.val().replace( '/', ' / ' ) );
			}

			if ( $this.val().indexOf( ' / ' ) ) {
				var date_details = $this.val().split( ' / ' );

				$( '#' + st_object.id + '-cc-month' ).val( date_details[0] );
				if ( date_details[1] !== undefined ) {
					if ( st_object.two_digits_date === '1' ) {
						$( '#' + st_object.id + '-cc-year' ).val( "20" + date_details[1] );
					} else {
						$( '#' + st_object.id + '-cc-year' ).val( date_details[1] );
					}
				}
			}
		}
		$( document ).on(
			'change keyup',
			'#' + st_object.id + '-card-expiry',
			function () {
				validate_cc_date( $( '#' + st_object.id + '-card-expiry' ) );
			}
		);

		$( '#' + st_object.id + '-card-expiry' ).trigger( 'keyup' ).change();
		$( 'form#' + form_id + ' input[name="payment_method"]' ).trigger( 'change' );

		$( 'form#' + form_id ).on(
			'checkout_place_order_st_gateway',
			function (e) {
				var d = new Date();
				remove_errors();

				if ( $( '#wc-' + st_object.id + '-payment-token-new' ).is( ':checked' ) ) {
					if ( ! verify_cc_form() ) {
						return false;
					}
				}
				str_remove_names_from_fields();
				if ($( 'form.checkout' ).data( 'token-time' ) !== undefined && parseInt( (d.getTime() - parseInt( $( 'form.checkout' ).data( 'token-time' ) ) / 100 / 60) > 14 )) {
					$( "#" + st_object.id + "-cachetoken" ).val( '' );
				}
				if ($( "#" + st_object.id + "-cachetoken" ).val() === '' && ( $( '#wc-' + st_object.id + '-payment-token-new' ).is( ':checked' ) || $( '#wc-' + st_object.id + '-payment-token-new' ).length === 0 ) ) {
					return false;
				}
				return true;
			}
		);

		function remove_errors(){
			// $('.woocommerce-notices-wrapper').html('');
			// $('.woocommerce-error').remove();
		}

		function display_date_error( $message ) {
			st_display_error_message( $message, $( '#' + st_object.id + '-card-expiry' ) );

		}

		function verify_valid_date() {
			var full_date = $( '#' + st_object.id + '-card-expiry' ).val();
			var $date     = full_date.split( ' / ' );
			var dt        = new Date();
			var $return   = true;
			var length    = 4;
			if ( st_object.two_digits_date === '1' ) {
				length = 2;
			}
			if ( full_date.length === 0 ) {
				display_date_error( 'Date field required.' );
				return false;
			}
			if ( $date[0].length !== 2 || $date[1].length !== length ) {
				if ( st_object.two_digits_date === '1' ) {
					display_date_error( 'Invalid expiry date format please use MM / YY.' );
				} else {
					display_date_error( 'Invalid expiry date format please use MM / YYYY. 1' );
				}
				$return = false;
			}
			if ( parseInt( $date[0] ) > 12 || parseInt( $date[0] ) === 0 ) {
				display_date_error( 'Invalid date month.' );
				$return = false;
			}
			if ( parseInt( $date[1] ) < ( dt.getFullYear() - 2000 ) ) {
				display_date_error( 'Invalid date year.' );
				$return = false;
			}
			if ( length === 4 && parseInt( $date[1] ) < ( dt.getFullYear() ) ) {
				display_date_error( 'Invalid date year.' );
				$return = false;
			}
			if ( length !== $date[1].length ) {
				display_date_error( 'Invalid date year.' );
				$return = false;
			}
			if ( $return ) {
				$( '#' + st_object.id + '-cc-month' ).val( $date[0] ).trigger( 'change' );
				if ( $date[1] !== undefined ) {
					if ( st_object.two_digits_date === '1' ) {
						$( '#' + st_object.id + '-cc-year' ).val( "20" + $date[1] ).trigger( 'change' );
					} else {
						$( '#' + st_object.id + '-cc-year' ).val( $date[1] ).trigger( 'change' );
					}
				}
			}
			return $return;
		}

		function verify_cc_number() {
			var $cc = $( '#' + st_object.id + '-card-number' ).val().split( ' ' ).join( '' );
			if ( validate_cc_new( $cc ) ) {
				return true;
			} else {
				st_display_error_message( 'Invalid credit card number.', $( '#' + st_object.id + '-card-number' ) );
				return false;
			}
		}
		function st_display_error_message( $message, $field ) {
			remove_errors();
			$( '.wc-st_gateway-cc-form .woocommerce-invalid' ).removeClass( 'woocommerce-invalid' );
			$( '.wc-st_gateway-cc-form .woocommerce-invalid-required-field' ).removeClass( 'woocommerce-invalid-required-field' );
			$field.parent().addClass( 'woocommerce-invalid woocommerce-invalid-required-field' );
			$field.focus();
			var str = '<div class="woocommerce-error" role="alert">' + $message + '</div>';
			$( '.woocommerce-notices-wrapper' ).first().html( str );
			$( document.body ).trigger( 'checkout_error' );
			$.scroll_to_notices( $( '.woocommerce-notices-wrapper:first-of-type' ) );
			if ( $( '#add_payment_method' ).length ) {
				setTimeout(
					function(){
						$( '#add_payment_method' ).unblock();
					},
					100
				);
			}
		}

		function validate_cc_new( no ) {
			return (
			no &&
			check_luhn( no ) &&
			no.length == 16 &&
			(
				no[0] == 4 || no[0] == 5 && no[1] >= 1 && no[1] <= 5 ||
				( no.indexOf( "6011" ) == 0 || no.indexOf( "65" ) == 0 )
			)
			|| ( no[0] == 5 && no[1] >= 0 )
			|| no.length == 15 && (no.indexOf( "34" ) == 0 || no.indexOf( "37" ) == 0)
			|| no.length == 16 && (no.indexOf( "22" ) == 0 || no.indexOf( "67" ) == 0)
			|| no.length == 13 && no[0] == 4
			|| no.length == 19 && no[0] == 6
			|| no.length == 14 && no[0] == 3
			);
		}
		function check_luhn(cardNo) {
			var s           = 0;
			var doubleDigit = false;
			for (var i = cardNo.length - 1; i >= 0; i--) {
				var digit = +cardNo[i];
				if (doubleDigit) {
					digit *= 2;
					if (digit > 9) {
						digit -= 9;
					}
				}
				s          += digit;
				doubleDigit = ! doubleDigit;
			}
			return s % 10 == 0;
		}
		if ( $( 'body' ).hasClass( '.woocommerce-checkout' ) && ! ('body').hasClass( 'woocommerce-order-received' ) ) {
			if ( $( '#' + st_object.id + '-card-expiry' ).val() !== '' ) {
				validate_cc_date( $( '#' + st_object.id + '-card-expiry' ) );
			}
		}
	}
);
