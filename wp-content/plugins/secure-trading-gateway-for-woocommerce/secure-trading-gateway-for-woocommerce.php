<?php
/**
 * Plugin Name: Trust Payments Gateway for WooCommerce
 * Description: Extends WooCommerce with the official Trust Payments payment gateway. Visit <a href="https://www.trustpayments.com/" target=_blank">Trust Payments</a> for more info.
 * Version:     3.0.6
 * Author:      Illustrate Digital
 * Author URI:  http://illustrate.digital
 * License:     GPL v3
 * Text Domain: wc-gateway-st
 * Domain Path: /i18n/languages/
 *
 * Copyright: (c) 2015-2018 Illustrate Digital Ltd. (info@Illustrate.Digital) and WooCommerce
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package   WC-Gateway-st
 * @author    Illustrate Digital
 * @category  Admin
 * @copyright Copyright (c) 2015-2018, Illustrate Digital Ltd. and WooCommerce
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 *
 * This st gateway forks the WooCommerce core "Cheque" payment gateway to create another st payment method.
 */

defined( 'ABSPATH' ) or exit;


// Make sure WooCommerce is active
if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	return;
}
require realpath( __DIR__ . '/vendor/autoload.php' );

/**
 * Add the gateway to WC Available Gateways
 *
 * @since 1.0.0
 * @param array $gateways all available WC gateways
 * @return array $gateways all WC gateways + st gateway
 */
function wc_st_add_to_gateways( $gateways ) {
	$gateways[] = 'WC_ST_Gateway';
	return $gateways;
}
add_filter( 'woocommerce_payment_gateways', 'wc_st_add_to_gateways' );


/**
 * Adds plugin page links
 *
 * @since 1.0.0
 * @param array $links all plugin links
 * @return array $links all plugin links + our custom links (i.e., "Settings")
 */
function wc_st_gateway_plugin_links( $links ) {

	$plugin_links = array(
		'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout&section=st_gateway' ) . '">' . __( 'Configure', 'wc-gateway-st' ) . '</a>',
	);

	return array_merge( $plugin_links, $links );
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'wc_st_gateway_plugin_links' );


add_action( 'plugins_loaded', 'woocommerce_gateway_st', 11 );

function woocommerce_gateway_st() {
	include_once plugin_dir_path( __FILE__ ) . 'classes/class-wc-st-gateway.php';
}
