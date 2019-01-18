<?php
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! is_user_logged_in() ) :
	?>
	<button type="button" class="hp-listing__action hp-js-link" data-url="#hp-user-login" data-type="popup"><?php esc_html_e( 'Add to Favorites', 'hivepress-favorites' ); ?></button>
	<?php
else :
	echo hivepress()->form->render_form(
		'favorite__update',
		[
			'attributes' => [
				'class'     => 'hp-listing__action',
				'data-type' => 'ajax',
			],
		]
	);
endif;
