<div class="spf-user-name">
	<div class="spf-user-name__wrapper">
		<h1 class="spf-user-name__name"><?php echo esc_html( bbp_get_displayed_user_field( 'user_nicename' ) ); ?></h1>
		<?php if ( supportforums_is_super_admin() ) : ?>
			<span class="spf-badge is-blue"><?php esc_html_e( 'Super Admin' ); ?></span>
		<?php endif; ?>
	</div>
</div>
