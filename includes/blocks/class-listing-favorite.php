<?php
/**
 * Listing favorite block.
 *
 * @package HivePress\Blocks
 */

namespace HivePress\Blocks;

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Listing favorite block class.
 *
 * @class Listing_Favorite
 */
class Listing_Favorite extends Container {

	/**
	 * Bootstraps block properties.
	 */
	protected function bootstrap() {
		$attributes = [];

		if ( is_user_logged_in() ) {
			$attributes['href'] = '#';

			if ( get_post_type() === 'hp_listing' ) {
				$attributes['data-component'] = 'todo';
				$attributes['data-action']    = hp\get_rest_url( '/listings/' . get_the_ID() );

				if ( 'todo' ) {
					$attributes['data-state'] = 'active';
					$attributes['data-text']  = esc_html__( 'Remove from Favorites', 'hivepress-favorites' );
				} else {
					$attributes['data-text'] = esc_html__( 'Add to Favorites', 'hivepress-favorites' );
				}
			}
		} else {

			// Set popup URL.
			$attributes['href'] = '#user_login_modal';
		}

		$this->attributes = hp\merge_arrays( $this->attributes, $attributes );

		parent::bootstrap();
	}
}
