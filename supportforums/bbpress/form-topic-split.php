<?php

/**
 * Split Topic
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

?>

<div id="spf-forums" class="spf-topic-manage">

<?php spf_render_breadcrumb(); ?>

	<h1 class="spf-topic-manage__title">

		<?php printf( esc_html__( 'Split reply "%s"' ), bbp_get_topic_title() ); ?>

	</h1>

	<?php if ( is_user_logged_in() && current_user_can( 'edit_topic', bbp_get_topic_id() ) ) : ?>

		<ul class="spf-topic-manage__info">

			<li><?php esc_html_e( 'When you split a topic, you are slicing it in half starting with the reply you just selected. Choose to use that reply as a new topic with a new title, or merge those replies into an existing topic.', 'bbpress' ); ?></li>

			<li><?php esc_html_e( 'If you use the existing topic option, replies within both topics will be merged chronologically. The order of the merged replies is based on the time and date they were posted.', 'bbpress' ); ?></li>

			<li><?php esc_html_e( 'This process cannot be undone.', 'bbpress' ); ?></li>

		</ul>

		<div id="split-topic-<?php bbp_topic_id(); ?>">

			<form id="split_topic" name="split_topic" method="post">

				<div>

					<div class="spf-topic-manage-group">

						<input name="bbp_topic_split_option" id="bbp_topic_split_option_reply" type="radio" checked="checked" value="reply" style="display: none;" />
							
						<label for="bbp_topic_split_option_reply"><?php printf( esc_html__( 'New topic in %s titled' ), bbp_get_forum_title( bbp_get_topic_forum_id( bbp_get_topic_id() ) ) ); ?></label>
							
						<input type="text" id="bbp_topic_split_destination_title" class="spf-text-input" value="<?php printf( esc_html__( 'Split: %s', 'bbpress' ), bbp_get_topic_title() ); ?>" name="bbp_topic_split_destination_title" />
					
					</div>

					<fieldset class="spf-topic-manage-group">
						
						<legend><?php esc_html_e( 'Topic Extras', 'bbpress' ); ?></legend>

						<?php if ( bbp_is_subscriptions_active() ) : ?>

							<div class="spf-checkbox-input">

								<input name="bbp_topic_subscribers" id="bbp_topic_subscribers" type="checkbox" value="1" checked="checked" />
							
								<label for="bbp_topic_subscribers"><?php esc_html_e( 'Copy subscribers to the new topic', 'bbpress' ); ?></label>

							</div>

						<?php endif; ?>

						<div class="spf-checkbox-input">

							<input name="bbp_topic_favoriters" id="bbp_topic_favoriters" type="checkbox" value="1" checked="checked" />
						
							<label for="bbp_topic_favoriters"><?php esc_html_e( 'Copy favoriters to the new topic', 'bbpress' ); ?></label>

						</div>

						<?php if ( bbp_allow_topic_tags() ) : ?>

							<div class="spf-checkbox-input">

								<input name="bbp_topic_tags" id="bbp_topic_tags" type="checkbox" value="1" checked="checked" />
							
								<label for="bbp_topic_tags"><?php esc_html_e( 'Copy topic tags to the new topic', 'bbpress' ); ?></label>

							</div>

						<?php endif; ?>
					
					</fieldset>

					<div class="spf-topic-manage__submit-wrapper">

						<button type="submit" id="bbp_merge_topic_submit" name="bbp_merge_topic_submit" class="spf-form-submit" title="<?php esc_attr_e( 'Split topic' ); ?>"><?php esc_html_e( 'Submit', 'bbpress' ); ?></button>
					
					</div>

				</div>

				<?php bbp_split_topic_form_fields(); ?>

			</form>

		</div>

	<?php else : ?>

		<div id="no-topic-<?php bbp_topic_id(); ?>">

			<?php if ( is_user_logged_in() ) : ?>

				<div class="spf-topic-form__notice-wrapper">

					<ul class="spf-notice is-warning">

						<li><?php esc_html_e( 'You do not have permission to edit this topic.', 'bbpress' ); ?></li>

					</ul>

				</div>

			<?php else : ?>

				<div class="spf-topic-manage-group">

					<?php spf_render_login_signup_links( __( 'Log in or get started with WordPress.com to edit this topic' ) ); ?>

				</div>

			<?php endif; ?>

		</div>

	<?php endif; ?>

</div>
