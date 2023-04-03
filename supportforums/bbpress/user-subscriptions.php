<?php

/**
 * User Subscriptions
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

if ( ! bbp_is_subscriptions_active() ) {
	return __( 'Subscriptions are not active.' );
}

if ( ! bbp_is_user_home() && ! current_user_can( 'edit_user', bbp_get_displayed_user_id() ) ) {
	return __( 'You do not have permission to edit this user.' );
}

do_action( 'bbp_template_before_user_subscriptions' ); ?>

	<div id="bbp-user-subscriptions" class="spf-user-page">
		<?php bbp_get_template_part( 'user', 'name' ); ?>

		<div class="spf-user-section is-subscriptions">
			<div class="spf-user-section__title"><?php esc_html_e( 'Subscribed forums' ); ?></div>
			<div class="spf-user-section__content">
				<?php bbp_get_template_part( 'form', 'topic-search' ); ?>

				<?php if ( bbp_get_user_forum_subscriptions() ) : ?>

					<?php bbp_get_template_part( 'loop', 'forums' ); ?>

				<?php else : ?>

					<?php bbp_get_template_part( 'feedback', 'no-forums-subscribed' ); ?>

				<?php endif; ?>
			</div>
		</div>

		<div class="spf-user-section">
			<div class="spf-user-section__title"><?php esc_html_e( 'Subscribed topics' ); ?></div>
			<div class="spf-user-section__content">
				<?php bbp_get_template_part( 'form', 'topic-search' ); ?>

				<?php if ( bbp_get_user_topic_subscriptions() ) : ?>


					<?php bbp_get_template_part( 'loop', 'topics' ); ?>

					<?php bbp_get_template_part( 'pagination', 'topics' ); ?>

				<?php else : ?>

					<?php bbp_get_template_part( 'feedback', 'no-topics-subscribed' ); ?>

				<?php endif; ?>
			</div>
		</div>

	</div><!-- #bbp-user-subscriptions -->

<?php
do_action( 'bbp_template_after_user_subscriptions' );
