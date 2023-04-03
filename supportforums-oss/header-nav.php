<?php
/**
 * Header Navigation template part for Support forums.
 */

// Get the current logged-in user profile URL and user data
$profile_url = '';
$user = '';
if ( is_user_logged_in() && function_exists( 'bbp_get_user_profile_url' ) ) {
	$user_id = get_current_user_id();
	$user = get_userdata( $user_id );
	$profile_url = bbp_get_user_profile_url( $user_id );
}
?>
<div id="lpc-header-nav" class="lpc lpc-header-nav">
    <div class="x-root lpc-header-nav-wrapper">
        <div class="lpc-header-nav-container">
            <!-- Nav bar starts here. -->
            <div class="masterbar-menu">
                <div class="masterbar">
                    <nav class="x-nav" aria-label="<?php esc_attr_e( 'Header Navigation' ); ?>">
                        <ul class="x-nav-list x-nav-list--left">
	                        <li class="x-nav-item">
		                        <a role="menuitem" class="x-nav-link x-nav-link--logo x-link" href="<?php echo get_home_url(); ?>">
			                        <img src="<?php spf_render_images_path( 'logo-generic.svg' ); ?>" alt="<?php esc_attr_e( 'WP Forums logo' ); ?>" />
		                        </a>
	                        </li>
	                        <?php
								wp_nav_menu(
									[
										'container' => false,
										'menu_class' => 'spf-header-nav',
										'theme_location' => 'supportforums_header_nav'
									]
								);
							?>
                        </ul>
                        <ul class="x-nav-list x-nav-list--right">
							<?php if ( $profile_url && $user ) { ?>
                                <li class="x-nav-item x-nav-item--wide logged-in">
                                    <a role="menuitem" class="x-nav-link x-link" href="<?php echo esc_url( $profile_url ); ?>" title="<?php esc_attr_e( 'Visit Profile' ); ?>">
										<?php /* translators: user display name */ ?>
										<?php printf( esc_html__( 'Welcome, %s' ), $user->display_name ); ?>
                                    </a>
                                </li>
							<?php } ?>
                            <li class="x-nav-item x-nav-item--wide login-link">
                                <a role="menuitem" class="x-nav-link x-link" href="<?php echo esc_url( apply_filters( 'support_forums_login_url',  wp_login_url( get_permalink() ) ) ); ?>" title="<?php esc_attr_e( 'Log In' ); ?>">
									<?php esc_html_e( 'Log In' ); ?>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- Nav bar ends here. -->
        </div>
    </div>
</div>
