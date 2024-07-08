<?php
/**
 * Settings configuration.
 *
 * @package HivePress\Configs
 */

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

return [
	'listings' => [
		'sections' => [
			'display' => [
				'fields' => [
					'listing_count_favorite' => [
						'label'   => esc_html__( 'Favorite listings', 'hivepress-favorites' ),
						'caption' => esc_html__( 'Allow showing favorite count', 'hivepress-favorites' ),
						'type'    => 'checkbox',
						'_order'  => 100,
					],
				],
			],
		],
	],
];
