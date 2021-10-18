=== Trust Payments Gateway for WooCommerce ===
Contributors: Illustrate Digital, Trust Payments
Tags: payment, secure trading, trust payments, woocommerce, gateway
Requires at least: 2.8
Tested up to: 5.7
Stable tag: 3.0.6
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

The official Trust Payments payment gateway for WooCommerce. Easily take payments using Trust Payments (formerly Secure Trading).

== Description ==
The official Trust Payments payment gateway for WooCommerce. Easily take payments using Trust Payments.

**Important Update** - Please note, this version is now deprecated and support will be removed on the 10th September 2021. Please find the newest version of the plugin [here](https://wordpress.org/plugins/trust-payments-gateway-3ds2/), which includes support for 3DSv2.

**Important note regarding v3** - Secure Trading has now rebranded to Trust Payments – your payments will continue to be processed as normal and no action is needed, so sit back and relax. If you have any questions about your payment services please contact your account manager 

**Important note regarding v2** - This version is a complete rebuild of the plugin which that adds 3D Secure functionality. Please check your gateway settings to ensure the update works correctly for you.

Please note: Support for free trials with subscriptions will be added in the next release of the module, coming soon.


== Installation ==
= Using The WordPress Dashboard =

1. Navigate to the *Add New* Plugin Dashboard
2. Search for Trust Payments for WooCommerce
3. Install
4. Activate the plugin on your WordPress Plugin Dashboard
5. Configure the plugin with your unique account details

= Using FTP =

1. Extract `secure-trading-gateway-for-woocommerce.zip` to your computer
2. Upload the unzipped directory to your `wp-content/plugins` directory
3. Activate the plugin on the WordPress Plugins Dashboard

== Changelog ==
= 3.0.5 =
* Bug fix.

= 3.0.4 =
* Bug fix.

= 3.0.3 =
* Bug fix.

= 3.0.2 =
* Fixed some PHP errors and sanitised code.

= 3.0.1 =
* Updating tested up to data.

= 3.0.0 =
* Plugin rebranded to Trust Payments. Your payments will continue to be processed as normal and no action is needed, so sit back and relax. If you have any questions about your payment services please contact your account manager.

= 2.4.2 =
* Fixed some ST test card numbers which were failing to process test payments

= 2.4 =
* Fixed some redirection errors that occurred at 3DS stage, causing duplicate payments in some cases
* Fixed an issue with caching errors caused by a Chrome update.
= 2.3.1 =
* Bug fixes.

= 2.3 =
* Added option for 4 year expiry date
* Fixed a conflict between 3D Secure and user creation
* General 3D secure improvements

= 2.2.1 =
* Bug fixes.

= 2.2.0 =
* Added support for less commonly used card numbers and IIN ranges
* Improved validation error messages to make them clearer
* Improved support for AMEX cards with 15 digit length

= 2.1.0 =
* Added validation for custom countries created by users that contain more than two characters country code length.
* Implemented functionality to use MM / YY instead of MM / YYYY for card expiry dates
* Improved Woocommerce Sequential Order Numbers Pro v1.12.0 extension.
* Fixed various JS validation errors

= 2.0.8 =
* Fixed an error with processing payment when Stripe and PayPal are also enabled gateways.

= 2.0.7 =
* Fixed error with incorrect expiry validation

= 2.0.6 =
* Minor changes missed in 2.0.5

= 2.0.5 =
* Support for custom country codes
* Expire date format changed from MM / YYYY to MM / YY
* Improved compatibility with WooCommerce Sequential Order Numbers Pro
* Fixed an error where attempting checkout without entering a CVC number or expiry caused the page to lock up

= 2.0.4 =
* Minor update to improve compatibility with older versions of PHP

= 2.0.3 =
* Fixed a bug with custom version tracking

= 2.0.2 =
* Minor update to version tracking

= 2.0.1 =
* Fixed a unique error with selecting new payment methods
* Added version tracking

= 2.0.0 =
* The plugin has been completely rebuilt to fully utilise the Secure Trading PHP SDK.
* 3D Secure authentication added.

= 1.1.2 =
* Fixed an issue with iFrame option not processing payments correctly.

= 1.1.1 =
* Improved compatibility with Sequential Order Numbers and Sequential Order Numbers Pro not processing orders correctly when custom prefixes were used.
* Added new version prompt banner

= 1.1.0 =
* Updated variables to fix issue with iFrame payments not updating order status

= 1.0.5 =
* Improved compatibility with Sequential Order Numbers and Sequential Order Numbers Pro not matching order numbers correctly.
* Minor bug fixes

= 1.0.4 =
* Minor compatibility and cosmetic updates.

= 1.0.3 =
* Minor bug fixes

= 1.0.2 =
* Added compatibility with Sequential Order Numbers and Sequential Order Numbers Pro
* Minor bug fixes
* Minor visual improvements

= 1.0.1 =
* Fixed a bug that caused stock to be reduced twice

= 1.0 =
* Initial release

== Upgrade Notice ==
= 2.0.0 =
v2.x is a complete rebuild of the plugin. It adds 3D Secure functionality and better integration with Secure Trading Web Services. Please check the gateway settings after updating to ensure the update works correctly for your store.
