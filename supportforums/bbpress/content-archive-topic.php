<?php

/**
 * Archive Topic Content Part
 * 
 * Is used by the home page, e.g. https://wordpress.com/forums/
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

?>

<?php do_action( 'bbp_template_before_topic_tag_description' ); ?>

<?php if ( bbp_is_topic_tag() ) : ?>

	<?php bbp_topic_tag_description( array(
		'before' => '<div class="spf-notice-wrapper"><div class="spf-notice"><ul><li>',
		'after'  => '</li></ul></div></div>'
	) ); ?>

<?php endif; ?>

<?php do_action( 'bbp_template_after_topic_tag_description' ); ?>

<?php bbp_get_template_part( 'header', 'navigation' ); ?>

<div id="spf-forums" class="bbpress-wrapper">

	<?php do_action( 'bbp_template_before_topics_index' ); ?>

	<?php if ( bbp_has_topics() ) : ?>

		<?php bbp_get_template_part( 'loop', 'topics' ); ?>

		<?php bbp_get_template_part( 'pagination', 'topics' ); ?>

	<?php else : ?>

		<?php bbp_get_template_part( 'feedback',   'no-topics' ); ?>

	<?php endif; ?>

	<?php do_action( 'bbp_template_after_topics_index' ); ?>

</div>
