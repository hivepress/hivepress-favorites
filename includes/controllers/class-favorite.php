<?php
/**
 * Favorite controller.
 *
 * @package HivePress\Controllers
 */

namespace HivePress\Controllers;

use HivePress\Helpers as hp;
use HivePress\Models;
use HivePress\Forms;
use HivePress\Menus;
use HivePress\Blocks;
use HivePress\Emails;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Favorite controller class.
 *
 * @class Favorite
 */
class Favorite extends Controller {

	/**
	 * Controller name.
	 *
	 * @var string
	 */
	protected static $name;

	/**
	 * Controller routes.
	 *
	 * @var array
	 */
	protected static $routes = [];

	/**
	 * Class initializer.
	 *
	 * @param array $args Controller arguments.
	 */
	public static function init( $args = [] ) {
		$args = hp\merge_arrays(
			[
				'routes' => [
					'path'      => '/listings',
					'rest'      => true,

					'endpoints' => [
						[
							'path'    => '/(?P<id>\d+)/favorite',
							'methods' => 'POST',
							'action'  => 'favorite_listing',
						],
					],
				],
			],
			$args
		);

		parent::init( $args );
	}

	/**
	 * Favorites listing.
	 *
	 * @param WP_REST_Request $request API request.
	 * @return WP_Rest_Response
	 */
	public function favorite_listing( $request ) {

		// Check authentication.
		if ( ! is_user_logged_in() ) {
			return hp\rest_error( 401 );
		}

		// Get listing.
		$listing = Models\Listing::get( $request->get_param( 'id' ) );

		if ( is_null( $listing ) || $listing->get_status() !== 'publish' ) {
			return hp\rest_error( 404 );
		}

		// Get favorite IDs.
		$favorite_ids = get_comments(
			[
				'type'    => 'hp_listing_favorite',
				'user_id' => get_current_user_id(),
				'post_id' => $listing->get_id(),
				'fields'  => 'ids',
			]
		);

		if ( ! empty( $favorite_ids ) ) {

			// Delete favorites.
			foreach ( $favorite_ids as $favorite_id ) {
				wp_delete_comment( $favorite_id, true );
			}
		} else {

			// Add favorite.
			$favorite = new Models\Listing_Favorite();

			$favorite->fill(
				[
					'user_id'    => get_current_user_id(),
					'listing_id' => $listing->get_id(),
				]
			);

			if ( ! $favorite->save() ) {
				return hp\rest_error( 400 );
			}
		}

		return new \WP_Rest_Response(
			[
				'data' => [
					'id' => $listing->get_id(),
				],
			],
			200
		);
	}
}
