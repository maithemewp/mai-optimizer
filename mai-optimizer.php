<?php
/**
 * Plugin Name: Mai Optimizer
 * Plugin URI:  https://maithemewp.com
 * Description: WordPress optimization plugin for sites running Mai theme.
 * Version:     0.1.0
 * Author:      BizBudding
 * Author URI:  https://bizbudding.com
 * License:     GPL-2.0-or-later
 * Text Domain: mai-optimizer
 * Domain Path: /resources/lang
 */

namespace MaiOptimizer;

require_once __DIR__ . '/vendor/autoload.php';

call_user_func( function () {
	$container = ( new Factory() )->make( Container::class );
	$container->add( Plugin::class, __FILE__ )->add_hooks();
	$container->add( Modules::class )->add_hooks();
	$container->add( Admin::class )->add_hooks();
} );
