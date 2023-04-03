<?php

/**
 * Topics Loop - Single
 *
 * WPCOM: Remove post and author meta.
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

?>

<ul id="spf-topic-<?php bbp_topic_id(); ?>" <?php bbp_topic_class( 0, [ 'spf-topics-row' ] ); ?>>

	<li class="spf-topics-col spf-topics__title">

		<?php if ( ! bbp_is_single_user() ) : ?>

			<div class="spf-topics__author">
				<?php echo bbp_get_topic_author_link( [ 'size' => 80, 'type' => 'avatar' ] ); ?>
			</div>

		<?php endif; ?>

		<div class="spf-topics__content">

			<div class="spf-topics__title-wrapper">

				<?php do_action( 'bbp_theme_before_topic_title' ); ?>
				<?php if ( ! bbp_is_single_user() ) : ?>
					<?php if ( bbp_is_topic_closed() ) : ?>
						<span class="spf-topic-icons-closed" data-tooltip="<?php esc_attr_e( 'Closed' ); ?>"></span>
					<?php endif; ?>
					<?php if ( bbp_is_topic_sticky() ) : ?>
						<span class="spf-topic-icons-pinned" data-tooltip="<?php esc_attr_e( 'Pinned' ); ?>"></span>
					<?php endif; ?>
				<?php endif; ?>

				<a class="spf-topics__permalink" href="<?php bbp_topic_permalink(); ?>"><?php bbp_topic_title(); ?></a>
					
				<?php if ( ! bbp_is_single_user() ) : ?>
					<span class="spf-topic__badges">
						<?php if ( spf_is_resolved_topic() ) : ?>
							<span class="spf-badge is-green"><?php esc_html_e( 'Solved' ); ?></span>
						<?php endif; ?>

						<?php if ( spf_is_topic_author_paid_user() ) : ?>
							<span class="spf-badge is-red"><?php esc_html_e( 'Paid user' ); ?></span>
						<?php endif; ?>
					</span>
				<?php endif; ?>
				
				<?php do_action( 'bbp_theme_after_topic_title' ); ?>

				<?php bbp_topic_row_actions(); ?>

			</div>

			<?php if ( ! bbp_is_single_user() && bbp_is_topic_sticky() ) : ?>

				<div class="spf-topics__excerpt"><?php bbp_reply_excerpt( 0, 250 ); ?></div>

			<?php endif; ?>

			<div class="spf-topics__replies-and-activity">
				<span><?php spf_render_replies_count(); ?></span>
				<span>∙</span>
				<span><?php bbp_topic_freshness_link(); ?></span>
			</div>

		</div>

	</li>

	<?php if ( ! bbp_is_single_user() ) : ?>
		<li class="spf-topics-col spf-topics__avatars">
			<?php spf_render_topic_engager_avatars( bbp_get_topic_id(), 5 ); ?>
		</li>
	<?php endif; ?>


	<li class="spf-topics-col spf-topics__replies">

		<?php esc_html_e( spf_get_replies_count() ); ?>

	</li>

	<?php if ( bbp_is_single_user() ) : ?>
		<li class="spf-topics-col spf-topics__last-poster">

			<?php do_action( 'bbp_theme_before_topic_freshness_author' ); ?>

			<?php
			$last_active_id = bbp_get_topic_last_active_id();
			spf_render_author_link( bbp_get_reply_author_id( $last_active_id ), $last_active_id );
			?>

			<?php do_action( 'bbp_theme_after_topic_freshness_author' ); ?>
		</li>
	<?php endif; ?>

	<li class="spf-topics-col spf-topics__latest-activity">

		<?php do_action( 'bbp_theme_before_topic_freshness_link' ); ?>

		<?php bbp_topic_freshness_link(); ?>

		<?php do_action( 'bbp_theme_after_topic_freshness_link' ); ?>

	</li>

	<?php if ( bbp_is_user_home() && ( bbp_is_favorites() || bbp_is_subscriptions() ) ) : ?>

		<li class="spf-topics-col spf-topics__actions">

			<?php if ( bbp_is_favorites() ) : ?>

				<span class="spf-topics__action" data-tooltip="<?php esc_attr_e( 'Unsubscribed from this forum' ); ?>">

					<?php do_action( 'bbp_theme_before_topic_favorites_action' ); ?>

					<?php
					bbp_topic_favorite_link(
						array(
							'before'    => '',
							'favorite'  => '+',
							'favorited' => spf_get_svg( 'icon-x.svg' ),
						)
					);
					?>

					<?php do_action( 'bbp_theme_after_topic_favorites_action' ); ?>

				</span>

			<?php elseif ( bbp_is_subscriptions() ) : ?>

				<span class="spf-topics__action" data-tooltip="<?php esc_attr_e( 'Unsubscribed from this topic' ); ?>">

					<?php do_action( 'bbp_theme_before_topic_subscription_action' ); ?>

					<?php
					bbp_topic_subscription_link(
						array(
							'before'      => '',
							'subscribe'   => '+',
							'unsubscribe' => spf_get_svg( 'icon-x.svg' ),
						)
					);
					?>

					<?php do_action( 'bbp_theme_after_topic_subscription_action' ); ?>

				</span>

			<?php endif; ?>

		</li>

	<?php endif; ?>

</ul><!-- #bbp-topic-<?php bbp_topic_id(); ?> -->
