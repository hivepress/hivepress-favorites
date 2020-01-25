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
	 * Class constructor.
	 *
	 * @param array $args Model arguments.
	 */
	public function __construct( $args = [] ) {
		$args = hp\merge_arrays(
			[
				'fields' => [
					'added_date' => [
						'type'   => 'date',
						'format' => 'Y-m-d H:i:s',
						'_alias' => 'comment_date',
					],

					'user'       => [
						'type'      => 'number',
						'min_value' => 1,
						'required'  => true,
						'_alias'    => 'user_id',
						'_model'    => 'user',
					],

					'listing'    => [
						'type'      => 'number',
						'min_value' => 1,
						'required'  => true,
						'_alias'    => 'comment_post_ID',
						'_model'    => 'listing',
					],
				],
			],
			$args
		);

		parent::__construct( $args );
	}
}
