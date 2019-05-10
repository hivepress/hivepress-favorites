<?php
/**
 * Listing favorite model.
 *
 * @package HivePress\Models
 */

namespace HivePress\Models;

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Listing favorite model class.
 *
 * @class Listing_Favorite
 */
class Listing_Favorite extends Comment {

	/**
	 * Model name.
	 *
	 * @var string
	 */
	protected static $name;

	/**
	 * Model fields.
	 *
	 * @var array
	 */
	protected static $fields = [];

	/**
	 * Model aliases.
	 *
	 * @var array
	 */
	protected static $aliases = [];

	/**
	 * Class initializer.
	 *
	 * @param array $args Model arguments.
	 */
	public static function init( $args = [] ) {
		$args = hp\merge_arrays(
			[
				'fields'  => [
					'user_id'    => [
						'type'      => 'number',
						'min_value' => 0,
					],

					'listing_id' => [
						'type'      => 'number',
						'min_value' => 0,
					],
				],

				'aliases' => [
					'user_id'         => 'user_id',
					'comment_post_ID' => 'listing_id',
				],
			],
			$args
		);

		parent::init( $args );
	}
}
