<?php
/**
 * Contains plugin settings.
 *
 * @package HivePress\Favorites
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$settings = [

	// Listing component.
	'listing'  => [

		// Pages.
		'pages' => [
			'favorites' => [
				'title'      => esc_html__( 'My Favorites', 'hivepress-favorites' ),
				'regex'      => '^account/favorites/?$',
				'redirect'   => 'index.php?hp-listing-favorites=1',
				'capability' => 'read',
				'template'   => 'listing_favorites',
				'menu'       => 'user_account',
				'order'      => 20,
			],
		],
	],

	// Favorite component.
	'favorite' => [

		// Forms.
		'forms'     => [
			'update' => [
				'capability'    => 'read',

				'fields'        => [
					'post_id' => [
						'type' => 'hidden',
					],
				],

				'submit_button' => [
					'name'       => esc_html__( 'Add to Favorites', 'hivepress-favorites' ),

					'attributes' => [
						'data-name' => esc_html__( 'Remove from Favorites', 'hivepress-favorites' ),
					],
				],
			],
		],

		// Templates.
		'templates' => [
			'listing_favorites' => [
				'parent' => 'user_account',

				'areas'  => [
					'content' => [
						'loop' => [
							'path'  => 'listing/parts/loop-archive',
							'order' => 20,
						],
					],
				],
			],

			'archive_listing'   => [
				'areas' => [
					'actions' => [
						'favorite' => [
							'path'  => 'favorite/parts/favorite-link',
							'order' => 20,
						],
					],
				],
			],

			'single_listing'    => [
				'areas' => [
					'actions' => [
						'favorite' => [
							'path'  => 'favorite/parts/favorite-button',
							'order' => 20,
						],
					],
				],
			],
		],
	],
];
