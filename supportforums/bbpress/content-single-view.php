<?php

/**
 * Single View Content Part
 * 
 * Is used by a single view page, e.g. https://wordpress.com/forums/view/support-forum-yes/
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

?>

<?php bbp_get_template_part( 'header', 'navigation' ); ?>

<div id="spf-forums" class="bbpress-wrapper">

	<?php bbp_set_query_name( bbp_get_view_rewrite_id() ); ?>

	<?php if ( bbp_view_query() ) : ?>

		<?php bbp_get_template_part( 'loop', 'topics' ); ?>

		<?php bbp_get_template_part( 'pagination', 'topics' ); ?>

	<?php else : ?>

		<?php bbp_get_template_part( 'feedback',   'no-topics' ); ?>

	<?php endif; ?>

	<?php bbp_reset_query_name(); ?>

</div>
