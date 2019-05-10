<?php
/**
 * Favorite component.
 *
 * @package HivePress\Components
 */

namespace HivePress\Components;

use HivePress\Helpers as hp;
use HivePress\Models;
use HivePress\Emails;

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

		// Add routes.
		add_filter( 'hivepress/v1/controllers/listing', [ $this, 'add_routes' ] );

		// Add menu items.
		add_filter( 'hivepress/v1/menus/account', [ $this, 'add_menu_items' ] );

		// Delete favorites.
		add_action( 'delete_user', [ $this, 'delete_favorites' ] );
	}

	/**
	 * Adds routes.
	 *
	 * @param array $controller Controller arguments.
	 * @return array
	 */
	public function add_routes( $controller ) {
		$controller['routes']['favorite_listings'] = [
			'title'    => esc_html__( 'My Favorites', 'hivepress-favorites' ),
			'path'     => '/account/favorites',
			'redirect' => [ $this, 'redirect_favorites_page' ],
			'action'   => [ $this, 'render_favorites_page' ],
		];

		return $controller;
	}

	/**
	 * Adds menu items.
	 *
	 * @param array $menu Menu arguments.
	 * @return array
	 */
	public function add_menu_items( $menu ) {
		if ( is_user_logged_in() ) {
			$menu['items']['favorite_listings'] = [
				'route' => 'listing/favorite_listings',
				'order' => 15,
			];
		}

		return $menu;
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
				'type'    => 'hp_listing_favorite',
				'user_id' => $user_id,
				'fields'  => 'ids',
			]
		);

		// Delete favorites.
		foreach ( $favorite_ids as $favorite_id ) {
			wp_delete_comment( $favorite_id, true );
		}
	}
}
