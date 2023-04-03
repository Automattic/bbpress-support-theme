<?php

/**
 * User Favorites
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

do_action( 'bbp_template_before_user_favorites' ); ?>

	<div class="spf-user-page">
		<?php bbp_get_template_part( 'user', 'name' ); ?>

		<div class="spf-user-section is-favorites">
			<div class="spf-user-section__title"><?php esc_html_e( 'Favorite topics' ); ?></div>
			<div class="spf-user-section__content">
				<?php bbp_get_template_part( 'form', 'topic-search' ); ?>

				<?php if ( bbp_get_user_favorites() ) : ?>

					<?php bbp_get_template_part( 'loop', 'topics' ); ?>

					<?php bbp_get_template_part( 'pagination', 'topics' ); ?>

				<?php else : ?>

					<?php bbp_get_template_part( 'feedback', 'no-topics-favorited' ); ?>

				<?php endif; ?>
			</div>
		</div>

	</div><!-- #bbp-user-favorites -->

<?php
do_action( 'bbp_template_after_user_favorites' );
