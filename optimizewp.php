<?php
/**
 * Plugin Name: OptimizeWP
 * Plugin URI:  https://optimizewp.com
 * Description: Optimize WordPress
 * Version:     0.1.0
 * Author:      OptimizeWP
 * Author URI:  https://optimizewp.com
 * License:     GPL-3.0-or-later
 * Text Domain: optimizewp
 * Domain Path: /resources/lang
 */

namespace OptimizeWP;

require_once __DIR__ . '/vendor/autoload.php';

call_user_func( function () {
	$container = ( new Factory() )->make( Container::class );
	$container->add( Plugin::class, __FILE__ );
	$container->add( Admin::class );
} );
