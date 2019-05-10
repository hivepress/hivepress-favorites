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

		// Add menu items.
		add_filter( 'hivepress/v1/menus/account', [ $this, 'add_menu_items' ] );

		// Delete favorites.
		add_action( 'delete_user', [ $this, 'delete_favorites' ] );
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
					wp_list_pluck(
						get_comments(
							[
								'type'    => 'hp_listing_favorite',
								'user_id' => get_current_user_id(),
							]
						),
						'comment_post_ID'
					)
				),
			]
		) !== 0 ) {
			$menu['items']['favorite_listings'] = [
				'route' => 'favorite/view_listings',
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
