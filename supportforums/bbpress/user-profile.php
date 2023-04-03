<?php

/**
 * User Profile
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

$time   = strtotime( bbp_get_displayed_user_field( 'user_registered' ) );
$format = get_option( 'date_format' );
/* translators: %s: user website link */
$website_label = __( 'Website: %s' );

do_action( 'bbp_template_before_user_profile' );
?>
	<div class="spf-user-page">
		<?php bbp_get_template_part( 'user', 'name' ); ?>

		<div class="spf-user-section is-user-profile">
			<div class="spf-user-section__content">
				<table class="spf-user-profile__table">
					<tr>
						<th><?php esc_html_e( 'Forum role' ); ?></th>
						<th><?php esc_html_e( 'Member since' ); ?></th>
						<th><?php esc_html_e( 'Last activity' ); ?></th>
						<th><?php esc_html_e( 'Topics created' ); ?></th>
						<th><?php esc_html_e( 'Replies created' ); ?></th>
					</tr>
					<tr>
						<td><?php echo esc_html( bbp_get_user_display_role() ); ?></td>
						<td><?php printf( '<p>' . esc_html( date_i18n( $format, $time ) ) . ' (' . esc_html( human_time_diff( $time ) ) . ")</p>\n" ); ?> </td>
						<td><?php echo bbp_get_user_last_posted() ? esc_html( bbp_get_time_since( bbp_get_user_last_posted(), false, true ) ) : '-'; ?> </td>
						<td><?php echo esc_html( bbp_get_user_topic_count() ); ?></td>
						<td><?php echo esc_html( bbp_get_user_reply_count() ); ?></td>
					</tr>
				</table>

				<dl class="spf-user-profile__list-mobile">
					<dt><?php esc_html_e( 'Forum role' ); ?></dt>
					<dd><?php echo esc_html( bbp_get_user_display_role() ); ?></dd>
					<dt><?php esc_html_e( 'Member since' ); ?></dt>
					<dd><?php printf( '<p>' . esc_html( date_i18n( $format, $time ) ) . ' (' . esc_html( human_time_diff( $time ) ) . ")</p>\n" ); ?></dd>
					<dt><?php esc_html_e( 'Last activity' ); ?></dt>
					<dd><?php echo bbp_get_user_last_posted() ? esc_html( bbp_get_time_since( bbp_get_user_last_posted(), false, true ) ) : '-'; ?></dd>
					<dt><?php esc_html_e( 'Topics created' ); ?></dt>
					<dd><?php echo esc_html( bbp_get_user_topic_count() ); ?></dd>
					<dt><?php esc_html_e( 'Replies created' ); ?></dt>
					<dd><?php echo esc_html( bbp_get_user_reply_count() ); ?></dd>
				</dl>
			</div>
		</div>

		<?php if ( bbp_get_displayed_user_field( 'description' ) ) : ?>
			<div class="spf-user-profile__section">
				<div class="spf-user-profile__section-title"><?php esc_html_e( 'Bio' ); ?></div>
				<div class="spf-user-profile__section-content">
					<span class="spf-user-profile__section-bio"><?php echo esc_html( bbp_rel_nofollow( bbp_get_displayed_user_field( 'description' ) ) ); ?></span>
					<?php if ( bbp_get_displayed_user_field( 'user_url' ) ) : ?>
						<?php
						$bbp_user_url      = bbp_get_displayed_user_field( 'user_url' );
						$bbp_link_url      = bbp_make_clickable( $bbp_user_url );
						$no_follow_url = bbp_rel_nofollow( $bbp_link_url );
						?>
						<span class="spf-user-profile__section-website"><?php printf( esc_html( $website_label ), esc_html( $no_follow_url ) ); ?></span>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>


	</div>
<?php
do_action( 'bbp_template_after_user_profile' );
