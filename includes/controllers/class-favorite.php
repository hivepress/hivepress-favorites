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
final class Favorite extends Controller {

	/**
	 * Class constructor.
	 *
	 * @param array $args Controller arguments.
	 */
	public function __construct( $args = [] ) {
		$args = hp\merge_arrays(
			[
				'routes' => [
					'listing_favorite_action' => [
						'base'   => 'listing_resource',
						'path'   => '/favorite',
						'method' => 'POST',
						'action' => [ $this, 'favorite_listing' ],
						'rest'   => true,
					],

					'listings_favorite_page'  => [
						'title'     => esc_html__( 'Favorites', 'hivepress-favorites' ),
						'base'      => 'user_account_page',
						'path'      => '/favorites',
						'redirect'  => [ $this, 'redirect_listings_favorite_page' ],
						'action'    => [ $this, 'render_listings_favorite_page' ],
						'paginated' => true,
					],
				],
			],
			$args
		);

		parent::__construct( $args );
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
		$listing = Models\Listing::query()->get_by_id( $request->get_param( 'listing_id' ) );

		if ( empty( $listing ) || $listing->get_status() !== 'publish' ) {
			return hp\rest_error( 404 );
		}

		// Get favorites.
		$favorites = Models\Favorite::query()->filter(
			[
				'user'    => get_current_user_id(),
				'listing' => $listing->get_id(),
			]
		)->get();

		if ( $favorites->count() ) {

			// Delete favorites.
			$favorites->delete();
		} else {

			// Add favorite.
			$favorite = ( new Models\Favorite() )->fill(
				[
					'user'    => get_current_user_id(),
					'listing' => $listing->get_id(),
				]
			);

			if ( ! $favorite->save() ) {
				return hp\rest_error( 400, $favorite->_get_errors() );
			}
		}

		return hp\rest_response(
			200,
			[
				'id' => $listing->get_id(),
			]
		);
	}

	/**
	 * Redirects listings favorite page.
	 *
	 * @return mixed
	 */
	public function redirect_listings_favorite_page() {

		// Check authentication.
		if ( ! is_user_logged_in() ) {
			return hivepress()->router->get_return_url( 'user_login_page' );
		}

		// Check listings.
		if ( ! Models\Listing::query()->filter(
			[
				'status' => 'publish',
				'id__in' => hivepress()->request->get_context( 'favorite_ids', [] ),
			]
		)->get_first_id() ) {
			return hivepress()->router->get_url( 'user_account_page' );
		}

		return false;
	}

	/**
	 * Renders listings favorite page.
	 *
	 * @return string
	 */
	public function render_listings_favorite_page() {

		// Query listings.
		hivepress()->request->set_context(
			'post_query',
			Models\Listing::query()->filter(
				[
					'status' => 'publish',
					'id__in' => hivepress()->request->get_context( 'favorite_ids', [] ),
				]
			)
			->order( 'id__in' )
			->limit( get_option( 'hp_listings_per_page' ) )
			->paginate( hivepress()->request->get_page_number() )
			->get_args()
		);

		// Render template.
		return ( new Blocks\Template(
			[
				'template' => 'listings_favorite_page',

				'context'  => [
					'listings' => [],
				],
			]
		) )->render();
	}
}
