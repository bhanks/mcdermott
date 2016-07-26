<?php
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
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'mcdermott');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'Acdn&K<WrPNJw5=G HtDR*q;)u.vNYnWDM2uxV*}YdXzNJRDJ7W-YR`#,lOc[G),');
define('SECURE_AUTH_KEY',  'FTV2C49@~BpJ&Iqo}Nk0JgYnGLoN(]Z?);9mEk760&U|8|U:X_<2Yg:0U)n<Qa8w');
define('LOGGED_IN_KEY',    'QRAwMdrA{s<.J/I9NU>2/<d^9z{_sn>F[cD}2eom;W-]!IF%%B^x6z0Lb.#90Q`b');
define('NONCE_KEY',        '{swb_Z1Kk)2P*([|ouE%`{{lfM~y-^N!JVBE]Cd{c6DJtQ-GxWJ `rES]K>OA-N5');
define('AUTH_SALT',        'cB3223Zw,hc$D)oWQp+;]!JL7X=B52Q6<<pG5f^)}/z:2}XxN`6Ol/$s?`0UK1i]');
define('SECURE_AUTH_SALT', '7D-%fuRkPWbSGCd2 %VS@okh&$g~q(`4hzQE3/_;Ep!9]Euu35OLY0^QuY,Vzy68');
define('LOGGED_IN_SALT',   ')U[i|i%lutY+=UA!>JU!_k1%iw5V6F$UwLVn4pOgf!}qe@Wh>^YGgsqaQ=j1[P3=');
define('NONCE_SALT',       'mB@ae 7<yCfB#~i.  dIxQ`oKhO:=xH,jt*X!U`U<+?gE9)Up.]6F]CpfXWWbcA1');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
