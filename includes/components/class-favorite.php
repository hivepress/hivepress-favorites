<?php
/**
 * Favorite component.
 *
 * @package HivePress\Components
 */

namespace HivePress\Components;

use HivePress\Helpers as hp;
use HivePress\Models;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Favorite component class.
 *
 * @class Favorite
 */
final class Favorite {

	/**
	 * Class constructor.
	 */
	public function __construct() {

		// Delete favorites.
		add_action( 'delete_user', [ $this, 'delete_favorites' ] );

		if ( ! is_admin() ) {

			// Alter templates.
			add_filter( 'hivepress/v1/templates/listing_view_block', [ $this, 'alter_listing_view_block' ] );
			add_filter( 'hivepress/v1/templates/listing_view_page', [ $this, 'alter_listing_view_page' ] );

			// Add menu items.
			add_filter( 'hivepress/v1/menus/account', [ $this, 'add_menu_items' ] );
		}
	}

	/**
	 * Deletes favorites.
	 *
	 * @param int $user_id User ID.
	 */
	public function delete_favorites( $user_id ) {

		// Get favorite IDs.
		$favorite_ids = get_comments(
			[
				'type'    => 'hp_favorite',
				'user_id' => $user_id,
				'fields'  => 'ids',
			]
		);

		// Delete favorites.
		foreach ( $favorite_ids as $favorite_id ) {
			wp_delete_comment( $favorite_id, true );
		}
	}

	/**
	 * Alters listing view block.
	 *
	 * @param array $template Template arguments.
	 * @return array
	 */
	public function alter_listing_view_block( $template ) {
		return hp\merge_trees(
			$template,
			[
				'blocks' => [
					'listing_actions_primary' => [
						'blocks' => [
							'listing_favorite_toggle' => [
								'type'       => 'favorite_toggle',
								'view'       => 'icon',
								'order'      => 20,

								'attributes' => [
									'class' => [ 'hp-listing__action', 'hp-listing__action--favorite' ],
								],
							],
						],
					],
				],
			],
			'blocks'
		);
	}

	/**
	 * Alters listing view page.
	 *
	 * @param array $template Template arguments.
	 * @return array
	 */
	public function alter_listing_view_page( $template ) {
		return hp\merge_trees(
			$template,
			[
				'blocks' => [
					'listing_actions_primary' => [
						'blocks' => [
							'listing_favorite_toggle' => [
								'type'       => 'favorite_toggle',
								'order'      => 20,

								'attributes' => [
									'class' => [ 'hp-listing__action', 'hp-listing__action--favorite' ],
								],
							],
						],
					],
				],
			],
			'blocks'
		);
	}

	/**
	 * Adds menu items.
	 *
	 * @param array $menu Menu arguments.
	 * @return array
	 */
	public function add_menu_items( $menu ) {
		if ( hp\get_post_id(
			[
				'post_type'   => 'hp_listing',
				'post_status' => 'publish',
				'post__in'    => array_merge(
					[ 0 ],
					$this->get_listing_ids( get_current_user_id() )
				),
			]
		) !== 0 ) {
			$menu['items']['favorite_listings'] = [
				'route' => 'favorite/view_listings',
				'order' => 20,
			];
		}

		return $menu;
	}

	/**
	 * Gets listing IDs.
	 *
	 * @param int $user_id User ID.
	 */
	public function get_listing_ids( $user_id ) {

		// Set query arguments.
		$query_args = [
			'type'    => 'hp_favorite',
			'user_id' => $user_id,
		];

		// Get cached IDs.
		$listing_ids = hivepress()->cache->get_user_cache( $user_id, array_merge( $query_args, [ 'fields' => 'post_ids' ] ), 'comment/favorite' );

		if ( is_null( $listing_ids ) ) {
			$listing_ids = array_map( 'absint', wp_list_pluck( get_comments( $query_args ), 'comment_post_ID' ) );

			// Cache IDs.
			if ( count( $listing_ids ) <= 1000 ) {
				hivepress()->cache->set_user_cache( $user_id, array_merge( $query_args, [ 'fields' => 'post_ids' ] ), $listing_ids, 'comment/favorite', DAY_IN_SECONDS );
			}
		}

		return (array) $listing_ids;
	}
}
