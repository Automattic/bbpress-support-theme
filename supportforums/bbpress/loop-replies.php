<?php

/**
 * Replies Loop
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

do_action( 'bbp_template_before_replies_loop' ); ?>

	<ul id="topic-<?php bbp_topic_id(); ?>-replies" class="spf-replies">
		<?php $has_topic_badge = bbp_is_topic_trash() || bbp_is_topic_spam() || bbp_is_topic_pending(); ?>

		<?php while ( bbp_replies() ) : bbp_the_reply(); ?>
			<?php $has_reply_badge = bbp_is_reply_trash() || bbp_is_reply_spam() || bbp_is_reply_pending() || spf_is_accepted_answer(); ?>

			<li <?php echo ( $has_topic_badge || $has_reply_badge ) ? 'class="has-badge"' : ''; ?> >

				<?php bbp_get_template_part( 'loop', 'single-reply' ); ?>

			</li><!-- .spf-reply -->

		<?php endwhile; ?>

	</ul><!-- #topic-<?php bbp_topic_id(); ?>-replies -->

<?php do_action( 'bbp_template_after_replies_loop' );
