<?php
/**
 * Contains plugin settings.
 *
 * @package HivePress/Favorites
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$settings = [

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

		// Pages.
		'pages'     => [
			'list' => [
				'title'      => esc_html__( 'My Favorites', 'hivepress-favorites' ),
				'regex'      => '^account/favorites/?$',
				'redirect'   => 'index.php?hp-favorite-list=1',
				'capability' => 'read',
				'template'   => 'favorite_list',
				'menu'       => 'user_account',
				'order'      => 20,
			],
		],

		// Templates.
		'templates' => [
			'favorite_list'   => [
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

			'archive_listing' => [
				'areas' => [
					'actions' => [
						'favorite' => [
							'path'  => 'listing/content/actions/favorite',
							'order' => 20,
						],
					],
				],
			],

			'single_listing'  => [
				'areas' => [
					'actions' => [
						'favorite' => [
							'path'  => 'listing/single/actions/favorite',
							'order' => 20,
						],
					],
				],
			],
		],
	],
];
