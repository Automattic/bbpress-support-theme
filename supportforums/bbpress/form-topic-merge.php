<?php

/**
 * Merge Topic
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

		<?php printf( esc_html__( 'Merge topic "%s"' ), bbp_get_topic_title() ); ?>

	</h1>

	<?php if ( is_user_logged_in() && current_user_can( 'edit_topic', bbp_get_topic_id() ) ) : ?>

		<ul class="spf-topic-manage__info">

			<li><?php esc_html_e( 'Select the topic to merge this one into. The destination topic will remain the lead topic, and this one will change into a reply.', 'bbpress' ); ?></li>

			<li><?php esc_html_e( 'Replies to both topics are merged chronologically, ordered by the time and date they were published. Topics may be updated to a 1 second difference to maintain chronological order based on the merge direction.', 'bbpress' ); ?></li>

			<li><?php esc_html_e( 'This process cannot be undone.', 'bbpress' ); ?></li>

		</ul>

		<div id="merge-topic-<?php bbp_topic_id(); ?>">

			<form id="merge_topic" name="merge_topic" method="post">

				<div>

					<div class="spf-topic-manage-group">

						<?php if ( bbp_has_topics( array( 'show_stickies' => false, 'post_parent' => bbp_get_topic_forum_id( bbp_get_topic_id() ), 'post__not_in' => array( bbp_get_topic_id() ) ) ) ) : ?>

							<label for="bbp_destination_topic"><?php esc_html_e( 'Destination topic' ); ?></label>

							<?php do_action( 'supportforums_merge_topic_dropdown', bbp_get_topic_id() ); ?>

						<?php else : ?>

							<label><?php esc_html_e( 'There are no other topics in this forum to merge with.', 'bbpress' ); ?></label>

						<?php endif; ?>

					</div>

					<fieldset class="spf-topic-manage-group">

						<legend><?php esc_html_e( 'Merge options' ); ?></legend>

						<?php if ( bbp_is_subscriptions_active() ) : ?>

							<div class="spf-checkbox-input">

								<input name="bbp_topic_subscribers" id="bbp_topic_subscribers" type="checkbox" value="1" checked="checked" />

								<label for="bbp_topic_subscribers"><?php esc_html_e( 'Merge subscribers' ); ?></label>

							</div>

						<?php endif; ?>

						<div class="spf-checkbox-input">

							<input name="bbp_topic_favoriters" id="bbp_topic_favoriters" type="checkbox" value="1" checked="checked" />

							<label for="bbp_topic_favoriters"><?php esc_html_e( 'Merge favoriters' ); ?></label>

						</div>

						<?php if ( bbp_allow_topic_tags() ) : ?>

							<div class="spf-checkbox-input">

								<input name="bbp_topic_tags" id="bbp_topic_tags" type="checkbox" value="1" checked="checked" />

								<label for="bbp_topic_tags"><?php esc_html_e( 'Merge tags' ); ?></label>
							
							</div>

						<?php endif; ?>

					</fieldset>

					<div class="spf-topic-manage__submit-wrapper">

						<button type="submit" id="bbp_merge_topic_submit" name="bbp_merge_topic_submit" class="spf-form-submit" title="<?php esc_attr_e( 'Merge topics' ); ?>"><?php esc_html_e( 'Merge topics' ); ?></button>

					</div>

				</div>

				<?php bbp_merge_topic_form_fields(); ?>

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
