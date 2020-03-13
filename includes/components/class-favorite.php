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
final class Favorite extends Component {

	/**
	 * Class constructor.
	 *
	 * @param array $args Component arguments.
	 */
	public function __construct( $args = [] ) {

		// Delete favorites.
		add_action( 'hivepress/v1/models/user/delete', [ $this, 'delete_favorites' ] );

		if ( ! is_admin() ) {

			// Set favorites.
			add_action( 'init', [ $this, 'set_favorites' ], 100 );

			// Alter account menu.
			add_filter( 'hivepress/v1/menus/user_account', [ $this, 'alter_account_menu' ] );

			// Alter templates.
			add_filter( 'hivepress/v1/templates/listing_view_block', [ $this, 'alter_listing_view_block' ] );
			add_filter( 'hivepress/v1/templates/listing_view_page', [ $this, 'alter_listing_view_page' ] );
		}

		parent::__construct( $args );
	}

	/**
	 * Deletes favorites.
	 *
	 * @param int $user_id User ID.
	 */
	public function delete_favorites( $user_id ) {
		Models\Favorite::query()->filter(
			[
				'user' => $user_id,
			]
		)->delete();
	}

	/**
	 * Sets favorites.
	 */
	public function set_favorites() {

		// Check authentication.
		if ( ! is_user_logged_in() ) {
			return;
		}

		// Set query.
		$query = Models\Favorite::query()->filter(
			[
				'user' => get_current_user_id(),
			]
		)->order( [ 'added_date' => 'desc' ] );

		// Get cached IDs.
		$favorite_ids = hivepress()->cache->get_user_cache( get_current_user_id(), array_merge( $query->get_args(), [ 'fields' => 'listing_ids' ] ), 'models/favorite' );

		if ( is_null( $favorite_ids ) ) {

			// Get favorite IDs.
			$favorite_ids = array_map(
				function( $favorite ) {
					return $favorite->get_listing__id();
				},
				$query->get()->serialize()
			);

			// Cache IDs.
			if ( count( $favorite_ids ) <= 1000 ) {
				hivepress()->cache->set_user_cache( get_current_user_id(), array_merge( $query->get_args(), [ 'fields' => 'listing_ids' ] ), 'models/favorite', $favorite_ids );
			}
		}

		// Set request context.
		hivepress()->request->set_context( 'favorite_ids', $favorite_ids );
	}

	/**
	 * Alters account menu.
	 *
	 * @param array $menu Menu arguments.
	 * @return array
	 */
	public function alter_account_menu( $menu ) {
		if ( Models\Listing::query()->filter(
			[
				'status' => 'publish',
				'id__in' => hivepress()->request->get_context( 'favorite_ids', [] ),
			]
		)->get_first_id() ) {
			$menu['items']['listings_favorite'] = [
				'route'  => 'listings_favorite_page',
				'_order' => 20,
			];
		}

		return $menu;
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
								'_order'     => 20,

								'attributes' => [
									'class' => [ 'hp-listing__action', 'hp-listing__action--favorite' ],
								],
							],
						],
					],
				],
			]
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
					'listing_actions_secondary' => [
						'blocks' => [
							'listing_favorite_toggle' => [
								'type'       => 'favorite_toggle',
								'_order'     => 20,

								'attributes' => [
									'class' => [ 'hp-listing__action', 'hp-listing__action--favorite' ],
								],
							],
						],
					],
				],
			]
		);
	}
}
