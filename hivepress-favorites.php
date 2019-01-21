<?php
/**
 * Plugin Name: HivePress Favorites
 * Description: Favorite listings add-on for HivePress plugin.
 * Version: 1.0.1
 * Author: HivePress
 * Author URI: https://hivepress.co/
 * Text Domain: hivepress-favorites
 * Domain Path: /languages/
 *
 * @package HivePress\Favorites
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Register plugin path.
add_filter(
	'hivepress/core/plugin_paths',
	function( $paths ) {
		return array_merge( $paths, [ dirname( __FILE__ ) ] );
	}
);
