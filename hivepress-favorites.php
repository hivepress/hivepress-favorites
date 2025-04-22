<?php
/**
 * Plugin Name: HivePress Favorites
 * Description: Allow users to keep a list of favorite listings.
 * Version: 1.2.2
 * Author: HivePress
 * Author URI: https://hivepress.io/
 * Text Domain: hivepress-favorites
 * Domain Path: /languages/
 *
 * @package HivePress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Register extension directory.
add_filter(
	'hivepress/v1/extensions',
	function( $extensions ) {
		$extensions[] = __DIR__;

		return $extensions;
	}
);
