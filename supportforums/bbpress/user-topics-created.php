<?php

/**
 * User Topics Created
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

do_action( 'bbp_template_before_user_topics_created' ); ?>

	<div class="spf-user-page">
		<?php bbp_get_template_part( 'user', 'name' ); ?>

		<div class="spf-user-section is-topics-created">
			<div class="spf-user-section__title"><?php esc_html_e( 'Topics started' ); ?></div>
			<div class="spf-user-section__content">
				<?php bbp_get_template_part( 'form', 'topic-search' ); ?>

				<?php if ( bbp_get_user_topics_started() ) : ?>

					<?php bbp_get_template_part( 'loop', 'topics' ); ?>

					<?php bbp_get_template_part( 'pagination', 'topics' ); ?>

				<?php else : ?>

					<?php bbp_get_template_part( 'feedback', 'no-topics-created' ); ?>

				<?php endif; ?>
			</div>
		</div>

	</div><!-- #bbp-user-topics-started -->

<?php
do_action( 'bbp_template_after_user_topics_created' );
