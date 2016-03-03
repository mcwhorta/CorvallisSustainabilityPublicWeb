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
define('DB_NAME', 'sustain');

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
define('AUTH_KEY',         '^#.}w|>nRT~ sY.Qxt`IAeE$Q.&=V:u$Pt3o2;lCWB+/fb&0Tah#U*QC|2QE!~,$');
define('SECURE_AUTH_KEY',  'dhi!#1b]zu]QCpgS+:4Us^NP(#K[BJ|-Cds:z/-i#0+22H;AeIb_$_q-)Xc/546f');
define('LOGGED_IN_KEY',    'Z?+$-7}L22R U/Zi^xfvn7ti1/TlL L.#`Lb|gd8!hWk?-w&>)a1U-StCbl;h,9O');
define('NONCE_KEY',        'd0lcj;MJL5NuQ$3&IF?/E[ZoMhX5R;zS*faeWqX%]7hhG0@pa#Pw*Htk-#X+OS|&');
define('AUTH_SALT',        'EvJT;%Q3nsnNwZMp}%-NkNufO^~Q=,C~;9,]:yl!Ql&k7UUGr<~o>UEFBR#(?Z]`');
define('SECURE_AUTH_SALT', '$ryU8G)a3cX}SNX+mBxjB5G3^%k1nSUjA #J=DQU*2Uk-{,( .Z(+a48j&fs/hOM');
define('LOGGED_IN_SALT',   ')SD<-yjE4:|/*g6J]Pv+DfE{5c|;/P-x+V0aW 5^~>&[BumLv<fT|)[P+O+kl(3W');
define('NONCE_SALT',       'uc]pzK,#|u7(0|yezS|}-:*G@al|-=x74Hk/9:;Q|{&ICN/x|WGPID}fG?m+@|-$');

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
