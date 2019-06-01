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
		'listing_container' => [
			'blocks' => [
				'listing_footer' => [
					'blocks' => [
						'listing_actions_primary' => [
							'blocks' => [
								'listing_favorite_toggle' => [
									'type'       => 'listing_favorite_toggle',
									'view'       => 'icon',
									'order'      => 20,

									'attributes' => [
										'class' => [ 'hp-listing__action' ],
									],
								],
							],
						],
					],
				],
			],
		],
	],
];
