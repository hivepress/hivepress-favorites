<?php
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! is_user_logged_in() ) :
	?>
	<a href="#user_login_modal" class="hp-listing__action" title="<?php esc_attr_e( 'Add to Favorites', 'hivepress-favorites' ); ?>"><i class="hp-icon fas fa-heart"></i></a>
	<?php else : ?>
	<a href="#" class="hp-listing__action" title="<?php esc_attr_e( 'Add to Favorites', 'hivepress-favorites' ); ?>"><i class="hp-icon fas fa-heart"></i></a>
	<?php
endif;
