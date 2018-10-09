<?php
namespace HivePress\Favorites;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Manages favorites.
 *
 * @class Favorite
 */
class Favorite extends \HivePress\Component {

	/**
	 * Class constructor.
	 *
	 * @param array $settings
	 */
	public function __construct( $settings ) {
		parent::__construct( $settings );

		// Update favorites.
		add_action( 'hivepress/form/submit_form/favorite__update', [ $this, 'update' ] );

		// Delete favorites.
		add_action( 'delete_user', [ $this, 'delete' ] );

		// Manage forms.
		add_filter( 'hivepress/form/form_args/favorite__update', [ $this, 'set_form_args' ] );
		add_filter( 'hivepress/form/form_values/favorite__update', [ $this, 'set_form_values' ] );

		if ( ! is_admin() ) {

			// Set template context.
			add_filter( 'hivepress/template/template_context/favorite_list', [ $this, 'set_template_context' ] );
		}
	}

	/**
	 * Gets favorites.
	 *
	 * @param int $post_id
	 * @return array
	 */
	private function get( $post_id = 0 ) {
		$args = [
			'type'      => 'hp_favorite',
			'post_type' => 'hp_listing',
			'user_id'   => get_current_user_id(),
			'fields'    => 'ids',
		];

		if ( 0 !== $post_id ) {
			$args['post_id'] = $post_id;
		}

		return get_comments( $args );
	}

	/**
	 * Updates favorites.
	 *
	 * @param array $values
	 */
	public function update( $values ) {

		// Get post ID.
		$post_id = hp_get_post_id(
			[
				'post__in'    => [ absint( $values['post_id'] ) ],
				'post_type'   => 'hp_listing',
				'post_status' => 'publish',
			]
		);

		if ( 0 !== $post_id ) {

			// Get favorite IDs.
			$favorite_ids = $this->get( $post_id );

			// Add or delete favorite.
			if ( ! empty( $favorite_ids ) ) {
				foreach ( $favorite_ids as $favorite_id ) {
					wp_delete_comment( $favorite_id, true );
				}
			} else {
				wp_insert_comment(
					[
						'comment_type'    => 'hp_favorite',
						'comment_post_ID' => $post_id,
						'user_id'         => get_current_user_id(),
					]
				);
			}
		}
	}

	/**
	 * Deletes favorites.
	 *
	 * @param int $user_id
	 */
	public function delete( $user_id ) {

		// Get favorite IDs.
		$favorite_ids = $this->get();

		// Delete favorites.
		foreach ( $favorite_ids as $favorite_id ) {
			wp_delete_comment( $favorite_id, true );
		}
	}

	/**
	 * Sets form arguments.
	 *
	 * @param array $args
	 * @return array
	 */
	public function set_form_args( $args ) {

		// Get favorite IDs.
		$favorite_ids = $this->get( get_the_ID() );

		if ( ! empty( $favorite_ids ) ) {

			// Set submit button.
			$name = $args['submit_button']['name'];

			$args['submit_button']['name'] = $args['submit_button']['attributes']['data-name'];

			$args['submit_button']['attributes']['data-name']  = $name;
			$args['submit_button']['attributes']['data-state'] = 'active';
		}

		return $args;
	}

	/**
	 * Sets form values.
	 *
	 * @param array $values
	 * @return array
	 */
	public function set_form_values( $values ) {
		$values['post_id'] = get_the_ID();

		return $values;
	}

	/**
	 * Sets template context.
	 *
	 * @param array $context
	 * @return array
	 */
	public function set_template_context( $context ) {
		$context['listing_query'] = new \WP_Query(
			[
				'post_type'      => 'hp_listing',
				'post__in'       => array_merge(
					[ 0 ],
					wp_list_pluck(
						get_comments(
							[
								'type'      => 'hp_favorite',
								'post_type' => 'hp_listing',
								'user_id'   => get_current_user_id(),
							]
						),
						'comment_post_ID'
					)
				),
				'orderby'        => 'post__in',
				'posts_per_page' => -1,
			]
		);

		$context['column_width'] = 6;

		return $context;
	}
}
