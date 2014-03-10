<?php
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
define('DB_NAME', 'Butcher_Shop');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');//'Vwj$s9@Q9z1YbccCK');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');


define('FORCE_SSL_LOGIN', true);

 define('FORCE_SSL_ADMIN', true);

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'z-oTpU|x`:Tr~J9+xb=rmOBJ-zR7O?It>JP]3N47sl[pYu#dEl}*or%/jc|?*aJw');
define('SECURE_AUTH_KEY',  'I+)zOzB]<6U-TRLfR6xbMWPI6u9C6z+?:>}i~elJB=6Vr,A}d 0| ?c|+_UU7 T%');
define('LOGGED_IN_KEY',    '|MI{4TQ.r*+1`#s_{UO~h>E_&NyrQ=br&+4K8*eX?F;N9?}00RHGT5bCHbPx;U$f');
define('NONCE_KEY',        'FV_|1;<+GB&@~q^GU7:Ux8v20WoL43MIEx#1qTSWeY+g46}uKlq^JROMU}YpjplZ');
define('AUTH_SALT',        'C5^u u_)H|v,~ZTP#-Wn$3W`y&4b?=X 7TLG2A7NFdP-S+6OcRcHD3mz--OUwWRZ');
define('SECURE_AUTH_SALT', 'hLCfq#ARH>_i|6!n$!WEt- )1u(Q[+aX!,rx9vhiPYx7_rQX-1o%n$9ede,+Zcp~');
define('LOGGED_IN_SALT',   '@WsB:5L>P5|/k/D&mv@.j_-A)DGP:a|c8Xs^(WI,]D(2]Wu&8h)+%A>4?/h`ZXhM');
define('NONCE_SALT',       ' gbmzP}~jvrgO=:s0dnC}pz[$e8S?IkR;;qBmON~n>,|HOl8f`MQb>%s|i0$8EM$');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress.  A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de.mo to wp-content/languages and set WPLANG to 'de' to enable German
 * language support.
 */
define ('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
