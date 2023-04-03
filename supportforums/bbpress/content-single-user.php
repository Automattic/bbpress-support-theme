<?php

/**
 * Single User Content Part
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

?>

<div id="spf-forums">

	<?php do_action( 'bbp_template_notices' ); ?>

	<?php do_action( 'bbp_template_before_user_wrapper' ); ?>

	<?php spf_render_breadcrumb( [ 'current_text' => sprintf( esc_html__( "%s's Profile" ), bbp_get_displayed_user_field( 'display_name' ) ) ] ); ?>

	<div class="spf-content-single-user">

		<div class="spf-content-single-user__details">
			<?php bbp_get_template_part( 'user', 'details' ); ?>
		</div>

		<div class="spf-content-single-user__content">
			<?php
			switch ( true ) {
				case bbp_is_favorites():
					bbp_get_template_part( 'user', 'favorites' );
					break;

				case bbp_is_subscriptions():
					bbp_get_template_part( 'user', 'subscriptions' );
					break;

				case bbp_is_single_user_engagements():
					bbp_get_template_part( 'user', 'engagements' );
					break;

				case bbp_is_single_user_topics():
					bbp_get_template_part( 'user', 'topics-created' );
					break;

				case bbp_is_single_user_replies():
					bbp_get_template_part( 'user', 'replies-created' );
					break;

				case bbp_is_single_user_edit():
					bbp_get_template_part( 'form', 'user-edit' );
					break;

				case bbp_is_single_user_profile():
					bbp_get_template_part( 'user', 'profile' );
					break;
			}
			?>
		</div>
	</div>

	<?php do_action( 'bbp_template_after_user_wrapper' ); ?>

</div>
