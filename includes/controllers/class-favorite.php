<?php
/**
 * Favorite controller.
 *
 * @package HivePress\Controllers
 */

namespace HivePress\Controllers;

use HivePress\Helpers as hp;
use HivePress\Models;
use HivePress\Blocks;

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
					[
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

					'view_listings' => [
						'title'    => esc_html__( 'My Favorites', 'hivepress-favorites' ),
						'path'     => '/account/favorites',
						'redirect' => 'redirect_listings_page',
						'action'   => 'render_listings_page',
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

	/**
	 * Redirects listings page.
	 *
	 * @return mixed
	 */
	public function redirect_listings_page() {

		// Check authentication.
		if ( ! is_user_logged_in() ) {
			return add_query_arg( 'redirect', rawurlencode( hp\get_current_url() ), User::get_url( 'login_user' ) );
		}

		// Check listings.
		if ( hp\get_post_id(
			[
				'post_type'   => 'hp_listing',
				'post_status' => 'publish',
				'post__in'    => array_merge(
					[ 0 ],
					hivepress()->favorite->get_listing_ids( get_current_user_id() )
				),
			]
		) === 0 ) {
			return true;
		}
	}

	/**
	 * Renders listings page.
	 *
	 * @return string
	 */
	public function render_listings_page() {

		// Query listings.
		query_posts(
			[
				'post_type'      => 'hp_listing',
				'post_status'    => 'publish',
				'post__in'       => hivepress()->favorite->get_listing_ids( get_current_user_id() ),
				'orderby'        => 'post__in',
				'posts_per_page' => -1,
			]
		);

		// Render page.
		$output  = ( new Blocks\Element( [ 'file_path' => 'header' ] ) )->render();
		$output .= ( new Blocks\Template( [ 'template_name' => 'listings_favorite_page' ] ) )->render();
		$output .= ( new Blocks\Element( [ 'file_path' => 'footer' ] ) )->render();

		return $output;
	}
}
