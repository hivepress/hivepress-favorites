<?php
/**
 * Listing view block template.
 *
 * @package HivePress\Configs\Templates
 */

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

return [
	'blocks' => [
		'container' => [
			'blocks' => [
				'footer' => [
					'blocks' => [
						'actions_primary' => [
							'blocks' => [
								'favorite_toggle' => [
									'type'  => 'listing_favorite_toggle',
									'class' => [ 'hp-listing__action' ],
									'order' => 20,
								],
							],
						],
					],
				],
			],
		],
	],
];
