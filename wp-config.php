<?php
define( 'WP_CACHE', true );
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * 
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'u813082887_AUBVB' );

/** MySQL database username */
define( 'DB_USER', 'u813082887_NRlmc' );

/** MySQL database password */
define( 'DB_PASSWORD', 'rrXQlDl9YD' );

/** MySQL hostname */
define( 'DB_HOST', 'mysql' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          '&x@Np1})V|AdT#&<*ZYtG NaTL>Om49Db KWt*XO~jBz U`7 4xz6L)W9i.u{E} ' );
define( 'SECURE_AUTH_KEY',   '|<_.6g8v=kg6Uz4>PEdONqnXVxJil^llOgk^MK1{7747L>bP G5Exu&WfM4e3r|#' );
define( 'LOGGED_IN_KEY',     '1cVVj!/^ZAi4h*IX[)oUT0cd&|5f~u1)Izlkz{u%hv4y%x6UuF_us6O>F0YG3P<W' );
define( 'NONCE_KEY',         '+0 /o=SYS<ei8`+GM.0sQB)xA{H[n{[M]AJ/~AajA#0>7)U?[8.~#Fv@6vmQZmFA' );
define( 'AUTH_SALT',         'b RY*DB:^9O$^x6 <u?_QWOw/E$u7+Cs[{_:PSF~625<`zmZFub^&KT4XX#mcn2Q' );
define( 'SECURE_AUTH_SALT',  'zUuwqCp8]y_@|F.tdm-SuJ3Ch7^>{y8?_Q>R.:]4~TGlcAf<kofit&5xpcoL.d%w' );
define( 'LOGGED_IN_SALT',    ']6PkRN*ck46[eyz%YxA<zdu!:+Yos~ZP/)rta3gN)yU60uza`Ld?m.h<]-|`gMMN' );
define( 'NONCE_SALT',        'FX^a]7||q@2+zHr3LS/%vW]`:~rSk@4+#gAOLc?`3;<B7zz;BwETu*ZXU2U05r*%' );
define( 'WP_CACHE_KEY_SALT', 'so].DcA~d2t^wUPWmnzcX<#4gA$b19}G3Rm2XZe+=WAshbsn1;N9Rw0Jamoqj6a3' );

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';




define( 'WP_AUTO_UPDATE_CORE', 'minor' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
