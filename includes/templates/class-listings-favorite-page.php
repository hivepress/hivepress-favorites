<?php
/**
 * Listings favorite page template.
 *
 * @package HivePress\Templates
 */

namespace HivePress\Templates;

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Listings favorite page template class.
 *
 * @class Listings_Favorite_Page
 */
class Listings_Favorite_Page extends User_Account_Page {

	/**
	 * Class constructor.
	 *
	 * @param array $args Template arguments.
	 */
	public function __construct( $args = [] ) {
		$args = hp\merge_trees(
			[
				'blocks' => [
					'page_content' => [
						'blocks' => [
							'listings'           => [
								'type'    => 'listings',
								'columns' => 2,
								'_order'  => 10,
							],

							'listing_pagination' => [
								'type'   => 'part',
								'path'   => 'page/pagination',
								'_order' => 20,
							],
						],
					],
				],
			],
			$args
		);

		parent::__construct( $args );
	}
}
