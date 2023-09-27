<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '123456' );

/** Database hostname */
define( 'DB_HOST', 'db' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'w?U*|-m*>CJNQVr!_acuTqp5&2Wd(<eG5nd/#z[G]A:yr0P!HWaENnf^}8`oK+ 3' );
define( 'SECURE_AUTH_KEY',  '=$!uKJWHtkwj,|LsRT|Mu}yh?jvmfBg[5!p4<Rv?: @R}YzLPfA^Qh5:k])n4fZ$' );
define( 'LOGGED_IN_KEY',    'Z6:{];OKBVE![-b5(KQ1)%!9M(O5IE|I@`JXL.?yAc5ItF<z;G^yZ^^iw|3pU-WG' );
define( 'NONCE_KEY',        'NH{iOUxwd^+7y|j6QX+n<*W783ib$,J_!p{f[[i%?PSBsMTLFE~v.u~)zR<::wrK' );
define( 'AUTH_SALT',        '1UN+Kg5 ~P7dqp-X6G^^6#kQ`/%Xa);g8V?;4Sx/nj6yzG):P0/szgJxQc/smjSF' );
define( 'SECURE_AUTH_SALT', 'u[+k!:I]*CrH5QQ#pnE+vTZj`o<bY2xduF0hV8mFoi3b)m3_l|m>zb~q^5~r.de]' );
define( 'LOGGED_IN_SALT',   '54O{/5}tO}/}y+WqfFV&Uajz}_@V}$!w~H.$H5czrYYNc[Jf8vD*M60)o:O7:zKC' );
define( 'NONCE_SALT',       '<uLh7usD0r;k~N{PgJl+88DjdkMy-w4JLL89{6zlc{PH{MA$^e|Uxu@E}oV;(4+I' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
