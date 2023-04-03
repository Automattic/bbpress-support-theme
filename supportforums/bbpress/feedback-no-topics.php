<?php

/**
 * No Topics Feedback Part
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;
?>

<div class="spf-empty-no-results">

	<?php if ( bbp_is_single_view() ) : ?>

		<h3 class="spf-empty-no-results__title"><?php esc_html_e( 'Oh, bother!' ); ?></h3>
		<p class="spf-empty-no-results__subtitle"><?php esc_html_e( sprintf( 'Sorry, but there are no "%s" topics in this forum.', esc_html__( bbp_get_view_title() ) ) ); ?></p>
		<a class="spf-empty-no-results__link"
		   href="<?php echo esc_url( get_home_url() ); ?>"><?php esc_html_e( 'Show all topics' ); ?></a>

	<?php else : ?>

		<h3 class="spf-empty-no-results__title"><?php esc_html_e( 'Oh, bother!' ); ?></h3>
		<p class="spf-empty-no-results__subtitle"><?php esc_html_e( 'Sorry, but there are no topics in this forum.' ); ?></p>
		<a class="spf-empty-no-results__link"
		   href="<?php echo esc_url( spf_get_new_topic_url() ); ?>"><?php esc_html_e( 'Create the first topic' ); ?></a>

	<?php endif; ?>

</div>
