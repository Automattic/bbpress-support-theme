<?php

/**
 * New/Edit Topic
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

?>

<div id="spf-forums" class="spf-topic-form">

	<?php if ( bbp_is_topic_edit() ) : ?>

		<?php spf_render_breadcrumb(); ?>

	<?php else : ?>

		<a href="<?php echo esc_url( spf_get_new_topic_back_url() ); ?>" class="spf-topic-form__back-link" title="<?php esc_attr_e( 'Back' ); ?>">
			
			<img src="<?php spf_render_images_path( 'icon-back.svg' ); ?>" alt="<?php esc_attr_e( 'Go back' ); ?>">
		
		</a>

	<?php endif; ?>

	<h1 class="spf-topic-form__title">

		<?php echo bbp_is_topic_edit() ? printf( esc_html__( 'Edit &ldquo;%s&rdquo;' ), bbp_get_topic_title() ) : esc_html__( 'Add a new topic' ); ?>

	</h1>

	<?php if ( ! bbp_is_topic_edit() ) : ?>

		<p class="spf-topic-form__subtitle">

			<?php esc_html_e( apply_filters( 'supportforums_form_topic_subtitle', '' ) ); ?>

		</p>

	<?php endif; ?>

	<?php if ( bbp_is_topic_edit() ) : ?>

		<?php bbp_get_template_part( 'alert', 'topic-lock' ); ?>

	<?php endif; ?>

	<?php if ( bbp_current_user_can_access_create_topic_form() ) : ?>

		<div id="new-topic-<?php bbp_topic_id(); ?>">

			<form id="new-post" name="new-post" method="post">

				<?php do_action( 'bbp_theme_before_topic_form' ); ?>

				<?php do_action( 'bbp_theme_before_topic_form_notices' ); ?>

				<?php if ( ! bbp_is_topic_edit() && bbp_is_forum_closed() ) : ?>

					<div class="spf-topic-form__notice-wrapper">

						<ul class="spf-notice is-warning">

							<li><?php esc_html_e( 'This forum is marked as closed to new topics, however your posting capabilities still allow you to create a topic.', 'bbpress' ); ?></li>

						</ul>

					</div>

				<?php endif; ?>

				<?php do_action( 'bbp_template_notices' ); ?>

				<div>

					<?php bbp_get_template_part( 'form', 'anonymous' ); ?>

					<?php do_action( 'bbp_theme_before_topic_form_title' ); ?>

					<p class="spf-topic-form-group">

						<label class="spf-topic-form__label" for="bbp_topic_title">

							<?php spf_render_en_as_lowercase( __( 'Topic Title' ) ); ?>

						</label>

						<input
								class="spf-text-input"
								type="text"
							<?php echo ! bbp_is_topic_edit() ? 'data-suggestions="#topic-suggestions"' : ''; ?>
								id="bbp_topic_title"
								value="<?php esc_attr(bbp_form_topic_title()); ?>"
								size="40"
								name="bbp_topic_title"
								maxlength="<?php esc_attr(bbp_title_max_length()); ?>"
						/>

					</p>

					<div id="topic-suggestions"></div>

					<?php do_action( 'bbp_theme_after_topic_form_title' ); ?>

					<?php do_action( 'bbp_theme_before_topic_form_content' ); ?>

					<?php bbp_the_content( array( 'context' => 'topic' ) ); ?>

					<?php do_action( 'bbp_theme_after_topic_form_content' ); ?>

					<?php if ( bbp_allow_topic_tags() && current_user_can( 'assign_topic_tags', bbp_get_topic_id() ) ) : ?>

						<?php do_action( 'bbp_theme_before_topic_form_tags' ); ?>

						<?php if ( defined( 'IS_WPCOM' ) && IS_WPCOM && is_automattician() ) : ?>

							<div class="spf-topic-form-group">

								<label class="spf-topic-form__label" for="spf-topic-tags"><?php esc_html_e( 'Tags' ); ?> <small>(<?php esc_html_e( 'optional' ); ?>)</small></label>

								<input type="text" value="<?php bbp_form_topic_tags(); ?>" name="bbp_topic_tags" id="bbp_topic_tags" hidden /><!-- Tags value comes here -->

								<div class="spf-topic-form__tag-wrapper" tabindex="-1"><!-- For user interaction -->

									<input id="spf-topic-tags" class="spf-topic-form__tag-input" type="text" />

								</div>

								<div class="spf-topic-form__tag-hint"><?php esc_html_e( 'These can help our team solve your issue faster' ); ?></div>

							</div>

						<?php endif; ?>

						<?php do_action( 'bbp_theme_after_topic_form_tags' ); ?>

					<?php endif; ?>

					<?php if ( ! bbp_is_single_forum() ) : ?>

						<?php do_action( 'bbp_theme_before_topic_form_forum' ); ?>

						<?php spf_render_forums_dropdown( 'spf-topic-form-group' ); ?>

						<?php do_action( 'bbp_theme_after_topic_form_forum' ); ?>

					<?php endif; ?>

					<?php if ( current_user_can( 'moderate', bbp_get_topic_id() ) ) : ?>

						<?php do_action( 'bbp_theme_before_topic_form_type' ); ?>

						<p class="spf-topic-form-group">

							<label class="spf-topic-form__label" for="bbp_stick_topic_select" title="<?php esc_attr_e( 'Select topic type' ); ?>">

								<?php spf_render_en_as_lowercase( __( 'Topic Type' ) ); ?>

							</label>

							<?php bbp_form_topic_type_dropdown( [ 'select_class' => 'spf-topic-form__select' ] ); ?>

						</p>

						<?php do_action( 'bbp_theme_after_topic_form_type' ); ?>

						<?php do_action( 'bbp_theme_before_topic_form_status' ); ?>

						<p class="spf-topic-form-group">

							<label class="spf-topic-form__label" for="bbp_topic_status_select">

								<?php spf_render_en_as_lowercase( __( 'Topic Status' ) ); ?>

							</label>

							<?php bbp_form_topic_status_dropdown( [ 'select_class' => 'spf-topic-form__select' ] ); ?>

						</p>

						<?php do_action( 'bbp_theme_after_topic_form_status' ); ?>

					<?php endif; ?>

					<?php if ( bbp_is_subscriptions_active() && ! bbp_is_anonymous() && ( ! bbp_is_topic_edit() || ( bbp_is_topic_edit() && ! bbp_is_topic_anonymous() ) ) ) : ?>

						<?php do_action( 'bbp_theme_before_topic_form_subscriptions' ); ?>

						<p class="spf-topic-form-group spf-checkbox-input">

							<input name="bbp_topic_subscription" id="bbp_topic_subscription" type="checkbox" value="bbp_subscribe" <?php bbp_form_topic_subscribed(); ?> />

							<?php if ( bbp_is_topic_edit() && ( bbp_get_topic_author_id() !== bbp_get_current_user_id() ) ) : ?>

								<label for="bbp_topic_subscription"><?php esc_html_e( 'Notify the author of follow-up replies via email.', 'bbpress' ); ?></label>

							<?php else : ?>

								<label for="bbp_topic_subscription">

									<?php echo esc_html( apply_filters( 'bbp_subscribe_user_text', __( 'Notify me of follow-up replies via email.', 'bbpress' ) ) ); ?>

								</label>

							<?php endif; ?>

						</p>

						<?php do_action( 'bbp_theme_after_topic_form_subscriptions' ); ?>

					<?php endif; ?>

					<?php do_action( 'bbp_theme_before_topic_form_submit_wrapper' ); ?>

					<div class="spf-topic-form__submit-wrapper">

						<?php do_action( 'bbp_theme_before_topic_form_submit_button' ); ?>

						<button type="submit" id="bbp_topic_submit" name="bbp_topic_submit" class="spf-form-submit" title="<?php esc_attr_e( 'Publish' ); ?>">
							
							<?php esc_html_e( 'Publish', 'bbpress' ); ?>

						</button>

						<?php do_action( 'bbp_theme_after_topic_form_submit_button' ); ?>

					</div>

					<?php do_action( 'bbp_theme_after_topic_form_submit_wrapper' ); ?>

				</div>

				<?php bbp_topic_form_fields(); ?>

				<?php do_action( 'bbp_theme_after_topic_form' ); ?>

			</form>

		</div>

	<?php elseif ( bbp_is_forum_closed() ) : ?>

		<div id="forum-closed-<?php bbp_forum_id(); ?>">

			<div class="spf-topic-form__notice-wrapper">

				<ul class="spf-notice is-warning">

					<li><?php printf( esc_html__( 'The forum &#8216;%s&#8217; is closed to new topics and replies.', 'bbpress' ), bbp_get_forum_title() ); ?></li>

				</ul>

			</div>

		</div>

	<?php else : ?>

		<div id="no-topic-<?php bbp_forum_id(); ?>">

			<?php if ( is_user_logged_in() ) : ?>

				<div class="spf-topic-form__notice-wrapper">

					<ul class="spf-notice is-warning">

						<li><?php esc_html_e( 'You cannot create new topics.', 'bbpress' ); ?></li>

					</ul>

				</div>

			<?php else : ?>

				<div class="spf-topic-form-group">

					<?php spf_render_login_signup_links( __( 'Log in or get started with WordPress.com to create topics' ) ); ?>

				</div>

			<?php endif; ?>

		</div>

	<?php endif; ?>

</div>
