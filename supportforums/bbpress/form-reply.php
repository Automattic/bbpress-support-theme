<?php

/**
 * New/Edit Reply
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

$reply_title = sprintf( esc_html__( 'Reply to %s' ), bbp_get_topic_title() );

?>

<div id="spf-forums" class="spf-topic-form">

	<?php if ( bbp_is_reply_edit() ) : ?>

		<?php spf_render_breadcrumb(); ?>

		<h1 class="spf-topic-form__title">

			<?php echo $reply_title; ?>
		
		</h1>

	<?php endif; ?>

	<?php if ( bbp_current_user_can_access_create_reply_form() ) : ?>

		<div id="new-reply-<?php bbp_topic_id(); ?>">

			<form id="new-post" name="new-post" method="post">

				<?php do_action( 'bbp_theme_before_reply_form' ); ?>

				<fieldset>

					<?php if ( ! bbp_is_reply_edit() ) : ?>

						<legend class="spf-topic-form__form-title"><?php esc_html_e( 'Reply to this topic' ); ?></legend>

					<?php endif; ?>

					<?php if ( ! bbp_is_topic_open() && ! bbp_is_reply_edit() ) : ?>

						<ul class="spf-notices">
							<li class="spf-notice is-warning">
								<?php esc_html_e( 'This topic is marked as closed to new replies, however your posting capabilities still allow you to reply.', 'bbpress' ); ?>
							</li>
						</ul>

					<?php endif; ?>

					<?php do_action( 'bbp_theme_before_reply_form_notices' ); ?>

					<?php if ( ! bbp_is_reply_edit() && bbp_is_forum_closed() ) : ?>

						<div class="spf-topic-form__notice-wrapper">

							<ul class="spf-notice is-warning">

								<li><?php esc_html_e( 'This forum is closed to new content, however your posting capabilities still allow you to post.', 'bbpress' ); ?></li>
							
							</ul>

						</div>

					<?php endif; ?>

					<?php do_action( 'bbp_template_notices' ); ?>

					<div>

						<?php bbp_get_template_part( 'form', 'anonymous' ); ?>

						<?php do_action( 'bbp_theme_before_reply_form_content' ); ?>

						<?php bbp_the_content( array( 'context' => 'reply' ) ); ?>

						<?php do_action( 'bbp_theme_after_reply_form_content' ); ?>

						<?php if ( bbp_allow_topic_tags() && current_user_can( 'assign_topic_tags', bbp_get_topic_id() ) ) : ?>

							<?php do_action( 'bbp_theme_before_reply_form_tags' ); ?>

							<div class="spf-topic-form-group">

								<label class="spf-topic-form__label" for="bbp_topic_tags"><?php esc_html_e( 'Tags' ); ?></label>

								<input type="text" value="<?php bbp_form_topic_tags(); ?>" name="bbp_topic_tags" id="bbp_topic_tags" hidden /><!-- Tags value comes here -->

								<div class="spf-topic-form__tag-wrapper" tabindex="-1"><!-- For user interaction -->
									
									<input id="spf-topic-tags" class="spf-topic-form__tag-input" type="text" />
								
								</div>

							</div>

							<?php do_action( 'bbp_theme_after_reply_form_tags' ); ?>

						<?php endif; ?>

						<?php if ( bbp_is_subscriptions_active() && ! bbp_is_anonymous() && ( ! bbp_is_reply_edit() || ( bbp_is_reply_edit() && ! bbp_is_reply_anonymous() ) ) ) : ?>

							<?php do_action( 'bbp_theme_before_reply_form_subscription' ); ?>

							<p class="spf-topic-form-group spf-checkbox-input">

								<input name="bbp_topic_subscription" id="bbp_topic_subscription" type="checkbox" value="bbp_subscribe"<?php bbp_form_topic_subscribed(); ?> />

								<?php if ( bbp_is_reply_edit() && ( bbp_get_reply_author_id() !== bbp_get_current_user_id() ) ) : ?>

									<label for="bbp_topic_subscription"><?php esc_html_e( 'Notify the author of follow-up replies via email.', 'bbpress' ); ?></label>

								<?php else : ?>

									<label for="bbp_topic_subscription">

										<?php echo esc_html( apply_filters( 'bbp_subscribe_user_text', __( 'Notify me of follow-up replies via email.', 'bbpress' ) ) ); ?>
									
									</label>

								<?php endif; ?>

							</p>

							<?php do_action( 'bbp_theme_after_reply_form_subscription' ); ?>

						<?php endif; ?>

						<?php if ( bbp_is_reply_edit() ) : ?>

							<?php if ( current_user_can( 'moderate', bbp_get_reply_id() ) ) : ?>

								<?php do_action( 'bbp_theme_before_reply_form_reply_to' ); ?>

								<p class="spf-topic-form-group">

									<label class="spf-topic-form__label" for="bbp_reply_to"><?php esc_html_e( 'Reply to' ); ?></label>

									<?php bbp_reply_to_dropdown(); ?>

								</p>

								<?php do_action( 'bbp_theme_after_reply_form_reply_to' ); ?>

								<?php do_action( 'bbp_theme_before_reply_form_status' ); ?>

								<p class="spf-topic-form-group">

									<label class="spf-topic-form__label" for="bbp_reply_status"><?php esc_html_e( 'Reply Status' ); ?></label>

									<?php bbp_form_reply_status_dropdown( [ 'select_class' => 'spf-topic-form__select' ] ); ?>

								</p>

								<?php do_action( 'bbp_theme_after_reply_form_status' ); ?>

							<?php endif; ?>

						<?php endif; ?>

						<?php do_action( 'bbp_theme_before_reply_form_submit_wrapper' ); ?>

						<div class="spf-topic-form__submit-wrapper">

							<?php do_action( 'bbp_theme_before_reply_form_submit_button' ); ?>

							<?php bbp_cancel_reply_to_link(); ?>

							<button type="submit" id="bbp_reply_submit" name="bbp_reply_submit" class="spf-form-submit" title="<?php esc_attr_e( 'Publish reply' ); ?>">

								<?php esc_html_e( 'Reply', 'bbpress' ); ?>

							</button>

							<?php do_action( 'bbp_theme_after_reply_form_submit_button' ); ?>

						</div>

						<?php do_action( 'bbp_theme_after_reply_form_submit_wrapper' ); ?>

					</div>

					<?php bbp_reply_form_fields(); ?>

				</fieldset>

				<?php do_action( 'bbp_theme_after_reply_form' ); ?>

			</form>
		</div>

	<?php elseif ( bbp_is_topic_closed() ) : ?>

		<div id="no-reply-<?php bbp_topic_id(); ?>">

			<div class="spf-topic-form__notice-wrapper">

				<ul class="spf-notice is-warning">

					<li><?php printf( esc_html__( 'The topic &#8216;%s&#8217; is closed to new replies.', 'bbpress' ), bbp_get_topic_title() ); ?></li>
				
				</ul>

			</div>

		</div>

	<?php elseif ( bbp_is_forum_closed( bbp_get_topic_forum_id() ) ) : ?>

		<div id="no-reply-<?php bbp_topic_id(); ?>">

			<div class="spf-topic-form__notice-wrapper">

				<ul class="spf-notice is-warning">

					<li><?php printf( esc_html__( 'The forum &#8216;%s&#8217; is closed to new topics and replies.', 'bbpress' ), bbp_get_forum_title( bbp_get_topic_forum_id() ) ); ?></li>
				
				</ul>

			</div>

		</div>

	<?php else : ?>

		<div id="no-reply-<?php bbp_topic_id(); ?>">

			<?php if ( is_user_logged_in() ) : ?>

				<div class="spf-topic-form__notice-wrapper">

					<ul class="spf-notice is-warning">

						<li><?php esc_html_e( 'You cannot reply to this topic.', 'bbpress' ); ?></li>

					</ul>

				</div>

			<?php else : ?>

				<?php if ( ! bbp_is_reply_edit() ) : ?>

					<div class="spf-topic-form__form-title"><?php echo $reply_title; ?></div>

				<?php endif; ?>

				<div class="spf-topic-form-group">

					<?php
						$title = bbp_is_reply_edit() ? __( 'Log in or get started with WordPress.com to edit reply' ) : __( 'Log in or get started with WordPress.com to reply' );
						spf_render_login_signup_links( $title );
					?>

				</div>

			<?php endif; ?>

		</div>

	<?php endif; ?>

</div>
