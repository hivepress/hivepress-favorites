<?php
namespace HivePress\Favorites;

/**
 * Tests favorites.
 *
 * @class Favorite_Test
 */
class Favorite_Test extends \WP_UnitTestCase {

	/**
	 * Post ID.
	 *
	 * @var int
	 */
	public $post_id;

	/**
	 * Update arguments.
	 *
	 * @var array
	 */
	public $update_args;

	/**
	 * Get arguments.
	 *
	 * @var array
	 */
	public $get_args;

	/**
	 * Setups test.
	 */
	public function setUp() {
		parent::setUp();

		// Create user and login.
		wp_set_current_user( $this->factory->user->create() );

		// Create post.
		$this->post_id = $this->factory->post->create( [ 'post_type' => 'hp_listing' ] );

		// Set default arguments.
		$this->update_args = [ 'post_id' => $this->post_id ];

		$this->get_args = [
			'type'    => 'hp_favorite',
			'user_id' => get_current_user_id(),
			'post_id' => $this->post_id,
		];
	}

	/**
	 * Tests updating.
	 */
	public function test_updating() {

		// Test if favorite is added.
		hivepress()->favorite->update( $this->update_args );

		$this->assertCount( 1, get_comments( $this->get_args ) );

		// Test if favorite is removed.
		hivepress()->favorite->update( $this->update_args );

		$this->assertCount( 0, get_comments( $this->get_args ) );

		// Test invalid post types.
		wp_update_post(
			[
				'ID'        => $this->post_id,
				'post_type' => 'post',
			]
		);

		hivepress()->favorite->update( $this->update_args );

		$this->assertCount( 0, get_comments( $this->get_args ) );

		// Test invalid post status.
		wp_update_post(
			[
				'ID'          => $this->post_id,
				'post_type'   => 'hp_listing',
				'post_status' => 'draft',
			]
		);

		hivepress()->favorite->update( $this->update_args );

		$this->assertCount( 0, get_comments( $this->get_args ) );
	}

	/**
	 * Tests deletion.
	 */
	public function test_deletion() {

		// Test if favorite is added.
		hivepress()->favorite->update( $this->update_args );

		$this->assertCount( 1, get_comments( $this->get_args ) );

		// Delete user.
		wp_delete_user( get_current_user_id() );

		// Test if favorite is removed.
		$this->assertCount( 0, get_comments( $this->get_args ) );
	}
}
