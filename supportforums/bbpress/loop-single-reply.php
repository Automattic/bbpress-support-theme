<?php

/**
 * Replies Loop - Single Reply
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

$spf_state_class = '';
$spf_state_label = '';
$spf_badge_class     = '';

if ( bbp_is_topic_trash() || bbp_is_reply_trash() ) {
	$spf_state_class = ' is-trash';
	$spf_state_label = __( 'Trash' );
	$spf_badge_class     = ' is-red';
} else if ( bbp_is_topic_spam() || bbp_is_reply_spam() ) {
	$spf_state_class = ' is-spam';
	$spf_state_label = __( 'Spam' );
	$spf_badge_class     = ' is-red';
} else if ( bbp_is_topic_pending() || bbp_is_reply_pending() ) {
	$spf_state_class = ' is-unapproved';
	$spf_state_label = __( 'Unapproved' );
	$spf_badge_class     = ' is-yellow';
} else if ( spf_is_accepted_answer() ) {
	$spf_state_class = ' is-accepted-answer';
	$spf_state_label = __( 'Accepted answer' );
	$spf_badge_class     = ' is-green';
}

$is_topic = get_post_type() === bbp_get_topic_post_type();
$user_id = $is_topic ? bbp_get_topic_author_id() : bbp_get_reply_author_id();

?>

<div id="post-<?php bbp_reply_id(); ?>" class="spf-reply<?php echo $spf_state_class; ?>" data-reply-background="1">
	<div class="spf-reply__avatar-wrapper<?php echo apply_filters( 'spf_is_staff', $user_id ) ? ' has-wp-logo' : ''; ?>">
		<?php echo $is_topic ? bbp_get_topic_author_link( [ 'type' => 'avatar' ] ) : bbp_get_reply_author_link( [ 'type' => 'avatar' ] ); ?>
	</div>

	<div class="spf-reply__main-wrapper">
		<div class="spf-reply__badge-wrapper" data-badge-placeholder="1">
			<?php if ( $spf_state_label ) : ?>
				<div class="spf-badge<?php echo $spf_badge_class; ?>"><?php echo esc_html( $spf_state_label ); ?></div>
			<?php endif; ?>
		</div><!-- .spf-reply__badge-wrapper -->

		<div class="spf-reply__header">
			<div class="spf-reply__header-left">
				<span class="spf-reply__author-role-wrapper">
					<span class="spf-reply__author"><?php spf_render_author_link( $user_id ); ?></span>

					<span class="spf-reply__dot">·</span>

					<span class="spf-reply__role">
						<?php bbp_user_display_role( $user_id ); ?>
					</span>
				</span>

				<span class="spf-reply__dot is-last">·</span>

				<span class="spf-reply__date">
					<a href="<?php bbp_reply_url(); ?>" class="spf-reply__permalink">
						<?php bbp_reply_post_date(); ?>
					</a>
				</span>
			</div>
			<div class="spf-reply__header-actions">

				<?php if ( bbp_is_topic_trash( bbp_get_reply_topic_id( bbp_get_reply_id() ) ) ) : ?><!-- If the reply's topic is trashed, show copy action only -->

				<div class="spf-reply__icon-action">
					<a href="<?php echo esc_url( bbp_get_reply_url() ); ?>" data-tooltip="<?php echo esc_attr( __( 'Copy link' ) ); ?>" data-clipboard-text="<?php esc_attr( bbp_get_reply_url() ); ?>">
						<img src="<?php spf_render_images_path( 'icon-link.svg' ); ?>" alt="<?php echo esc_attr( __( 'Copy link' ) ); ?>">
					</a>
				</div>

				<?php else : ?>

					<div class="spf-reply__icon-action">
						<a href="<?php echo esc_url( spf_get_topic_reply_link( bbp_get_topic_id() ) ); ?>" data-tooltip="<?php echo esc_attr(  __( 'Reply' ) ); ?>">
							<?php spf_render_svg( 'icon-new-reply.svg' ); ?>
						</a>
					</div>

					<?php if ( $is_topic ): ?>
						<div class="spf-reply__icon-action spf-reply__icon-action--subscribe">
							<?php
							bbp_user_subscribe_link(
								[
									'object_id'   => bbp_get_topic_id(),
									'user_id'    => get_current_user_id(),
								],
								0, true );
							?>
						</div>
					<?php endif; ?>

					<div class="spf-reply__more-actions" data-ellipsis-menu>
						<button data-tooltip="<?php _e( 'More menu...' ); ?>"></button>
						<ul class="spf-reply__actions-dropdown">
							<?php foreach ( spf_get_admin_action_items() as $item ) : ?>

								<?php if ( $item === '_divider' ) : ?>

									<li class="spf-reply__actions-divider"></li>

								<?php elseif ( $item['is_hidden'] !== true ) : ?>

									<li class="spf-reply__action-item<?php echo $item['class'] ? " {$item['class']}" : ''; ?>">
										<a class="spf-reply__action-link" href="<?php echo $item['url'] ? esc_url( $item['url'] ) : 'javascript:;'; ?>" <?php echo $item['data_attrs'] ?? ''; ?>>
											<img src="<?php spf_render_images_path( $item['icon'] ); ?>" alt="<?php echo esc_attr( $item['label'] ); ?>">
											<span><?php esc_html_e( $item['label'] ); ?></span>
										</a>
									</li>

								<?php endif; ?>

							<?php endforeach; ?>
						</ul>
					</div>

				<?php endif; ?>
			</div>
		</div><!-- .spf-reply__header -->

		<div class="spf-reply__content"> <?php //bbp_reply_class(); ?>
			<?php if ( $is_topic ) : ?>

				<?php do_action( 'bbp_theme_before_topic_content' ); ?>

				<?php bbp_topic_content(); ?>

				<?php do_action( 'bbp_theme_after_topic_content' ); ?>

			<?php else : ?>

				<?php do_action( 'bbp_theme_before_reply_content' ); ?>

				<?php bbp_reply_content(); ?>

				<?php do_action( 'bbp_theme_after_reply_content' ); ?>

			<?php endif; ?>
		</div>
	</div><!-- .spf-reply__main-wrapper -->
</div><!-- .spf-reply -->
