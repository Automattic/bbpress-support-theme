<?php

/**
 * Single Topic Content Part
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

?>

<div class="spf-topic">

	<?php spf_render_breadcrumb(); ?>

	<!-- Temporarily used for also viewing -->
	<ul class="spf-notices">
		<li id="also-viewing-placeholder"></li>
	</ul>

	<?php do_action( 'bbp_template_before_single_topic' ); ?>

	<h1 class="spf-topic__title">
		<?php if ( bbp_is_topic_closed() ) : ?>
			<span class="spf-topic-icons-closed" data-tooltip="<?php esc_attr_e( 'Closed' ); ?>"></span>
		<?php endif; ?>
		<?php if ( bbp_is_topic_sticky() ) : ?>
			<span class="spf-topic-icons-pinned" data-tooltip="<?php esc_attr_e( 'Pinned' ); ?>"></span>
		<?php endif; ?>
		<?php bbp_topic_title(); ?>
	</h1>

	<?php if ( post_password_required() ) : ?>

		<?php bbp_get_template_part( 'form', 'protected' ); ?>

	<?php else : ?>

		<?php if ( bbp_has_replies() ) : ?>

			<?php bbp_get_template_part( 'loop',       'replies' ); ?>

			<?php bbp_get_template_part( 'pagination', 'replies' ); ?>

		<?php endif; ?>

		<?php bbp_get_template_part( 'form', 'reply' ); ?>

	<?php endif; ?>

	<?php do_action( 'bbp_template_after_single_topic' ); ?>

</div>

