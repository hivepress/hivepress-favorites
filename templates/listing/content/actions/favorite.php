<?php
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! is_user_logged_in() ) :
	?>
	<a href="#hp-user-login" title="<?php esc_attr_e( 'Add to Favorites', 'hivepress-favorites' ); ?>" class="hp-listing__action hp-js-link" data-type="popup"><i class="fas fa-heart"></i></a>
	<?php
else :
	echo hivepress()->form->render_link(
		'favorite__update',
		[
			'text'       => '<i class="fas fa-heart"></i>',
			'attributes' => [
				'class' => 'hp-listing__action',
			],
		]
	);
endif;
