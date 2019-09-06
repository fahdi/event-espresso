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

// ** MySQL settings ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

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
define('AUTH_KEY',         'YbRuLc0kHpwOe5XY8QcTMEfghg47aS3ZhYoEtOZfBJBl/RZdzmcNFhIIy1xD+aH9EM3+chufhTXMK+yZiquSGw==');
define('SECURE_AUTH_KEY',  'gQ6T2dLP0eevweGjjIZ8Bfp5QtjeMr+Uvtieynywj5V0sGsVmmbQyBKc04RR1YP32N+14uP+rqcYbOuEYkLwJQ==');
define('LOGGED_IN_KEY',    'mQbINA4luY3JNmHRQWlaBAcYyXbmjxMslDHBGH75vRxQSHIm4LToyzVJCt0cvj0iusHa38Ti/j2pbZ/zCWKDqQ==');
define('NONCE_KEY',        '5B1jBaiKfGxHWkJob9kwoVsq95gFrw/BqILHAcUoGx0uQTF46VsSPeuwxzYQCGmAa74peirzGc0zcf2D4TTwNQ==');
define('AUTH_SALT',        'ZLd0GSslOuyIKCarOyHvZu5IBzQ3QIF3CoRrvFT/o74Qxtuh8TkBFe58uao1ckjycj5fX7O5072Vxu2qKugMZg==');
define('SECURE_AUTH_SALT', 'CJ/BVEYy/hehTEj0dd7YJfItDnxOj80xLLrE1QmgWJwG4YaTTVLCZTHe1Z3pzdloWYSByc1tYGi7loG40nu/Mw==');
define('LOGGED_IN_SALT',   'KYxB/rbzGvSgBB+rinqduW9RiqogDw8p9JycoN89zV4mdl2NnqROs9upoVjlwKgixZitTcoc0ejA6BXzCXVmdQ==');
define('NONCE_SALT',       'o8BYl++kIqoDyGQFJ0FQktjtA41VUOunuZpTNrUHVbB09oeLHNUlAnESt7buQXCg5tp/i3hFtStpwCYYVlI3UQ==');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';




/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
