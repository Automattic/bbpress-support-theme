<?php

/**
 * Pagination for pages of replies (when viewing a topic)
 *
 * WPCOM: Remove pagination count.
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

do_action( 'bbp_template_before_pagination_loop' ); ?>

	<div class="spf-pagination">
		<div class="spf-pagination-links"><?php bbp_topic_pagination_links(); ?></div>
	</div>

<?php do_action( 'bbp_template_after_pagination_loop' );
