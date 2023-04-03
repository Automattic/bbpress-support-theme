<?php

/**
 * Topics Loop
 *
 * WPCOM: Change the headers and remove the footer.
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

do_action( 'bbp_template_before_topics_loop' ); ?>

	<ul id="bbp-forum-<?php bbp_forum_id(); ?>" class="spf-topics">

		<?php if ( bbp_is_single_user() ) : ?>

			<li class="spf-topics__header">
				<ul class="spf-topics-row spf-topics__field_names">
					<li class="spf-topics-col spf-topics__title"><?php esc_html_e( 'Topic' ); ?></li>
					<li class="spf-topics-col spf-topics__replies"><?php esc_html_e( 'Replies' ); ?></li>
					<li class="spf-topics-col spf-topics__last-poster"><?php esc_html_e( 'Last poster' ); ?></li>
					<li class="spf-topics-col spf-topics__latest-activity"><?php esc_html_e( 'Latest activity' ); ?></li>
					<?php if ( bbp_is_user_home() && ( bbp_is_favorites() || bbp_is_subscriptions() ) ) : ?>
						<li class="spf-topics-col spf-topics__action">&nbsp;</li>
					<?php endif; ?>
				</ul>
			</li>
		<?php else : ?>
			<li class="spf-topics__header">
				<ul class="spf-topics-row spf-topics__field_names">
					<li class="spf-topics-col spf-topics__title"><?php esc_html_e( 'Topic', 'bbpress' ); ?></li>
					<li class="spf-topics-col spf-topics__replies"><?php esc_html_e( 'Replies' ); ?></li>
					<li class="spf-topics-col spf-topics__latest-activity"><?php esc_html_e( 'Latest activity' ); ?></li>
				</ul>
			</li>
		<?php endif; ?>

		<li class="spf-topics__body">

			<?php do_action( 'bbp_template_before_topics_loop_render' ); ?>

			<?php while ( bbp_topics() ) : ?>

				<?php bbp_the_topic(); ?>
				<?php bbp_get_template_part( 'loop', 'single-topic' ); ?>

			<?php endwhile; ?>

		</li>

	</ul><!-- #bbp-forum-<?php bbp_forum_id(); ?> -->

<?php do_action( 'bbp_template_after_topics_loop' );
