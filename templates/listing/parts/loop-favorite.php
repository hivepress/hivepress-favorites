<?php
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( $listing_query->have_posts() ) :
	echo hivepress()->template->render_part(
		'listing/parts/loop-archive',
		[
			'listing_query' => $listing_query,
			'column_width'  => $column_width,
		]
	);
else :
	?>
	<div class="hp-no-results">
		<p><?php esc_html_e( 'No favorites yet.', 'hivepress-favorites' ); ?></p>
	</div>
	<?php
endif;
