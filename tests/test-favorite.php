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
	 * Setups test.
	 */
	public function setUp() {
		parent::setUp();

		// Create user and login.
		wp_set_current_user( $this->factory->user->create() );

		// Create post.
		$this->post_id = $this->factory->post->create( [ 'post_type' => 'hp_listing' ] );
	}

	/**
	 * Tests updating.
	 */
	public function test_updating() {

		// Test invalid post types.
		wp_update_post(
			[
				'ID'        => $this->post_id,
				'post_type' => 'post',
			]
		);

		hivepress()->favorite->update( [ 'post_id' => $this->post_id ] );

		$this->assertCount(
			0,
			hivepress()->favorite->get(
				[
					'user_id' => get_current_user_id(),
					'post_id' => $this->post_id,
				]
			)
		);

		// Test invalid post status.
		wp_update_post(
			[
				'ID'          => $this->post_id,
				'post_type'   => 'hp_listing',
				'post_status' => 'draft',
			]
		);

		hivepress()->favorite->update( [ 'post_id' => $this->post_id ] );

		$this->assertCount(
			0,
			hivepress()->favorite->get(
				[
					'user_id' => get_current_user_id(),
					'post_id' => $this->post_id,
				]
			)
		);

		wp_update_post(
			[
				'ID'          => $this->post_id,
				'post_status' => 'publish',
			]
		);

		// Test if favorite is added.
		hivepress()->favorite->update( [ 'post_id' => $this->post_id ] );

		$this->assertCount(
			1,
			hivepress()->favorite->get(
				[
					'user_id' => get_current_user_id(),
					'post_id' => $this->post_id,
				]
			)
		);

		// Test if favorite is removed.
		hivepress()->favorite->update( [ 'post_id' => $this->post_id ] );

		$this->assertCount(
			0,
			hivepress()->favorite->get(
				[
					'user_id' => get_current_user_id(),
					'post_id' => $this->post_id,
				]
			)
		);
	}

	/**
	 * Tests deletion.
	 */
	public function test_deletion() {

		// Test if favorite is added.
		hivepress()->favorite->update( [ 'post_id' => $this->post_id ] );

		$this->assertCount(
			1,
			hivepress()->favorite->get(
				[
					'user_id' => get_current_user_id(),
					'post_id' => $this->post_id,
				]
			)
		);

		// Delete user.
		wp_delete_user( get_current_user_id() );

		// Test if favorite is removed.
		$this->assertCount( 0, hivepress()->favorite->get() );
	}
}
