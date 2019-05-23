<?php
/**
 * Listing view page template.
 *
 * @package HivePress\Configs\Templates
 */

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

return [
	'blocks' => [
		'page_content' => [
			'blocks' => [
				'columns' => [
					'blocks' => [
						'sidebar' => [
							'blocks' => [
								'actions_primary' => [
									'blocks' => [
										'favorite_toggle' => [
											'type'       => 'listing_favorite_toggle',
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
		],
	],
];
