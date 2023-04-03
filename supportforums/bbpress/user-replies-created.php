<?php

/**
 * User Replies Created
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

do_action( 'bbp_template_before_user_replies' ); ?>

	<div class="spf-user-page">
		<?php bbp_get_template_part( 'user', 'name' ); ?>

		<div class="spf-user-section is-replies-created">
			<div class="spf-user-section__title"><?php esc_html_e( 'Replies created' ); ?></div>
			<div class="spf-user-section__content">
				<?php bbp_get_template_part( 'form', 'reply-search' ); ?>

				<?php if ( bbp_has_topics() ) : ?>

					<?php bbp_get_template_part( 'loop', 'topics' ); ?>

					<?php bbp_get_template_part( 'pagination', 'topics' ); ?>

				<?php else : ?>

					<?php bbp_get_template_part( 'feedback', 'no-replies-created' ); ?>

				<?php endif; ?>
			</div>
		</div>

	</div><!-- #bbp-user-replies-created -->

<?php
do_action( 'bbp_template_after_user_replies' );
