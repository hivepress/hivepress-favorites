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
	protected function boot() {

		// Get listing.
		$listing = $this->get_context( 'listing' );

		if ( hp\is_class_instance( $listing, '\HivePress\Models\Listing' ) ) {

			// Set URL.
			$this->url = hivepress()->router->get_url(
				'listing_favorite_action',
				[
					'listing_id' => $listing->get_id(),
				]
			);

			// Set active flag.
			if ( in_array( $listing->get_id(), hivepress()->request->get_context( 'favorite_ids', [] ), true ) ) {
				$this->active = true;
			}
		}

		parent::boot();
	}
}
