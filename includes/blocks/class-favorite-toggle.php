<?php
/**
 * Favorite toggle block.
 *
 * @package HivePress\Blocks
 */

namespace HivePress\Blocks;

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Favorite toggle block class.
 *
 * @class Favorite_Toggle
 */
class Favorite_Toggle extends Toggle {

	/**
	 * Class constructor.
	 *
	 * @param array $args Block arguments.
	 */
	public function __construct( $args = [] ) {
		$args = hp\merge_arrays(
			[
				'icon'     => 'heart',
				'url'      => hp\get_rest_url( '/listings/' . get_the_ID() . '/favorite' ),

				'captions' => [
					esc_html__( 'Add to Favorites', 'hivepress-favorites' ),
					esc_html__( 'Remove from Favorites', 'hivepress-favorites' ),
				],
			],
			$args
		);

		parent::__construct( $args );
	}

	/**
	 * Bootstraps block properties.
	 */
	protected function bootstrap() {

		// Set active property.
		if ( is_user_logged_in() && in_array( get_the_ID(), hivepress()->favorite->get_listing_ids( get_current_user_id() ), true ) ) {
			$this->active = true;
		}

		parent::bootstrap();
	}
}
