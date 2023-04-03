<?php

/**
 * Single Forum Content Part
 * 
 * Is used by a sub-forum page, e.g. https://wordpress.com/forums/forum/support/
 *
 * WPCOM: Removed subscription link.
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

?>

<?php bbp_get_template_part( 'header', 'navigation' ); ?>

<div id="spf-forums" class="bbpress-wrapper">

	<?php do_action( 'bbp_template_before_single_forum' ); ?>

	<?php if ( post_password_required() ) : ?>

		<?php bbp_get_template_part( 'form', 'protected' ); ?>

	<?php else : ?>

		<?php if ( ! bbp_is_forum_category() && bbp_has_topics() ) : ?>

			<?php bbp_get_template_part( 'loop',       'topics'    ); ?>

			<?php bbp_get_template_part( 'pagination', 'topics'    ); ?>

		<?php elseif ( ! bbp_is_forum_category() ) : ?>

			<?php bbp_get_template_part( 'feedback',   'no-topics' ); ?>

		<?php endif; ?>

	<?php endif; ?>

	<?php do_action( 'bbp_template_after_single_forum' ); ?>

</div>
