<?php

/**
 * ST Payment Gateway
 *
 * Provides an st Payment Gateway; mainly for testing purposes.
 * We load it later to ensure WC is loaded first since we're extending it.
 * Note: v3 Secure Trading rebranded to Trust Payments
 *
 * @class       WC_ST_Gateway
 * @extends     WC_Payment_Gateway_CC
 * @version     1.0.0
 * @package     WooCommerce/Classes/Payment
 * @author      Illustrate Digital
 */
class WC_ST_Gateway extends WC_Payment_Gateway_CC {

	/**
	 * Constructor for the gateway.
	 */
	public function __construct() {

		$this->id                 = 'st_gateway';
		$this->icon               = apply_filters( 'woocommerce_st_icon', '' );
		$this->has_fields         = true;
		$this->method_title       = __( 'Trust Payments for WooCommerece', 'wc-gateway-st' );
		$this->method_description = __( 'Allows payment to be taken using your Trust Payments account.', 'wc-gateway-st' );
		$this->plugin_ver         = '3.0.0';
		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();

		// Define user set variables.
		$this->title                = $this->get_option( 'title' );
		$this->description          = $this->get_option( 'description' );
		$this->username             = $this->get_option( 'username', '' );
		$this->password             = $this->get_option( 'password', '' );
		$this->secure_3d            = $this->get_option( 'secure_3d', 'no' ) == 'yes' ? 1 : 0;
		$this->enabled_log_details  = $this->get_option( 'enabled_log_details', 'no' ) == 'yes' ? 1 : 0;
		$this->enabled_tokenization = $this->get_option( 'enabled_tokenization', 'no' ) == 'yes' ? 1 : 0;
		$this->transaction_method   = $this->get_option( 'transaction_method', 'FINAL' );
		$this->sitereference        = $this->get_option( 'sitereference', '' );
		$this->two_digits_date      = $this->get_option( 'two_digits_date', 'no' ) == 'yes' ? 0 : 1;
		$this->supports             = array(
			'products',
			'refunds',
			'subscriptions',
			'subscription_cancellation',
			'subscription_suspension',
			'subscription_reactivation',
			'subscription_amount_changes',
			'subscription_date_changes',
			'add_payment_method',
			'tokenization',
			'credit_card_form_cvc_on_saved_method',
		);

		// ST API config.
		$this->configData = array(
			'username' => $this->username,
			'password' => $this->password,
		);

		$this->api = \Securetrading\api( $this->configData );

		// Logger config.
		$this->logger   = wc_get_logger();
		$this->context  = array( 'source' => 'wc-st-gateway' );
		$this->order_id = 0;

		// Actions.
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'validate_st_account_details' ), 1 );
		// add_filter( 'wc_session_use_secure_cookie', '__return_true' );
		add_action( 'wp_enqueue_scripts', array( $this, 'wc_st_add_frontend_script' ) );
		add_action( 'woocommerce_after_checkout_validation', array( $this, 'validate_expiry_date_format' ), 10, 2 );
		add_action( 'woocommerce_admin_order_data_after_order_details', array( $this, 'display_order_transaction_details' ), 10, 1 );
		// add_action( 'woocommerce_thankyou_' . $this->id, array( $this, 'display_customer_order_transaction_details' ), 10, 1 );
		// add_action( 'woocommerce_order_details_after_order_table', array( $this, 'get_customer_order_transaction_details' ), 10, 1 );
		add_action( 'woocommerce_get_order_item_totals', array( $this, 'add_payment_method_data' ), 10, 3 );
		// add_action( 'woocommerce_order_status_cancelled', array( $this, 'process_cancellation' ), 10, 1 );

		// 3d secure actions.
		if ( $this->secure_3d ) {
			// since there is a different form that needs to be submited to the 3th party processor
			// we create form located at wp_footer that will be automatically submited.
			// add_action( 'wp_footer', array( $this, 'add_3d_container_to_footer' ) );
			add_action( 'before_woocommerce_pay', array( $this, 'display_3d_form' ) );

			add_filter( 'woocommerce_update_order_review_fragments', array( $this, 'refresh_form' ), 10, 1 );
			add_action( 'woocommerce_before_checkout_form', array( $this, 'clear_payment_session_data' ), 10, 1 );
			add_action( 'woocommerce_before_checkout_form', array( $this, 'add_3d_container_no_js' ), 10, 1 );
			add_action( 'woocommerce_api_' . $this->id, array( $this, 'secure_3d_process_response' ), 10, 1 );
		}
		if ( class_exists( 'WC_Subscriptions_Order' ) && function_exists( 'wcs_create_renewal_order' ) ) {
			add_action( 'woocommerce_scheduled_subscription_payment_' . $this->id, array( $this, 'scheduled_subscription_payment' ), 10, 2 );
		}

		add_filter( 'wc_st_process_request', array( $this, 'add_address_to_request' ), 10, 2 );
	}

	/**
	 * Enqueue ST assets.
	 *
	 * @return void
	 */
	public function wc_st_add_frontend_script() {
		if ( is_checkout() || is_wc_endpoint_url( 'order-pay' ) || is_wc_endpoint_url( 'add-payment-method' ) ) {
			$min = '';
			if ( ! defined( 'SCRIPT_DEBUG' ) ) {
				$min = '.min';
			}
			wp_enqueue_script( 'wc-st-api', 'https://webservices.securetrading.net/js/st.js' );
			wp_register_script( 'wc-st-main', plugin_dir_url( __FILE__ ) . '../assets/frontend/js/main' . $min . '.js', array( 'jquery', 'wc-st-api' ) );
			wp_localize_script(
				'wc-st-main',
				'st_object',
				array(
					'id'              => $this->id,
					'sitereference'   => $this->sitereference,
					'secure_3d'       => $this->secure_3d,
					'two_digits_date' => $this->two_digits_date,
				)
			);
			wp_enqueue_script( 'wc-st-main' );
		}
	}

	/**
	 * Validate expiry date format.
	 *
	 * @param $data
	 * @param $errors
	 */
	public function validate_expiry_date_format( $data, $errors ) {
		$length         = 4;
		$format_size    = 9;
		$display_format = 'YYYY';
		if ( $this->two_digits_date ) {
			$length         = 2;
			$format_size    = 7;
			$display_format = 'YY';
		}
		$method = filter_input( INPUT_POST, $this->id . '-payment-token' );
		if ( isset( $_REQUEST['payment_method'] ) && ( $_REQUEST['payment_method'] === $this->id && $method === 'new' ) ) {
			$cart_expiry = filter_input( INPUT_POST, $this->id . '-card-expiry' );
			if ( $format_size !== strlen( $cart_expiry ) ) {
				wc_add_notice( __( 'Invalid expiry date format please use MM / ' . $display_format, 'wc-gateway-st' ), 'error' );
				return;
			}
			$new_token = isset( $_POST[ "wc-{$this->id}-payment-token" ] ) && ( $_POST[ "wc-{$this->id}-payment-token" ] == 'new' ) ? 1 : 0;
			if ( ! preg_match( '/^([0-9]{2})( \/ )([0-9]{' . $length . '})$/', $cart_expiry, $matches ) && $new_token ) {
				wc_add_notice( __( 'Invalid expiry date format please use MM / ' . $display_format, 'wc-gateway-st' ), 'error' );
			}
		}
	}

	/**
	 * Validation to save payment details in options.
	 */
	public function validate_st_account_details() {
		if ( ! empty( $_POST['woocommerce_st_gateway_username'] ) && ! empty( $_POST['woocommerce_st_gateway_password'] ) && ! empty( $_POST['woocommerce_st_gateway_sitereference'] ) ) {
			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ), 10 );
		}
	}

	/**
	 * Log messages the Woo way.
	 *
	 * Need to enable the feature from the admin settings.
	 *
	 * @param string $message The log message.
	 * @param string $type    The log type.
	 */
	protected function add_log_message( $message, $type = 'info' ) {
		$this->context = array( 'source' => 'wc-st-gateway-' . $this->order_id );
		if ( $this->enabled_log_details ) {
			switch ( $type ) {
				case 'info':
					$this->logger->info( $message, $this->context );
					break;
				case 'debug':
					$this->logger->debug( $message, $this->context );
					break;
				case 'notice':
					$this->logger->notice( $message, $this->context );
					break;
				case 'warning':
					$this->logger->warning( $message, $this->context );
					break;
				case 'error':
					$this->logger->error( $message, $this->context );
					break;
				case 'critical':
					$this->logger->critical( $message, $this->context );
					break;
				case 'alert':
					$this->logger->alert( $message, $this->context );
					break;
				case 'emergency':
					$this->logger->emergency( $message, $this->context );
					break;
				default:
					$this->logger->log( $type, $message, $this->context );
					break;

			}
		}
	}

	/**
	 * Removing 3d payment information.
	 */
	public function clear_payment_session_data() {
		$order_id = isset( $this->order_id ) && ! empty( $this->order_id ) ? $this->order_id : WC()->session->get( $this->id . '_order_id' );
		if ( ! empty( $order_id ) ) {
			$order = wc_get_order( $order_id );
			if ( is_object( $order ) ) {
				$order->delete_meta_data( '_st_acsurl' );
				$order->delete_meta_data( '_st_pareq' );
				$order->delete_meta_data( '_st_md' );
				$order->delete_meta_data( '_st_save_card' );
				$order->delete_meta_data( '_st_cc_month' );
				$order->delete_meta_data( '_st_cc_year' );
			}
		}
		WC()->session->__unset( $this->id . '_acsurl' );
		WC()->session->__unset( $this->id . '_pareq' );
		WC()->session->__unset( $this->id . '_md' );
		WC()->session->__unset( $this->id . '_order_id' );
		WC()->session->__unset( $this->id . '_save_card' );
		WC()->session->__unset( $this->id . '_cc_month' );
		WC()->session->__unset( $this->id . '_cc_year' );
	}

	/**
	 * Add billing/shipping fields to AUTH request.
	 *
	 * @param array    $request_data
	 * @param WC_Order $order
	 * @return array
	 */
	public function add_address_to_request( $request_data, $order ) {
		return $this->prepare_auth_request( $order, $request_data );
	}

	/**
	 * Initialize Gateway Settings Form Fields
	 */
	public function init_form_fields() {

		$this->form_fields = apply_filters(
			'wc_st_form_fields',
			array(

				'enabled'              => array(
					'title'   => __( 'Enable/Disable', 'wc-gateway-st' ),
					'type'    => 'checkbox',
					'label'   => __( 'Enable Trust Payments', 'wc-gateway-st' ),
					'default' => 'yes',
				),
				'enabled_log_details'  => array(
					'title'   => __( 'Enable/Disable Debug Logger', 'wc-gateway-st' ),
					'type'    => 'checkbox',
					'label'   => __( 'Enable debugging (useful if you\'re having issues with the plugin)', 'wc-gateway-st' ),
					'default' => 'yes',
				),
				'title'                => array(
					'title'       => __( 'Title', 'wc-gateway-st' ),
					'type'        => 'text',
					'description' => __( 'This controls the title for the payment method the customer sees during checkout.', 'wc-gateway-st' ),
					'default'     => __( 'st Payment', 'wc-gateway-st' ),
					'desc_tip'    => true,
				),
				'description'          => array(
					'title'       => __( 'Description', 'wc-gateway-st' ),
					'type'        => 'textarea',
					'description' => __( 'Payment method description that the customer will see on your checkout.', 'wc-gateway-st' ),
					'default'     => __( 'Please remit payment to Store Name upon pickup or delivery.', 'wc-gateway-st' ),
					'desc_tip'    => true,
				),
				'secure_3d'            => array(
					'title'   => __( '3D Secure mode', 'wc-gateway-st' ),
					'type'    => 'checkbox',
					'label'   => __( 'Enable 3D Secure mode', 'wc-gateway-st' ),
					'default' => 'no',
				),
				'username'             => array(
					'title'       => __( 'Webservices Username', 'wc-gateway-st' ),
					'type'        => 'email',
					'description' => __( 'Your Trust Payments email.', 'wc-gateway-st' ),
					'default'     => '',
					'desc_tip'    => true,
				),
				'password'             => array(
					'title'       => __( 'Webservices Password', 'wc-gateway-st' ),
					'type'        => 'password',
					'description' => __( 'Your Trust Payments password.', 'wc-gateway-st' ),
					'default'     => '',
					'desc_tip'    => true,
				),
				'sitereference'        => array(
					'title'       => __( 'Site Reference', 'wc-gateway-st' ),
					'type'        => 'text',
					'description' => __( 'Trust Payments Site Reference.', 'wc-gateway-st' ),
					'default'     => '',
					'desc_tip'    => true,
				),
				'transaction_method'   => array(
					'title'       => __( 'Transaction Method', 'wc-gateway-st' ),
					'type'        => 'select',
					'options'     => array(
						'FINAL' => 'Final authorisation',
						'PRE'   => 'Pre-authorisation',
					),
					'description' => __( 'Final authorisation is (Only supported by Mastercard)', 'wc-gateway-st' ),
					'default'     => 'FINAL',
					'desc_tip'    => true,
				),
				'enabled_tokenization' => array(
					'title'   => __( 'Enable Tokenization', 'wc-gateway-st' ),
					'type'    => 'checkbox',
					'label'   => __( 'Enable Trust Payments Credit Card storage', 'wc-gateway-st' ),
					'default' => 'no',
				),
				'two_digits_date'      => array(
					'title'   => __( 'Enable 4 digits year', 'wc-gateway-st' ),
					'type'    => 'checkbox',
					'label'   => __( 'Enable Trust Payments Credit Card expiry year with 4 ( four ) digits.', 'wc-gateway-st' ),
					'default' => 'no',
				),
				'button_style'         => array(
					'title'       => __( 'Button Style', 'wc-gateway-st' ),
					'type'        => 'textarea',
					'description' => __( 'Add css style for the pay now button.', 'wc-gateway-st' ),
					'default'     => '',
					'desc_tip'    => true,
				),
			)
		);
	}

	/**
	 * Prepare AUTH ST request
	 *
	 * @param WC_Order $order        Order object.
	 * @param array    $request_data Depending of the request, contains different data such as baseamount and token.
	 * @return void
	 */
	protected function prepare_auth_request( $order, $request_data ) {

		if ( ! $order || ! is_object( $order ) ) {
			$order = WC()->customer;
		}

		if ( ! isset( $_POST[ "wc-{$this->id}-new-payment-method" ] ) && isset( $_POST[ "wc-{$this->id}-payment-token" ] ) && $_POST[ "wc-{$this->id}-payment-token" ] === 'new' && isset( $request_data['credentialsonfile'] ) && $request_data['credentialsonfile'] === 2 ) {
			// check if order hasn't subscription.
			if ( function_exists( 'wcs_order_contains_subscription' ) && ! wcs_order_contains_subscription( $order ) ) {
				unset( $request_data['credentialsonfile'] );
				// check if subscription is disabled.
			} elseif ( ! function_exists( 'wcs_order_contains_subscription' ) ) {
				unset( $request_data['credentialsonfile'] );
			}
		}

		if ( function_exists( 'wcs_order_contains_subscription' ) && wcs_order_contains_subscription( $order ) && ! isset( $request_data['subscriptionnumber'] ) ) {
			$request_data['subscriptionnumber'] = '1';
			$request_data['subscriptiontype']   = 'RECURRING';
			unset( $request_data['credentialsonfile'] );
		}

		return array_merge(
			$request_data,
			array(
				'billingpremise'       => $order->get_billing_address_1(),
				'billingstreet'        => $order->get_billing_address_2(),
				'billingtown'          => $order->get_billing_city(),
				'billingcounty'        => $order->get_billing_state(),
				'billingcountryiso2a'  => strlen( $order->get_billing_country() ) === 2 ? $order->get_billing_country() : '',
				'billingpostcode'      => $order->get_billing_postcode(),
				'billingemail'         => $order->get_billing_email(),
				'billingtelephone'     => $order->get_billing_phone(),
				'billingfirstname'     => $order->get_billing_first_name(),
				'billinglastname'      => $order->get_billing_last_name(),
				'customerpremise'      => $order->get_shipping_address_1(),
				'customerstreet'       => $order->get_shipping_address_2(),
				'customertown'         => $order->get_shipping_city(),
				'customercounty'       => $order->get_shipping_state(),
				'customercountryiso2a' => strlen( $order->get_shipping_country() ) === 2 ? $order->get_shipping_country() : '',
				'customerpostcode'     => $order->get_shipping_postcode(),
				'customerfirstname'    => $order->get_shipping_first_name(),
				'customerlastname'     => $order->get_shipping_last_name(),
				'orderreference'       => 'Web_order_' . $order->get_order_number(),
			)
		);
	}

	/**
	 * Process refund request.
	 *
	 * @param $response
	 * @param $reuqest
	 *
	 * @return array
	 */
	protected function validate_st_refund_response_response( $response, $reuqest ) {
		$return = array( 'result' => false );
		if ( intval( $response['errorcode'] ) === 0 ) {
			if ( $response['requesttypedescription'] === 'TRANSACTIONUPDATE' ) {
				$return['result']       = true;
				$return['order_status'] = 'refunded';
			} elseif ( $response['requesttypedescription'] === 'REFUND' ) {
				$return['result']       = true;
				$return['order_status'] = 'cancelled';
			}
		}
		return $return;
	}

	/**
	 * Transactionupdate cancel request.
	 *
	 * @param int $refund_amount
	 * @param str $parent_transaction
	 *
	 * @return @return array with response data.
	 */
	protected function prepare_transaction_update_request( $refund_amount, $parent_transaction, $order ) {
		$request_data_transaction = array(
			'requesttypedescriptions' => array( 'TRANSACTIONUPDATE' ),
			'filter'                  => array(
				'sitereference'        => array( array( 'value' => $this->sitereference ) ),
				'transactionreference' => array( array( 'value' => $parent_transaction ) ),
			),
		);
		if ( $refund_amount && $refund_amount != $order->get_total() ) {
			$refund_amount = strval( ( $order->get_total() - $refund_amount ) * 100 );
			$request_data_transaction['updates']['settlebaseamount'] = $refund_amount;
		} else {
			$request_data_transaction['updates']['settlestatus'] = '3';
		}

		$response     = $this->api->process( $request_data_transaction );
		$response_arr = $response->toArray();

		return $this->validate_st_refund_response_response( $response_arr['responses'][0], $request_data_transaction );
	}

	public function woo_cancel_refund_status( $order_id, $refund_id ) {
		return 'cancelled';
	}

	public function process_cancellation( $order_id ) {
		$order = wc_get_order( $order_id );

		if ( ! $order ) {
			return;
		}
		$total_refunded = $order->get_total_refunded();
		if ( $total_refunded ) {
			$this->process_refund( $order_id, $order->get_total() - $total_refunded );
		} else {
			// Make sure we have the payment token stored.
			$this->process_refund( $order_id, $order->get_total() );
		}
	}


	/**
	 * Process refund.
	 *
	 * If the gateway declares 'refunds' support, this will allow it to refund.
	 * a passed in amount.
	 *
	 * @param  int    $order_id
	 * @param  float  $amount
	 * @param  string $reason
	 * @return boolean True or false based on success, or a WP_Error object.
	 */
	public function process_refund( $order_id, $amount = null, $reason = '' ) {
		$this->order_id = $order_id;
		$this->add_log_message( sprintf( __( 'Processing refund for order %s', 'wc-gateway-st' ), $order_id ) );
		$order = wc_get_order( $order_id );

		// Make sure we have the payment token stored.
		$payment_token = get_post_meta( $order_id, '_transaction_id', true );
		if ( empty( $payment_token ) ) {
			return false;
		}

		// Check if its partial refund or full.
		if ( $amount ) {
			$refund_amount = strval( $amount * 100 );
		} else {
			$refund_amount = strval( $order->get_total() * 100 );
		}

		// Prepare request.
		$request_data = array(
			'requesttypedescriptions'    => array( 'REFUND' ),
			'sitereference'              => $this->sitereference,
			'parenttransactionreference' => $payment_token,
			'baseamount'                 => $refund_amount,
		);

		$response     = $this->api->process( $request_data );
		$response_arr = $response->toArray();
		$data         = $this->validate_st_payment_response( $response_arr['responses'][0], $request_data, true );

		unset( $data['message'] );
		// Make sure the refund was processed properly.
		if ( 'success' === $data['result'] ) {
			$order->add_order_note( __( 'Order refunded.', 'wc-gateway-st' ) );
			$this->add_log_message( sprintf( __( 'Successful processing refund for order %s', 'wc-gateway-st' ), $order_id ) );
			$this->add_log_message( '========================================' );
			return true;

		} else {
			$this->add_notes( $data, $order );
			$this->add_log_message( sprintf( __( 'Unsuccessful processing refund for order %s', 'wc-gateway-st' ), $order_id ) );
			$this->add_log_message( '========================================' );
			$order->save();
			return false;
		}
	}

	/**
	 * Make the 3d secure request.
	 *
	 * We use this function because if there is an enrolled value as U we need to do the same request few times.
	 *
	 * @param string $st_token The ST Token retrived via Javascript.
	 * @param string $order_total  The Order total.
	 * @param int    $order_id     The Order ID.
	 * @return array               The processed response details.
	 */
	protected function make_secure_3d_request( $st_token, $order_total, $order_id, $type = 'cachetoken' ) {
		$request_data = array(
			'termurl'                 => get_site_url() . '/?wc-api=' . esc_attr( $this->id ) . '&order=' . $order_id,
			'useragent'               => isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : '',
			'accept'                  => 'text/html,*/*',
			$type                     => $st_token,
			'currencyiso3a'           => get_woocommerce_currency(),
			'requesttypedescriptions' => $order_total ? array( 'THREEDQUERY' ) : 'ACCOUNTCHECK',
			'accounttypedescription'  => 'ECOM',
			'sitereference'           => $this->sitereference,
			'baseamount'              => $order_total,
		);
		$this->add_log_message( sprintf( __( 'THREEDQUERY processing for order %s', 'wc-gateway-st' ), $order_id ) );

		$order        = wc_get_order( $order_id );
		$request_data = apply_filters( 'wc_st_process_request', $request_data, $order );
		$response     = $this->api->process( $request_data );
		$response_arr = $response->toArray();
		return $this->validate_st_3d_secure_payment_response( $response_arr['responses'][0] );
	}

	/**
	 * If securityresponseaddress, securityresponsepostcode or securityresponsesecuritycode are with status 0 or 1
	 *
	 * We change the payment complete order status to on-hold
	 *
	 * @param string   $status   The default order status.
	 * @param int      $order_id The order id.
	 * @param WC_Order $order    The order object.
	 * @return string            On hold order status.
	 */
	public function change_payment_complete_status( $status, $order_id, $order ) {
		return 'on-hold';
	}

	/**
	 * Make JS disabled 3d secure request.
	 *
	 * We use this function because if there is an enrolled value as U we need to do the same request few times.
	 *
	 * @param string $st_token The ST Token retrived via Javascript.
	 * @param string $order_total  The Order total.
	 * @param int    $order_id     The Order ID.
	 * @return array               The processed response details.
	 */
	protected function make_secure_3d_no_js_request( $order_total, $order_id ) {
		$expiry_date = explode( ' / ', isset( $_POST[ $this->id . '-card-expiry' ] ) ? sanitize_text_field( wp_unslash( $_POST[ $this->id . '-card-expiry' ] ) ) : '' );
		if ( $this->two_digits_date && strlen( $expiry_date[1] ) ) {
			$expiry_date[1] = '20' . $expiry_date[1];
		}
			$request_data = array(
				'termurl'                 => get_site_url() . '/?wc-api=' . esc_attr( $this->id ) . '&order=' . $order_id,
				'useragent'               => isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : '',
				'accept'                  => 'text/html,*/*',
				'sitereference'           => $this->sitereference,
				'requesttypedescriptions' => intval( $order_total ) ? 'THREEDQUERY' : 'ACCOUNTCHECK',
				'accounttypedescription'  => 'ECOM',
				'currencyiso3a'           => get_woocommerce_currency(),
				'baseamount'              => $order_total,
				'pan'                     => strval( isset( $_POST[ $this->id . '-card-number' ] ) ? sanitize_text_field( wp_unslash( $_POST[ $this->id . '-card-number' ] ) ) : '' ),
				'expirymonth'             => strval( $expiry_date[0] ),
				'expiryyear'              => strval( $expiry_date[1] ),
				'securitycode'            => strval( isset( $_POST[ $this->id . '-card-cvc' ] ) ? sanitize_text_field( wp_unslash( $_POST[ $this->id . '-card-cvc' ] ) ) : '' ),
			);
			$order        = wc_get_order( $order_id );
			$request_data = apply_filters( 'wc_st_process_request', $request_data, $order );
			$response     = $this->api->process( $request_data );
			$response_arr = $response->toArray();
			return $this->validate_st_3d_secure_payment_response( $response_arr['responses'][0] );
	}

	/**
	 * Process subscription payment.
	 *
	 * @param $amount_to_charge
	 * @param $order
	 *
	 * @return bool
	 */
	public function scheduled_subscription_payment( $amount_to_charge, $order ) {
		if ( $amount_to_charge == 0 ) {
			$order->payment_complete();
			return true;
		}
		$order_id        = $order->get_id();
		$parent_order_id = 0;
		foreach ( $order->get_meta_data() as $key => $meta ) {
			$data = $meta->get_data();
			if ( $data['key'] === '_subscription_renewal' ) {
				$parent_order_id = $data['value'];
			}
		}
		$renewal_no = 2;
		// On the subscription page, just show related orders.

		if ( $parent_order_id ) {
			$subscription = wcs_get_subscription( $parent_order_id );
			if ( false != $subscription->get_parent_id() ) {
				$token      = get_post_meta( $subscription->get_parent_id(), '_transaction_id', true );
				$renewal_no = count( $subscription->get_related_orders() );
			}
		} else {
			$order->update_status( 'failed', 'Can\'t find transaction reference' );
			return false;
		}

		$order_total  = strval( $amount_to_charge * 100 );
		$request_data = array(
			'sitereference'              => $this->sitereference,
			// COF 2 here ok.
			'credentialsonfile'          => '2',
			'requesttypedescriptions'    => intval( $order_total ) ? array( 'AUTH' ) : array( 'ACCOUNTCHECK' ),
			'authmethod'                 => $this->transaction_method,
			'accounttypedescription'     => 'RECUR',
			'currencyiso3a'              => get_woocommerce_currency(),
			'baseamount'                 => $order_total,
			'orderreference'             => 'Web_order_' . $order->get_order_number(),
			'parenttransactionreference' => $token,
			'subscriptionnumber'         => (string) $renewal_no,
			'subscriptiontype'           => 'RECURRING',

		);
		$request_data = apply_filters( 'wc_st_process_request', $request_data, $subscription );

		$response     = $this->api->process( $request_data );
		$response_arr = $response->toArray();

		$data = $this->validate_st_payment_response( $response_arr['responses'][0], $request_data );
		unset( $data['message'] );
		if ( 'success' === $data['result'] ) {
			$order->payment_complete( $response_arr['responses'][0]['transactionreference'] );
			$data['admin_message'][] = sprintf( __( 'Trust Payments payment successful, transaction id: %s', 'wc-gateway-st' ), $response_arr['responses'][0]['transactionreference'] );
			$this->add_log_message( sprintf( __( 'Trust Payments payment successful, transaction id: %s', 'wc-gateway-st' ), $response_arr['responses'][0]['transactionreference'] ) );
			$this->add_notes( $data, $order );
			$this->add_log_message( sprintf( __( 'Successfull processing payment end for order %s', 'wc-gateway-st' ), $order_id ) );
			$this->add_log_message( '========================================' );
			$this->add_order_transaction_details( $order_id, $response_arr['responses'][0] );
			return true;
		} else {
			$data['admin_message'][] = sprintf( __( 'Trust Payments payment unsuccessful, transaction id: %s', 'wc-gateway-st' ), $response_arr['responses'][0]['transactionreference'] );
			$this->add_notes( $data, $order );
			$this->add_order_transaction_details( $order_id, $response_arr['responses'][0] );
			$this->add_log_message( sprintf( __( 'Unsuccessfull processing payment end for order %s', 'wc-gateway-st' ), $order_id ) );
			$this->add_log_message( '========================================' );
			$order->update_status( 'failed' );
			return false;
		}
	}

	/**
	 * Process the payment and return the result
	 *
	 * @param int $order_id
	 * @return array
	 */
	public function process_payment( $order_id ) {
		$expiry_date = explode( ' / ', isset( $_POST[ $this->id . '-card-expiry' ] ) ? sanitize_text_field( wp_unslash( $_POST[ $this->id . '-card-expiry' ] ) ) : '' );
		if ( sizeof( $expiry_date ) > 1 && $this->two_digits_date && strlen( $expiry_date[1] ) ) {
			$expiry_date[1] = '20' . $expiry_date[1];
		}
		WC()->session->set( $this->id . '_auto_redirect', 1 );
		WC()->session->set( $this->id . '_order_id', $order_id );
		$st_token = isset( $_POST[ $this->id . '-cachetoken' ] ) ? sanitize_text_field( wp_unslash( $_POST[ $this->id . '-cachetoken' ] ) ) : '';
		$order    = wc_get_order( $order_id );
		$order->add_meta_data( 'st_plugin_version', $this->plugin_ver );
		$order->add_meta_data( '_st_auto_redirect', 1 );
		$order->delete_meta_data( '_st_order_processed' );
		$order->save();
		$order_total    = strval( $order->get_total() * 100 );
		$this->order_id = $order_id;
		$this->add_log_message( sprintf( __( 'Processing payment for order %s', 'wc-gateway-st' ), $order_id ) );
		// if JS is disabled.
		if ( isset( $_POST[ $this->id . '-card-number' ] ) && ! empty( $_POST[ $this->id . '-card-number' ] ) && isset( $_POST[ $this->id . '-card-expiry' ] ) && ! empty( $_POST[ $this->id . '-card-expiry' ] ) && isset( $_POST[ $this->id . '-card-cvc' ] ) && ! empty( $_POST[ $this->id . '-card-cvc' ] ) ) {
			// secure 3d processing.
			$this->add_log_message( __( 'JS disabled', 'wc-gateway-st' ) );

			if ( $this->secure_3d ) {
				$secure_3d_old_data = array();
				$this->add_log_message( __( '3d non JS payment', 'wc-gateway-st' ) );
				$data = $this->make_secure_3d_no_js_request( $order_total, $order_id );
				// Make sure we got proper response from ST.
				if ( ! $data['continue'] ) {
					// This means the request enrolled value was U.
					// Make 2 more requests.
					$this->add_log_message( __( '3d secure U or N request', 'wc-gateway-st' ) );
					if ( $data['skip_threequery'] ) {
						$tests = 0;
						while ( $tests < 2 ) {
							$data = $this->make_secure_3d_no_js_request( $order_total, $order_id );
							if ( 'success' === $data['result'] || $data['continue'] || ! $data['skip_threequery'] ) {
								break;
							}
							$tests++;
						}
					}
				}
				if ( 'success' === $data['result'] ) {

					$this->add_log_message( __( 'Success 3d non JS payment', 'wc-gateway-st' ) );
					WC()->session->set( $this->id . '_pareq', $data['response']['pareq'] );
					WC()->session->set( $this->id . '_acsurl', $data['response']['acsurl'] );
					WC()->session->set( $this->id . '_md', $data['response']['md'] );
					$order->update_meta_data( '_st_md', $data['response']['md'] );
					$order->update_meta_data( '_st_pareq', $data['response']['pareq'] );
					$order->update_meta_data( '_st_acsurl', $data['response']['acsurl'] );
					$this->order_id = $order_id;
					WC()->session->set( $this->id . '_order_id', $order_id );
					$data['response']['cc_month']   = $expiry_date[0];
					$data['response']['cc_year']    = $expiry_date[1];
					$secure_3d_old_data['enrolled'] = $data['response']['enrolled'];
					$secure_3d_old_data['status']   = $data['response']['status'];
					if ( isset( $_POST[ "wc-{$this->id}-new-payment-method" ] ) && sainitize_text_field( wp_unslash( $_POST[ "wc-{$this->id}-new-payment-method" ] ) ) ) {
						$data['response']['save_cc'] = 1;
						WC()->session->set( $this->id . '_save_card', 1 );
						WC()->session->set( $this->id . '_cc_month', $expiry_date[0] );
						WC()->session->set( $this->id . '_cc_year', $expiry_date[1] );
						$order->update_meta_data( '_st_save_card', 1 );
						$order->update_meta_data( '_st_cc_month', $expiry_date[0] );
						$order->update_meta_data( '_st_cc_year', $expiry_date[1] );
					} else {
						$order->update_meta_data( '_st__save_card', 0 );
						WC()->session->set( $this->id . '_save_card', 0 );
					}
					$order->save();
					wc_add_notice( __( 'Since you\'re javascript is not enabled, please submit the form above to process with the payment', 'wc-gateway-st' ), 'success' );
					$this->add_log_message( __( 'Sucessful 3d secure request redirecting to ACS', 'wc-gateway-st' ) );
					$this->add_notes( $data, $order );
					$this->add_order_transaction_details( $order_id, $data['response'] );
					return array(
						'result'   => 'success',
						'reload'   => true,
						'redirect' => get_permalink( wc_get_page_id( 'checkout' ) ),
					);
				} else {
					$this->add_log_message( __( 'Unsuccess 3d non JS payment, trying AUTH request', 'wc-gateway-st' ) );
				}
			}

			// No else here since we need to do AUTH request if
			// 3d secure is enabled but we got enrolled return value as U or N.
			$this->add_log_message( __( 'Auth non JS payment', 'wc-gateway-st' ) );
			$request_data = array(
				'sitereference'           => $this->sitereference,
				'requesttypedescriptions' => intval( $order_total ) ? 'AUTH' : 'ACCOUNTCHECK',
				'authmethod'              => $this->transaction_method,
				'accounttypedescription'  => 'ECOM',
				'currencyiso3a'           => get_woocommerce_currency(),
				'baseamount'              => $order_total,
				'pan'                     => strval( isset( $_POST[ $this->id . '-card-number' ] ) ? sanitize_text_field( wp_unslash( $_POST[ $this->id . '-card-number' ] ) ) : '' ),
				'expirymonth'             => strval( $expiry_date[0] ),
				'expiryyear'              => strval( $expiry_date[1] ),
				'securitycode'            => strval( isset( $_POST[ $this->id . '-card-cvc' ] ) ? sanitize_text_field( wp_unslash( $_POST[ $this->id . '-card-cvc' ] ) ) : '' ),
			);

			if ( $this->secure_3d && isset( $data['transactionreference'] ) ) {
				$request_data['transactionreference'] = $data['transactionreference'];
			}
			$request_data = apply_filters( 'wc_st_process_request', $request_data, $order );
			$response     = $this->api->process( $request_data );
			$response_arr = $response->toArray();
			$data         = $this->validate_st_payment_response( $response_arr['responses'][0], $request_data );

		} elseif ( empty( $st_token ) && isset( $_POST[ "wc-{$this->id}-payment-token" ] ) && $_POST[ "wc-{$this->id}-payment-token" ] === 'new' ) {
			wc_add_notice( __( 'Error processing request', 'wc-gateway-st' ), 'error' );
			$this->add_log_message( __( 'Missing token in request', 'wc-gateway-st' ), 'error' );
			return array();
		} else {
			// Check the payment method.
			if ( $_POST[ "wc-{$this->id}-payment-token" ] == 'new' || ! isset( $_POST[ "wc-{$this->id}-payment-token" ] ) ) {
				// Register the secure 3d response variable.
				$data               = array();
				$secure_3d_old_data = array();
				if ( $this->secure_3d ) {
					$this->add_log_message( __( '3d secure payment method', 'wc-gateway-st' ) );
					$data = $this->make_secure_3d_request( $st_token, $order_total, $order_id );
					// Make sure we got proper response from ST.
					if ( ! $data['continue'] ) {
						// This means the request enrolled value was U.
						// Make 2 more requests.
						$this->add_log_message( __( '3d secure U or N request', 'wc-gateway-st' ) );
						if ( $data['skip_threequery'] ) {
							$tests = 0;
							while ( $tests < 2 ) {
								$this->add_log_message( __( '3d secure U or N request #' . $tests, 'wc-gateway-st' ) );
								$data = $this->make_secure_3d_request( $st_token, $order_total, $order_id );
								if ( 'success' === $data['result'] || $data['continue'] || ! $data['skip_threequery'] ) {
									break;
								}
								$tests++;
							}
						}
					} else {
						$this->add_log_message( __( '3d secure payment successful', 'wc-gateway-st' ) );
					}
					$data['response']['cc_month'] = $expiry_date[0];
					$data['response']['cc_year']  = $expiry_date[1];
					if ( $data['response']['enrolled'] ) {
						$secure_3d_old_data['enrolled'] = $data['response']['enrolled'];
					}
					if ( isset( $data['response']['status'] ) ) {
						$secure_3d_old_data['status'] = $data['response']['status'];
					}

					// make sure we are good to be redirected at Access Control Server.
					if ( 'success' === $data['result'] ) {
						$this->add_log_message( __( 'Sucessful 3d secure request redirecting to ACS', 'wc-gateway-st' ) );

						WC()->session->set( $this->id . '_pareq', $data['response']['pareq'] );
						WC()->session->set( $this->id . '_acsurl', $data['response']['acsurl'] );
						WC()->session->set( $this->id . '_md', $data['response']['md'] );
						$order->update_meta_data( '_st_md', $data['response']['md'] );
						$order->update_meta_data( '_st_pareq', $data['response']['pareq'] );
						$order->update_meta_data( '_st_acsurl', $data['response']['acsurl'] );
						$this->order_id = $order_id;
						WC()->session->set( $this->id . '_order_id', $order_id );
						if ( isset( $_POST[ "wc-{$this->id}-new-payment-method" ] ) && sanitize_text_field( wp_unslash( $_POST[ "wc-{$this->id}-new-payment-method" ] ) ) ) {
							WC()->session->set( $this->id . '_save_card', 1 );
							$data['response']['save_cc'] = 1;
							WC()->session->set( $this->id . '_cc_month', $expiry_date[0] );
							WC()->session->set( $this->id . '_cc_year', $expiry_date[1] );
							$order->update_meta_data( '_st_save_card', 1 );
							$order->update_meta_data( '_st_cc_month', $expiry_date[0] );
							$order->update_meta_data( '_st_cc_year', $expiry_date[1] );
						} else {
							WC()->session->set( $this->id . '_save_card', 0 );
							$order->update_meta_data( '_st_save_card', 0 );
						}
						$order->save();
						wc_add_notice( __( 'Redirecting the cardholder to the card issuer’s ACS', 'wc-gateway-st' ), 'success' );
						$key    = 'refresh';
						$append = '';
						if ( is_wc_endpoint_url( 'order-pay' ) ) {
							$key = 'reload';
						}
						if ( isset( $_POST['createaccount'] ) ) {
							$append = '<script>jQuery("form[name=\'checkout\']").trigger("submit");console.log("its happening");</script>';
						}
						$this->add_notes( $data, $order );
						$this->add_order_transaction_details( $order_id, $data['response'] );
						$return = array(
							'result'   => 'success',
							$key       => true,
							'messages' => "\n\t<div class=\"woocommerce-message\" role=\"alert\">" . __( 'Redirecting the cardholder to the card issuer’s ACS', 'wc-gateway-st' ) . "</div>\n\t" . $append,
						);
						if ( is_wc_endpoint_url( 'order-pay' ) ) {

							$return['redirect'] = $order->get_checkout_payment_url();
						}
						return $return;
					}
				}
				// No else here since we need to do AUTH request if
				// 3d secure is enabled but we got enrolled return value as U or N.
				$request_data = array(
					'sitereference'           => $this->sitereference,
					'requesttypedescriptions' => intval( $order_total ) ? array( 'AUTH' ) : 'ACCOUNTCHECK',
					'authmethod'              => $this->transaction_method,
					'accounttypedescription'  => 'ECOM',
					'currencyiso3a'           => get_woocommerce_currency(),
					'baseamount'              => $order_total,
					'orderreference'          => 'Web_order_' . $order->get_order_number(),
					'cachetoken'              => $st_token,
				);

				if ( isset( $data['response']['transactionreference'] ) ) {
					$request_data['parenttransactionreference'] = $data['response']['transactionreference'];
				}

				if ( isset( $_POST[ "wc-{$this->id}-new-payment-method" ] ) && sanitize_text_field( wp_unslash( $_POST[ "wc-{$this->id}-new-payment-method" ] ) ) ) {
					$request_data['credentialsonfile'] = '1';
				}

				if ( $this->secure_3d && isset( $data['transactionreference'] ) ) {
					$request_data['transactionreference'] = $data['transactionreference'];
					$this->add_log_message( __( '3d secure request with U or N enrolled status.', 'wc-gateway-st' ) );
				}
				$this->add_log_message( __( 'Making AUTH request', 'wc-gateway-st' ) );
				// Checked OK.
				$request_data = apply_filters( 'wc_st_process_request', $request_data, $order );
				$response     = $this->api->process( $request_data );
				$response_arr = $response->toArray();
			} else {
				if ( ( isset( $_POST[ "wc-{$this->id}-payment-token" ] ) && ! empty( $_POST[ "wc-{$this->id}-payment-token" ] ) ) ) {
					$this->add_log_message( __( 'Making saved CC request', 'wc-gateway-st' ) );
					$token          = WC_Payment_Tokens::get( sanitize_text_field( $_POST[ "wc-{$this->id}-payment-token" ] ) );
					$parent_token   = $token->get_token();
					$expiry_date[0] = $token->get_data()['expiry_month'];
					$expiry_date[1] = $token->get_data()['expiry_year'];
					if ( $this->secure_3d ) {
						$secure_3d_old_data = array();
						$this->add_log_message( __( '3d non JS payment', 'wc-gateway-st' ) );
						$data = $this->make_secure_3d_request( $parent_token, $order_total, $order_id, 'parenttransactionreference' );
						// Make sure we got proper response from ST.
						if ( ! $data['continue'] ) {
							// This means the request enrolled value was U.
							// Make 2 more requests.
							$this->add_log_message( __( '3d secure U or N request', 'wc-gateway-st' ) );
							if ( $data['skip_threequery'] ) {
								$tests = 0;
								while ( $tests < 2 ) {
									$data = $this->make_secure_3d_request( $parent_token, $order_total, $order_id, 'parenttransactionreference' );
									if ( 'success' === $data['result'] || $data['continue'] || ! $data['skip_threequery'] ) {
										break;
									}
									$tests++;
								}
							}
						}
						WC()->session->set( $this->id . '_old_card', 1 );
						$order->update_meta_data( '_st_old_card', 1 );
						if ( 'success' === $data['result'] ) {
							$this->add_log_message( __( 'Success 3d non JS payment', 'wc-gateway-st' ) );
							WC()->session->set( $this->id . '_pareq', $data['response']['pareq'] );
							WC()->session->set( $this->id . '_acsurl', $data['response']['acsurl'] );
							WC()->session->set( $this->id . '_md', $data['response']['md'] );
							$order->update_meta_data( '_st_md', $data['response']['md'] );
							$order->update_meta_data( '_st_pareq', $data['response']['pareq'] );
							$order->update_meta_data( '_st_acsurl', $data['response']['acsurl'] );
							$this->order_id = $order_id;
							WC()->session->set( $this->id . '_order_id', $order_id );
							$data['response']['cc_month']   = $expiry_date[0];
							$data['response']['cc_year']    = $expiry_date[1];
							$secure_3d_old_data['enrolled'] = $data['response']['enrolled'];
							if ( isset( $_POST[ "wc-{$this->id}-new-payment-method" ] ) && sanitize_text_field( wp_unslash( $_POST[ "wc-{$this->id}-new-payment-method" ] ) ) ) {
								$data['response']['save_cc'] = 1;
								WC()->session->set( $this->id . '_save_card', 1 );
								WC()->session->set( $this->id . '_cc_month', $expiry_date[0] );
								WC()->session->set( $this->id . '_cc_year', $expiry_date[1] );
								$order->update_meta_data( '_st_save_card', 1 );
								$order->update_meta_data( '_st_cc_month', $expiry_date[0] );
								$order->update_meta_data( '_st_cc_year', $expiry_date[1] );
							} else {
								$order->update_meta_data( '_st_save_card', 0 );
								WC()->session->set( $this->id . '_save_card', 0 );
							}
							$order->save();
							$this->add_log_message( __( 'Sucessful 3d secure request redirecting to ACS', 'wc-gateway-st' ) );
							$this->add_notes( $data, $order );
							$this->add_order_transaction_details( $order_id, $data['response'] );
							if ( isset( $_POST[ $this->id . '-card-number' ] ) ) {
								wc_add_notice( __( 'Since you\'re javascript is not enabled, please submit the form above to process with the payment', 'wc-gateway-st' ), 'success' );
								return array(
									'result'   => 'success',
									'reload'   => true,
									'redirect' => get_permalink( wc_get_page_id( 'checkout' ) ),
								);
							} else {
								$key    = 'refresh';
								$append = '';
								if ( is_wc_endpoint_url( 'order-pay' ) ) {
									$key = 'reload';
								}
								wc_add_notice( __( 'Successful 3d secure request redirecting to ACS', 'wc-gateway-st' ), 'success' );
								$return = array(
									'result'   => 'success',
									$key       => true,
									'messages' => "\n\t<div class=\"woocommerce-message\" role=\"alert\">" . __( 'Successful 3d secure request redirecting to ACS', 'wc-gateway-st' ) . "</div>\n\t" . $append,
								);
								if ( is_wc_endpoint_url( 'order-pay' ) ) {
									$return['redirect'] = $order->get_checkout_payment_url();
								}
								return $return;
							}
						} else {
							$this->add_log_message( __( 'Unsuccess 3d non JS payment, trying AUTH request', 'wc-gateway-st' ) );
						}
					}

					$request_data = array(
						'sitereference'              => $this->sitereference,
						// COF 2 here is OK.
						'credentialsonfile'          => '2',
						'requesttypedescriptions'    => intval( $order_total ) ? array( 'AUTH' ) : 'ACCOUNTCHECK',
						'authmethod'                 => $this->transaction_method,
						'accounttypedescription'     => 'ECOM',
						'currencyiso3a'              => get_woocommerce_currency(),
						'baseamount'                 => $order_total,
						'orderreference'             => 'Web_order_' . $order->get_order_number(),
						'parenttransactionreference' => $parent_token,
					);

					// TODO: Ask if we need to remove this.
					if ( isset( $data['response']['transactionreference'] ) ) {
						$request_data['parenttransactionreference'] = $data['response']['transactionreference'];
					}

					$expiry_date    = array();
					$expiry_date[0] = $token->get_expiry_month();
					$expiry_date[1] = $token->get_expiry_year();
					if ( strlen( $expiry_date[1] ) == 2 ) {
						$expiry_date[1] = '20' . $expiry_date[1];
					}
					$request_data = apply_filters( 'wc_st_process_request', $request_data, $order );

					$response     = $this->api->process( $request_data );
					$response_arr = $response->toArray();
				} else {
					wc_add_notice( __( 'Invalid payment method.', 'wc-gateway-st' ), 'error' );
					$this->add_log_message( __( 'Invalid payment method.', 'wc-gateway-st' ), 'error' );
					return array();
				}
			}
		}
			$data                                     = $this->validate_st_payment_response( $response_arr['responses'][0], $request_data );
			$response_arr['responses'][0]['cc_month'] = $expiry_date[0];
			$response_arr['responses'][0]['cc_year']  = $expiry_date[1];
		if ( ! empty( $secure_3d_old_data ) ) {
			// if is not empty, means we did 3d secure with enrolled != Y status so we add that into the data.
			$response_arr['responses'][0]['enrolled'] = $secure_3d_old_data['enrolled'];
			$response_arr['responses'][0]['status']   = $secure_3d_old_data['status'];
		}
		if ( 'success' === $data['result'] ) {
			// make sure the payment is successful.

			if ( isset( $_POST[ "wc-{$this->id}-new-payment-method" ] ) && sanitize_text_field( wp_unslash( $_POST[ "wc-{$this->id}-new-payment-method" ] ) ) ) {
				$this->add_log_message( __( 'Save order CC', 'wc-gateway-st' ) );
				$response_arr['responses'][0]['save_cc'] = 1;
				$this->save_payment_token( $response_arr['responses'][0], $expiry_date[0], $expiry_date[1] );
			}
			$order->payment_complete( $response_arr['responses'][0]['transactionreference'] );
			$data['admin_message'][] = sprintf( __( 'Trust Payments payment successful, transaction id: %s', 'wc-gateway-st' ), $response_arr['responses'][0]['transactionreference'] );
			$this->add_log_message( sprintf( __( 'Trust Payments payment successful, transaction id: %s', 'wc-gateway-st' ), $response_arr['responses'][0]['transactionreference'] ) );
			$this->add_notes( $data, $order );

			$this->add_log_message( sprintf( __( 'Successfull processing payment end for order %s', 'wc-gateway-st' ), $order_id ) );
			$this->add_log_message( '========================================' );
			$this->add_order_transaction_details( $order_id, $response_arr['responses'][0] );
			// Return thankyou redirect.
			return array(
				'result'   => 'success',
				'redirect' => $this->get_return_url( $order ),
			);
		} else {
			$data['admin_message'][] = sprintf( __( 'Trust Payments payment unsuccessful, transaction id: %s', 'wc-gateway-st' ), $response_arr['responses'][0]['transactionreference'] );
			$this->add_notes( $data, $order );
			$this->add_order_transaction_details( $order_id, $response_arr['responses'][0] );
			$this->add_log_message( sprintf( __( 'Unsuccessfull processing payment end for order %s', 'wc-gateway-st' ), $order_id ) );
			$this->add_log_message( '========================================' );
			return array();
		}
	}

	/**
	 * Store additional details for the transaction.
	 *
	 * @param int   $order_id The order id
	 * @param array $data     Data containing the transaction response.
	 * @return void
	 */
	protected function add_order_transaction_details( $order_id, $data ) {
		if ( isset( $data['maskedpan'] ) ) {
			update_post_meta( $order_id, $this->id . '_card_number', $data['maskedpan'] );
		}
		if ( isset( $data['paymenttypedescription'] ) ) {
			update_post_meta( $order_id, $this->id . '_card_type', $data['paymenttypedescription'] );
		}
		if ( isset( $data['cc_month'] ) ) {
			update_post_meta( $order_id, $this->id . '_card_month', $data['cc_month'] );
		}
		if ( isset( $data['cc_year'] ) ) {
			update_post_meta( $order_id, $this->id . '_card_year', $data['cc_year'] );
		}
		if ( isset( $data['issuer'] ) ) {
			update_post_meta( $order_id, $this->id . '_card_issuer', $data['issuer'] );
		}
		if ( isset( $data['issuercountryiso2a'] ) ) {
			update_post_meta( $order_id, $this->id . '_issuercountryiso2a', $data['issuercountryiso2a'] );
		}
		if ( isset( $data['save_cc'] ) ) {
			update_post_meta( $order_id, $this->id . '_save_card', $data['save_cc'] );
		}
		if ( isset( $data['securityresponseaddress'] ) ) {
			update_post_meta( $order_id, $this->id . '_securityresponseaddress', $data['securityresponseaddress'] );
		}
		if ( isset( $data['securityresponsepostcode'] ) ) {
			update_post_meta( $order_id, $this->id . '_securityresponsepostcode', $data['securityresponsepostcode'] );
		}
		if ( isset( $data['securityresponsesecuritycode'] ) ) {
			update_post_meta( $order_id, $this->id . '_securityresponsesecuritycode', $data['securityresponsesecuritycode'] );
		}
		if ( isset( $data['enrolled'] ) ) {
			update_post_meta( $order_id, $this->id . '_enrolled', $data['enrolled'] );
		}
		if ( isset( $data['status'] ) ) {
			update_post_meta( $order_id, $this->id . '_status', $data['status'] );
		}
	}

	public function add_payment_method_data( $total_rows, $order, $tax_display ) {
		if ( isset( $total_rows['payment_method'] ) ) {
			$order_id = $order->get_id();
			if ( get_post_meta( $order_id, '_payment_method', true ) === $this->id ) {
				if ( ( get_post_meta( $order_id, $this->id . '_card_number', true ) ) ) {
					$total_rows['payment_method']['value'] .= '<br>Last 4 digits of card used: ' . substr( get_post_meta( $order_id, $this->id . '_card_number', true ), -4 );
				}
				if ( ( get_post_meta( $order_id, $this->id . '_card_type', true ) ) ) {
					$total_rows['payment_method']['value'] .= '<br>Card Type: ' . get_post_meta( $order_id, $this->id . '_card_type', true );
				}
			}
		}
		return $total_rows;
	}

	public function get_customer_order_transaction_details( $order ) {
		$this->display_customer_order_transaction_details( $order->get_id() );
	}

	public function display_customer_order_transaction_details( $order_id ) {
		echo '<p class="form-field form-field-wide ' . esc_attr( $this->id ) . '">';
		if ( ( get_post_meta( $order_id, $this->id . '_card_number', true ) ) ) {
			echo 'Card Number: ' . esc_html( get_post_meta( $order_id, $this->id . '_card_number', true ) ) . '<br>';
		}
		if ( ( get_post_meta( $order_id, $this->id . '_card_type', true ) ) ) {
			echo 'Card Type: ' . esc_html( get_post_meta( $order_id, $this->id . '_card_type', true ) ) . '<br>';
		}
		echo '</p>';
	}

	/**
	 * Display transaction details on Order page.
	 *
	 * @param $order
	 */
	public function display_order_transaction_details( $order ) {
		$order_id = $order->get_id();
		echo '<p class="form-field form-field-wide ' . esc_attr( $this->id ) . '">';
		if ( ( get_post_meta( $order_id, $this->id . '_card_number', true ) ) ) {
			echo 'Card Number: ' . esc_html( get_post_meta( $order_id, $this->id . '_card_number', true ) ) . '<br>';
		}
		if ( ( get_post_meta( $order_id, $this->id . '_card_type', true ) ) ) {
			echo 'Card Type: ' . esc_html( get_post_meta( $order_id, $this->id . '_card_type', true ) ) . '<br>';
		}
		if ( ( get_post_meta( $order_id, $this->id . '_card_month', true ) ) ) {
			echo 'Expiry Month: ' . esc_html( get_post_meta( $order_id, $this->id . '_card_month', true ) ) . '<br>';
		}
		if ( ( get_post_meta( $order_id, $this->id . '_card_year', true ) ) ) {
			echo 'Expiry Year: ' . esc_html( get_post_meta( $order_id, $this->id . '_card_year', true ) ) . '<br>';
		}
		if ( ( get_post_meta( $order_id, $this->id . '_card_issuer', true ) ) ) {
			echo 'Card Issuer: ' . esc_html( get_post_meta( $order_id, $this->id . '_card_issuer', true ) ) . '<br>';
		}
		if ( ( get_post_meta( $order_id, $this->id . '_issuercountryiso2a', true ) ) ) {
			echo 'Card Issuer Country: ' . esc_html( get_post_meta( $order_id, $this->id . '_issuercountryiso2a', true ) ) . '<br>';
		}
		if ( ( get_post_meta( $order_id, $this->id . '_save_card', true ) ) ) {
			echo 'Saved CC<br>';
		}
		if ( get_post_meta( $order_id, $this->id . '_securityresponseaddress', true ) || get_post_meta( $order_id, $this->id . '_securityresponseaddress', true ) == 0 ) {
			echo 'AVS Response Code first line of address: ' . esc_html( get_post_meta( $order_id, $this->id . '_securityresponseaddress', true ) ) . '<br>';
		}
		if ( get_post_meta( $order_id, $this->id . '_securityresponsepostcode', true ) || get_post_meta( $order_id, $this->id . '_securityresponsepostcode', true ) == 0 ) {
			echo 'AVS Response Code postcode: ' . esc_html( get_post_meta( $order_id, $this->id . '_securityresponsepostcode', true ) ) . '<br>';
		}
		if ( get_post_meta( $order_id, $this->id . '_securityresponsesecuritycode', true ) || get_post_meta( $order_id, $this->id . '_securityresponsesecuritycode', true ) == 0 ) {
			echo 'CVV2 Response Code: ' . esc_html( get_post_meta( $order_id, $this->id . '_securityresponsesecuritycode', true ) ) . '<br>';
		}
		if ( ( get_post_meta( $order_id, $this->id . '_enrolled', true ) ) ) {
			echo '3D secure enrolled status: ' . esc_html( get_post_meta( $order_id, $this->id . '_enrolled', true ) ) . '<br>';
		}
		if ( ( get_post_meta( $order_id, $this->id . '_status', true ) ) ) {
			echo '3D secure status: ' . esc_html( get_post_meta( $order_id, $this->id . '_status', true ) ) . '<br>';
		}

		// Disable the transaction ID field.
		if ( $order->get_payment_method() ) { ?>
			<script>
				jQuery(document).ready(function(){
					jQuery('#_transaction_id,#_payment_method').css('display','none');
					jQuery('#_payment_method').parent().css('display','none');
				})
			</script>
			<?php
		}
	}

	/**
	 * Add order notes
	 *
	 * @param array $data  Key message contains all messages for the user. Key admin_message contains all messages for the admin.
	 * @param bool  $error True if the transaction is invalid.
	 */
	protected function add_notes( $data, $order, $error = true ) {
		// Displaying error messages.
		if ( isset( $data['message'] ) ) {
			foreach ( $data['message'] as $msg ) {
				if ( $error ) {
					wc_add_notice( $msg, 'error' );
				}
				$this->add_log_message( $msg, 'error' );
			}
		}
		if ( isset( $data['admin_message'] ) ) {
			foreach ( $data['admin_message'] as $msg ) {
				$order->add_order_note( $msg );
				$this->add_log_message( $msg, 'error' );
			}
		}
	}

	/**
	 * Display payment form.
	 */
	public function payment_fields() {
		echo esc_attr( $this->description );
		$this->tokenization_script();
		if ( ! is_wc_endpoint_url( 'add-payment-method' ) ) {
			$this->saved_payment_methods();
		}
		$this->form();
		// Make sure user logged in and feature must be enabled.
		if ( get_current_user_id() && $this->enabled_tokenization && ! is_wc_endpoint_url( 'add-payment-method' ) ) {
			$this->save_payment_method_checkbox();
		}
	}

	/**
	 * Outputs fields for entering credit card information.
	 *
	 * @since 2.6.0
	 */
	public function form() {
		wp_enqueue_script( 'wc-credit-card-form' );

		$fields      = array();
		$year_format = 'YYYY';
		if ( $this->two_digits_date ) {
			$year_format = 'YY';
		}
		$default_fields = array(
			'card-number-field' => '<p class="form-row form-row-wide validate-required">
			<label for="' . esc_attr( $this->id ) . '-card-number">' . esc_html__( 'Card number', 'woocommerce' ) . ' <span class="required">*</span></label>
			<input required id="' . esc_attr( $this->id ) . '-card-number" name="' . esc_attr( $this->id ) . '-card-number" data-st-field="pan" autocomplete="off" class="input-text wc-credit-card-form-card-number" inputmode="numeric" autocomplete="cc-number" autocorrect="no" autocapitalize="no" spellcheck="no" type="tel" placeholder="&bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull;" ' . $this->field_name( 'card-number' ) . ' />
			</p>',
			'card-cvc-field'    => '<p class="form-row form-row-first validate-required">
			<label for="' . esc_attr( $this->id ) . '-card-cvc">' . esc_html__( 'Security Code', 'woocommerce' ) . ' <span class="required">*</span></label>
			<input required id="' . esc_attr( $this->id ) . '-card-cvc" name="' . esc_attr( $this->id ) . '-card-cvc" data-st-field="securitycode" autocomplete="off" class="input-text wc-credit-card-form-card-cvc" inputmode="numeric" autocomplete="off" autocorrect="no" autocapitalize="no" spellcheck="no" type="tel" maxlength="4" placeholder="' . esc_attr__( 'CVC', 'woocommerce' ) . '" ' . $this->field_name( 'card-cvc' ) . ' />
			</p>',
			'card-expiry-field' => '<p class="form-row form-row-last validate-required">
			<label for="' . esc_attr( $this->id ) . '-card-expiry">' . esc_html__( 'Expiry (MM / ' . $year_format . ')', 'woocommerce' ) . ' <span class="required">*</span></label>
			<input required id="' . esc_attr( $this->id ) . '-card-expiry" name="' . esc_attr( $this->id ) . '-card-expiry" class="input-text wc-credit-card-form-card-expiry" maxlenght="7" inputmode="numeric" autocomplete="cc-exp" autocorrect="no" autocapitalize="no" spellcheck="no" type="tel" placeholder="' . esc_attr__( 'MM / ' . $year_format, 'woocommerce' ) . '"  />
			</p>',
		);

		$fields = wp_parse_args( $fields, apply_filters( 'woocommerce_credit_card_form_fields', $default_fields, $this->id ) );

		?>
			<div id="st-message"></div>
			<fieldset class='wc-credit-card-form wc-payment-form wc-<?php echo esc_attr( $this->id ); ?>-cc-form'>
			<?php do_action( 'woocommerce_credit_card_form_start', $this->id ); ?>
			<?php
			foreach ( $fields as $field ) {
				echo $field;
			}
			?>
				<input type="hidden" id="<?php echo esc_attr( $this->id ); ?>-cachetoken" name="<?php echo esc_attr( $this->id ); ?>-cachetoken"
				/>

				<input type="hidden" id="<?php echo esc_attr( $this->id ); ?>-cc-month" data-st-field="expirymonth" />
				<input type="hidden" id="<?php echo esc_attr( $this->id ); ?>-cc-year" data-st-field="expiryyear" />
				<?php do_action( 'woocommerce_credit_card_form_end', $this->id ); ?>
			</fieldset>

			<?php
	}

	/**
	 * Displays the save to account checkbox.
	 *
	 * @since 4.1.0
	 */
	public function save_payment_method_checkbox() {
		printf(
			'<p class="form-row woocommerce-SavedPaymentMethods-saveNew">
			<input id="wc-%1$s-new-payment-method" name="wc-%1$s-new-payment-method" type="checkbox" value="true" style="width:auto;" />
			<label for="wc-%1$s-new-payment-method" style="display:inline;">%2$s</label>
			</p>',
			esc_attr( $this->id ),
			esc_html( apply_filters( 'wc_st_save_to_account_text', __( 'Save payment information to my account for future purchases.', 'woocommerce-gateway-stripe' ) ) )
		);
	}

	/**
	 * Add payment method from my account page.
	 *
	 * @return array
	 */
	public function add_payment_method() {

		$st_token = isset( $_POST[ $this->id . '-cachetoken' ] ) ? sanitize_text_field( wp_unslash( $_POST[ $this->id . '-cachetoken' ] ) ) : '';

		$request_data = array(
			'currencyiso3a'           => get_woocommerce_currency(),
			'requesttypedescriptions' => array( 'ACCOUNTCHECK' ),
			'sitereference'           => $this->sitereference,
			'baseamount'              => '0',
			'orderreference'          => 'Save_CC_' . get_current_user_id(),
			'accounttypedescription'  => 'ECOM',
			'billingpremise'          => WC()->customer->get_billing_address_1(),
			'billingpostcode'         => WC()->customer->get_billing_postcode(),
			'cachetoken'              => $st_token,
			'credentialsonfile'       => '1',
		);
		$response     = $this->api->process( $request_data );
		$response_arr = $response->toArray();
		$data         = $this->validate_st_payment_response( $response_arr['responses'][0], $request_data, true );

		$cc_dates = explode( ' / ', isset( $_POST['st_gateway-card-expiry'] ) ? sanitize_text_field( wp_unslash( $_POST['st_gateway-card-expiry'] ) ) : '' );
		if ( $this->save_payment_token( $response_arr['responses'][0], $cc_dates[0], $cc_dates[1] ) && $data['result'] ) {
			return array(
				'result'   => 'success',
				'redirect' => wc_get_endpoint_url( 'payment-methods' ),
			);
		}
		return array(
			'result'   => 'failure',
			'redirect' => wc_get_endpoint_url( 'payment-methods' ),
		);
	}

	/**
	 * Save payment credit cart.
	 *
	 * @param array $response ST response details.
	 * @return void           Token status.
	 */
	protected function save_payment_token( $response, $month, $year ) {
		$pan   = substr( $response['maskedpan'], -4 );
		$token = new WC_Payment_Token_CC();
		$token->set_token( $response['transactionreference'] );
		$token->set_gateway_id( $this->id );
		$token->set_card_type( $response['paymenttypedescription'] );
		// get last 4 digits from response.
		$token->set_last4( $pan );
		$token->set_expiry_month( $month );
		if ( strlen( $year ) === 2 ) {
			$year = '20' . $year;
		}
		$token->set_expiry_year( $year );
		$token->set_user_id( get_current_user_id() );

		return $token->save();
	}

	/**
	 * Validate the response from 3d secure - Secure Trading.
	 *
	 * @param array   $data     Response object.
	 * @param array   $request  Requested object.
	 * @param boolean $refund   Skip baseprice valdiation if its refund.
	 * @return array            Validation array, if not valid contains error messages.
	 */
	public function validate_st_payment_response( $data, $request, $refund = false ) {
		$return = array(
			'result'       => 'success',
			'order_status' => 'processing',
		);
		// Request wasn't processed successfully.
		if ( 0 !== intval( $data['errorcode'] ) ) {
			$return['result'] = 'error';
			if ( 30000 === intval( $data['errorcode'] ) ) {
				// This indicates a field error.
				if ( ! empty( $data['errordata'] ) ) {
					if ( is_array( $data['errordata'] ) ) {
						$return['message'][]       = $data['errordata'][0];
						$return['admin_message'][] = $data['errordata'][0];
					} else {
						$return['message'][]       = $data['errordata'];
						$return['admin_message'][] = $data['errordata'];
					}
				} else {
					// make sure we have error message to display.
					$return['message'][]       = $data['errormessage'];
					$return['admin_message'][] = $data['errormessage'];
				}
			} elseif ( 70000 === intval( $data['errorcode'] ) ) {
				// this indicates a payment was declined by your bank.
				$return['message'][]       = __( 'Payment declined by your bank.', 'wc-gateway-st' );
				$return['admin_message'][] = __( 'Payment declined by their bank.', 'wc-gateway-st' );
			} else {
				if ( ! empty( $data['errormessage'] ) && $data['errormessage'] !== 'Ok' ) {
					if ( ! isset( $return['message']['errormessage'] ) ) {
						$return['message']['errormessage'] = $data['errormessage'];
						$return['admin_message'][]         = $data['errormessage'];
					}
				} else {
					$return['message'][]       = __( 'Communication error. Please contact the merchant to confirm the status of the transaction', 'wc-gateway-st' );
					$return['admin_message'][] = __( 'Communication error. Please contact the merchant to confirm the status of the transaction', 'wc-gateway-st' );
				}
			}
		}
		// Indicates if Settlement will be performed.
		if ( 2 === intval( $data['settlestatus'] ) ) {
			// Settle status 2 – Suspended.
			add_filter( 'woocommerce_payment_complete_order_status', array( $this, 'change_payment_complete_status' ), 99, 3 );
			$return['admin_message'][] = __( 'Trust Payments Settlement Suspended.', 'wc-gateway-st' );

		} elseif ( 3 === intval( $data['settlestatus'] ) ) {
			// Settle status Cancelled
			$return['admin_message'][] = __( 'Trust Payments Settlement Cancelled.', 'wc-gateway-st' );
			$return['result']          = 'error';
		}

		// Does the first line of the billing address entered by the customer match that found on their bank account?
		if ( 4 === intval( $data['securityresponseaddress'] ) ) {
			// The customer entered incorrect details.
			$return['admin_message'][] = __( 'The first line of the billing address entered didn’t match the one on the bank account.', 'wc-gateway-st' );
		} elseif ( 0 === intval( $data['securityresponseaddress'] ) ) {
			$return['admin_message'][] = __( 'The first line of the billing address was not sent to the bank.', 'wc-gateway-st' );
		} elseif ( 1 === intval( $data['securityresponseaddress'] ) ) {
			$return['admin_message'][] = __( 'The bank was unable to check the customer’s details.', 'wc-gateway-st' );
		}

		// Does the billing postcode entered by the customer match that found on their bank account?
		if ( 4 === intval( $data['securityresponsepostcode'] ) ) {
			// The customer entered incorrect details.
			$return['admin_message'][] = __( 'The postcode of the billing address entered didn’t match the one on the bank account.', 'wc-gateway-st' );
		} elseif ( 0 === intval( $data['securityresponsepostcode'] ) ) {
			$return['admin_message'][] = __( 'The postcode of the billing address was not sent to the bank.', 'wc-gateway-st' );
		} elseif ( 1 === intval( $data['securityresponsepostcode'] ) ) {
			$return['admin_message'][] = __( 'The bank was unable to check the customer’s postcode details.', 'wc-gateway-st' );
		}

		// Does the security code entered by the customer match the value found on the back of their card?
		if ( 4 === intval( $data['securityresponsesecuritycode'] ) ) {
			// The customer entered incorrect details.
			$return['admin_message'][] = __( 'The security code entered didn’t match the one on the bank account.', 'wc-gateway-st' );
		} elseif ( ( 0 === intval( $data['securityresponsesecuritycode'] ) && ! isset( $request['parenttransactionreference'] ) ) ) {
			$return['admin_message'][] = __( 'The security code entered was not sent to the bank.', 'wc-gateway-st' );
		} elseif ( 1 === intval( $data['securityresponsesecuritycode'] ) ) {
			$return['admin_message'][] = __( 'The bank was unable to check the customer’s security code.', 'wc-gateway-st' );
		}

		if ( 'ERROR' === $data['requesttypedescription'] ) {
			// The request may not have been processed successfully.
			$return['result'] = 'error';
			if ( ! empty( $data['errormessage'] ) && $data['errormessage'] !== 'Ok' ) {
				if ( ! isset( $return['message']['errormessage'] ) ) {
					$return['message']['errormessage'] = $data['errormessage'];
					$return['admin_message'][]         = $data['errormessage'];
				}
			} else {
				$return['message'][]       = __( 'ERROR, The request may not have been processed successfully. Please contact us for more details.', 'wc-gateway-st' );
				$return['admin_message'][] = __( 'ERROR, The request may not have been processed successfully', 'wc-gateway-st' );
			}
		}
		// Make sure all details are correct.
		if ( floatval( $data['baseamount'] ) !== floatval( $request['baseamount'] ) || $data['currencyiso3a'] !== $request['currencyiso3a'] && ! $refund ) {
			$return['result'] = 'error';
			if ( ! empty( $data['errormessage'] ) && $data['errormessage'] !== 'Ok' ) {
				if ( ! isset( $return['message']['errormessage'] ) ) {
					$return['message']['errormessage'] = $data['errormessage'];
					$return['admin_message'][]         = $data['errormessage'];
				}
			} else {
				$return['message'][]       = __( 'Something went wrong. Please contact us for more information.', 'wc-gateway-st' );
				$return['admin_message'][] = __( 'Something went wrong.', 'wc-gateway-st' );
			}
		}
		return $return;
	}

	/**
	 * Validate the response from 3d secure - Secure Trading.
	 *
	 * @param array $data     Response object.
	 * @return array            Validation array, if not valid contains error messages.
	 */
	public function validate_st_3d_secure_payment_response( $data ) {
		$return = array(
			'result'          => 'success',
			'continue'        => true,
			'response'        => $data,
			'skip_threequery' => true,
		);
		if ( $data['requesttypedescription'] === 'THREEDQUERY' ) {
			$return['skip_threequery'] = false;
		}
		// Request wasn't processed successfully.
		if ( 0 !== intval( $data['errorcode'] ) ) {
			$return['result'] = 'error';
			if ( 30000 === intval( $data['errorcode'] ) ) {
				// This indicates a field error.
				if ( ! empty( $data['errordata'] ) ) {
					$return['message'][]       = $data['errordata'];
					$return['admin_message'][] = $data['errordata'];
				} else {
					// make sure we have error message to display.
					$return['message'][]       = $data['errormessage'];
					$return['admin_message'][] = $data['errormessage'];
				}
			} elseif ( 70000 === intval( $data['errorcode'] ) ) {
				// this indicates a payment was declined by your bank.
				$return['message'][]       = __( 'Payment declined by your bank.', 'wc-gateway-st' );
				$return['admin_message'][] = __( 'Payment declined by their bank.', 'wc-gateway-st' );
			} else {
				$return['message'][]       = __( 'Communication error. Please contact the merchant to confirm the status of the transaction', 'wc-gateway-st' );
				$return['admin_message'][] = __( 'Communication error. Please contact the merchant to confirm the status of the transaction', 'wc-gateway-st' );
			}
		}
		if ( 'Y' !== $data['enrolled'] ) {
			$return['result']               = 'error';
			$return['transactionreference'] = $data['transactionreference'];
			if ( 'U' !== $data['enrolled'] ) {
				$return['continue'] = false;
			}
		}
		$return['admin_message'][] = sprintf( __( 'Transaction enrolled status : %s.', 'wc-gateway-st' ), $data['enrolled'] );
		$return['admin_message'][] = sprintf( __( 'Transaction settlestatus status : %s.', 'wc-gateway-st' ), $data['settlestatus'] );
		return $return;
	}

	/**
	 * Process the response from the 3d secure processor.
	 *
	 * TO DO: Process response.
	 *
	 * @param object $api_request the response.
	 * @return void
	 */
	public function secure_3d_process_response( $api_request ) {
		// error_reporting( 0 );.
		if ( isset( $_GET['order'] ) && ! empty( $_GET['order'] ) ) {
			$order_id = intval( $_GET['order'] );
		} else {
			$order_id = WC()->session->get( $this->id . '_order_id' );
		}
		$this->order_id = $order_id;
		$order          = wc_get_order( $order_id );

		// If there is no valid order - redirect back to checkout.
		if ( ! $order ) :
			wp_safe_redirect( get_permalink( wc_get_page_id( 'checkout' ) ) );
		endif;

		$order_total = strval( $order->get_total() * 100 );
		$_md         = $order->get_meta( '_st_md', true );
		$saved_cc    = $order->get_meta( '_st_save_card', true );
		$old_card    = $order->get_meta( '_st_old_card', true );
		if ( $order->get_meta( '_st_order_processed', true ) ) {
			if ( intval( $order->get_meta( '_st_order_processed', true ) ) ) {
				wp_safe_redirect( $this->get_return_url( $order ) );
			} else {
				wc_add_notice( __( $order->get_meta( '_st_order_processed', true ), 'wc-gateway-st' ), 'error' );
				wp_safe_redirect( get_permalink( wc_get_page_id( 'checkout' ) ) );
			}
			exit();
		}

		if ( isset( $_POST['MD'] ) && ( $_POST['MD'] !== $_md ) ) {
			// TO DO: provide better wording for this.
			wc_add_notice( __( 'Communication error. Please contact the merchant to confirm the status of the transaction', 'wc-gateway-st' ), 'error' );
			wp_safe_redirect( get_permalink( wc_get_page_id( 'checkout' ) ) );
			$this->clear_payment_session_data();
			exit();
		}
		$request_data = array(
			'sitereference'           => $this->sitereference,
			'requesttypedescriptions' => intval( $order_total ) ? 'AUTH' : 'ACCOUNTCHECK',
			'authmethod'              => $this->transaction_method,
			'accounttypedescription'  => 'ECOM',
			'currencyiso3a'           => get_woocommerce_currency(),
			'baseamount'              => $order_total,
			'orderreference'          => 'Web_order_' . $order->get_order_number(),
			'md'                      => isset( $_POST['MD'] ) ? sanitize_text_field( wp_unslash( $_POST['MD'] ) ) : '',
			'pares'                   => isset( $_POST['PaRes'] ) ? sanitize_text_field( wp_unslash( $_POST['PaRes'] ) ) : '',
		);
		if ( $saved_cc ) {
			$request_data['credentialsonfile'] = '1';
		}
		if ( $old_card ) {
			$request_data['credentialsonfile'] = '2';
			WC()->session->set( $this->id . '_old_card', 0 );
			$order->delete_meta_data( '_st_old_card' );
			$order->save();
		}
		$request_data = apply_filters( 'wc_st_process_request', $request_data, $order );
		$response     = $this->api->process( $request_data );
		$response_arr = $response->toArray();
		$data         = $this->validate_st_payment_response( $response_arr['responses'][0], $request_data );

		$_st_cc_month                             = $order->get_meta( '_st_cc_month', true );
		$_st_cc_year                              = $order->get_meta( '_st_cc_year', true );
		$response_arr['responses'][0]['cc_month'] = $_st_cc_month ? $_st_cc_month : WC()->session->get( $this->id . '_cc_month' );
		$response_arr['responses'][0]['cc_year']  = $_st_cc_year ? $_st_cc_year : WC()->session->get( $this->id . '_cc_year' );
		if ( 'success' === $data['result'] ) {
			$order->add_meta_data( '_st_order_processed', 1 );
			$order->save();
			if ( $saved_cc ) {
				$this->add_log_message( __( 'Save order CC', 'wc-gateway-st' ) );
				$this->save_payment_token( $response_arr['responses'][0], $_st_cc_month, $_st_cc_year );
				$response_arr['responses'][0]['save_cc'] = 1;
			}

			$order->payment_complete( $response_arr['responses'][0]['transactionreference'] );
			$data['admin_message'][] = sprintf( __( 'Trust Payments payment successful, transaction id: %s', 'wc-gateway-st' ), $response_arr['responses'][0]['transactionreference'] );
			$this->add_log_message( sprintf( __( 'Trust Payments payment successful, transaction id: %s', 'wc-gateway-st' ), $response_arr['responses'][0]['transactionreference'] ) );
			$this->add_log_message( sprintf( __( 'Successfull processing payment end for order %s', 'wc-gateway-st' ), $order_id ) );
			$this->add_log_message( '========================================' );
			$this->add_notes( $data, $order, false );
			$this->add_order_transaction_details( $order_id, $response_arr['responses'][0] );
			// Return thankyou redirect.
			$this->clear_payment_session_data();
			wp_safe_redirect( $this->get_return_url( $order ) );
		} else {
			$order->add_meta_data( '_st_order_processed', $data['message']['errormessage'] );
			$order->save();
			$data['admin_message'][] = sprintf( __( 'Trust Payments payment unsuccessful, transaction id: %s', 'wc-gateway-st' ), $response_arr['responses'][0]['transactionreference'] );
			$this->add_notes( $data, $order, true );
			$this->add_order_transaction_details( $order_id, $response_arr['responses'][0] );
			$this->clear_payment_session_data();
			wp_safe_redirect( get_permalink( wc_get_page_id( 'checkout' ) ) );
		}
		exit();
	}

	/**
	 * Initial container for the 3d secure form.
	 */
	public function add_3d_container_to_footer() {
		?>
			<div class="<?php echo esc_attr( $this->id ); ?>-3d-secure-form-container">

			</div>
		<?php
	}

	/**
	 * Refresh the 3d secure form.
	 *
	 * @param  array $fragments Contain refresh fragments.
	 * @return array
	 */
	public function refresh_form( $fragments ) {
		$fragments[ '.' . esc_attr( $this->id ) . '-3d-secure-form-container' ] = $this->get_display_3d_form();
		return $fragments;
	}

	/**
	 * Display the 3d secure form for Javascript enabled.
	 */
	public function display_3d_form() {
		$this->add_3d_container_no_js();
		// Deprecated, we can use the same form.
		return '';
		?>
		<div class="<?php echo esc_attr( $this->id ); ?>-3d-secure-form-container">
			<?php if ( ! empty( WC()->session->get( $this->id . '_acsurl' ) ) && ! empty( WC()->session->get( $this->id . '_pareq' ) ) && ! empty( WC()->session->get( $this->id . '_md' ) ) ) { ?>
				<form name="form" id="<?php echo esc_attr( $this->id ); ?>-3d-secure-form" action="<?php echo esc_attr( WC()->session->get( $this->id . '_acsurl' ) ); ?>"
					  method="POST">
					<div>
						<input type="hidden" name="PaReq" value="<?php echo esc_attr( WC()->session->get( $this->id . '_pareq' ) ); ?>" />
						<input type="hidden" name="TermUrl" value="<?php echo esc_url( get_site_url() ) . '/?wc-api=' . esc_attr( $this->id ) . '&order=' . esc_attr( WC()->session->get( $this->id ) . '_order_id' ); ?>"
						/>
						<input type="hidden" name="MD" value="<?php echo esc_attr( WC()->session->get( $this->id . '_md' ) ); ?>" />
					</div>
					<?php
					if ( WC()->session->get( $this->id . '_auto_redirect' ) == 1 ) {
						WC()->session->set( $this->id . '_auto_redirect', 0 );
						?>
					<?php } ?>
				</form>
			<?php } ?>
		</div>
		<?php
	}

	/**
	 * Display the form that's automatically submited on the front-end.
	 *
	 * @return string
	 */
	protected function get_display_3d_form() {
		ob_start();
		$this->add_3d_container_no_js();
		return ob_get_clean();
	}

	/**
	 * Display the No JS form above the checkout form.
	 */
	public function add_3d_container_no_js() {
		$order_id = isset( $this->order_id ) && ! empty( $this->order_id ) ? $this->order_id : WC()->session->get( $this->id . '_order_id' );
		?>
		<div class="<?php echo esc_attr( $this->id ); ?>-3d-secure-form-container">
			<?php
			if ( ! empty( $order_id ) ) {
				$order = wc_get_order( $order_id );
				$meta  = array(
					'acsurl'        => $order->get_meta( '_st_acsurl', true ),
					'pareq'         => $order->get_meta( '_st_pareq', true ),
					'md'            => $order->get_meta( '_st_md', true ),
					'auto_redirect' => $order->get_meta( '_st_auto_redirect', true ),
				);

				if ( ! empty( $meta['acsurl'] ) && ! empty( $meta['pareq'] ) && ! empty( $meta['md'] ) ) {
					?>
					<form name="form" id="<?php echo esc_attr( $this->id ); ?>-3d-secure-form" action="<?php echo esc_attr( $meta['acsurl'] ); ?>" 
						method="POST">
						<div>
							<input type="hidden" name="PaReq" value="<?php echo esc_attr( $meta['pareq'] ); ?>" />
							<input type="hidden" name="TermUrl" value="<?php echo esc_url( get_site_url() ) . '/?wc-api=' . esc_attr( $this->id ) . '&order=' . esc_attr( $order_id ); ?>"
							/>
							<input type="hidden" name="MD" value="<?php echo esc_attr( $meta['md'] ); ?>" />
						</div>
						<noscript>
							<div>
								<h3>JavaScript is currently disabled or is not supported by your browser.</h3>
								<h4>Please click Submit to continue processing your 3-D Secure transaction.</h4>
								<input type="submit" value="Submit">
							</div>
						</noscript>
						<?php
						if ( intval( $meta['auto_redirect'] ) === 1 ) {
							$order->delete_meta_data( '_st_auto_redirect' );
							$order->save();
							?>
								<script>
									jQuery('#<?php echo esc_attr( $this->id ); ?>-3d-secure-form').submit();
									console.log('dance');
								</script>
						<?php } ?>
					</form>
						<?php
				}
			}
			?>
			</div>
		<?php
	}
} // end \WC_ST_Gateway class
