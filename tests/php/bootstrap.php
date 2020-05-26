<?php
/**
 * Genesis Starter Theme.
 *
 * @package   SeoThemes\GenesisStarterTheme
 * @link      https://genesisstartertheme.com
 * @author    SEO Themes
 * @copyright Copyright © 2019 SEO Themes
 * @license   GPL-2.0-or-later
 */

function trailingslashit( $string ) {
	return rtrim( $string, '/\\' ) . '/';
}

require_once __DIR__ . '/config.php';

define( 'PLUGIN_TESTS_DIR', trailingslashit( __DIR__ ) );
define( 'PLUGIN_UNIT_TESTS_DIR', trailingslashit( PLUGIN_TESTS_DIR . 'unit' ) );
define( 'PLUGIN_ROOT_DIR', trailingslashit( dirname( dirname( __DIR__ ) ) ) );
define( 'PLUGIN_SRC_DIR', trailingslashit( PLUGIN_ROOT_DIR . 'src' ) );
define( 'PLUGIN_VENDOR_DIR', trailingslashit( PLUGIN_ROOT_DIR . 'vendor' ) );

define( 'WP_PLUGIN_DIR', trailingslashit( dirname( PLUGIN_ROOT_DIR ) ) );
define( 'WP_CONTENT_DIR', trailingslashit( dirname( WP_PLUGIN_DIR ) ) );
define( 'WP_THEME_DIR', trailingslashit( WP_PLUGIN_DIR . 'themes' ) );
define( 'WP_PHPUNIT__DIR', trailingslashit( PLUGIN_VENDOR_DIR . 'wp-phpunit/wp-phpunit' ) );

require_once PLUGIN_VENDOR_DIR . 'autoload.php';
