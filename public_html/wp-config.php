<?php
/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache




/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
 //Added by WP-Cache Manager
define('DB_NAME', 'kinivoco_live');

/** MySQL database username */
define('DB_USER', 'kinivoco_liveusr');

/** MySQL database password */
define('DB_PASSWORD', 'Cm$g-Zb,T4oc');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');
/*Force HTTPS on admin site*/
define('FORCE_SSL_ADMIN', true);
if ($_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
       $_SERVER['HTTPS']='on';


/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'l,`y6mjxu|,efF{H(PQjO]1b1Hv<f9&@DkS20g-GznPB393*:5c&UwKU$Qk6Qr13');
define('SECURE_AUTH_KEY',  'fo$DeJml{ro6IUSDYl,3kZ{/:<w2y|I}Ml`7M-K 1$j]PUA :o2$sWkn 0Zh=>OB');
define('LOGGED_IN_KEY',    'u7-D94_S_GJE<jx(Ra6;mn,/+OSiy-q38BEn}SLr8R:&t y%WCUmr54O^RS.ER?I');
define('NONCE_KEY',        '^t]H ?X;`WOMnHX|6zQe5*H!oD^uv!B*diQc+wYl*bN4spDhjjb?OZcRv)-tRbB9');
define('AUTH_SALT',        'AEvIkJ.O/LZr5yY3w/#1-2!m+&}/hW(Y-Jvb?W(3X0qXO}^%>|A}!A734$jJ(|l,');
define('SECURE_AUTH_SALT', '>>e?,8|RI7+,Gzi=@RMA30Tu?@S;GvGWH%W[^p`Vk#3,=M-s+:%L$nwEs.hI:XUV');
define('LOGGED_IN_SALT',   '_$Pk-*^$xkP_pgfH-q%^R#H-*=X_c)O3Hm99MWTp/Lf1- dp9?@0R)5KBq)|:FB,');
define('NONCE_SALT',       'g84p9aKA3m7#|X<AS;0cMTQvCS&Ehu5.wO+$AyWlYCl+$O,e4hb-^k#0$I7ZLo{g');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

define( 'WP_MEMORY_LIMIT', '96M' );

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
