<?php
/**
 * Favorite model.
 *
 * @package HivePress\Models
 */

namespace HivePress\Models;

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Favorite model class.
 *
 * @class Favorite
 */
class Favorite extends Comment {

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
						'min_value' => 1,
						'required'  => true,
					],

					'listing_id' => [
						'type'      => 'number',
						'min_value' => 1,
						'required'  => true,
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
