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

		// Delete favorites.
		add_action( 'delete_user', [ $this, 'delete_favorites' ] );
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
