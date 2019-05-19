<?php
/**
 * Listing favorite toggle block.
 *
 * @package HivePress\Blocks
 */

namespace HivePress\Blocks;

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Listing favorite toggle block class.
 *
 * @class Listing_Favorite_Toggle
 */
class Listing_Favorite_Toggle extends Toggle {

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
}
