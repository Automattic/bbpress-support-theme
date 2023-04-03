<?php
/**
 * Add a custom 404 page.
 */

get_header(); ?>
<div class="spf-404">
	<h1 class="spf-header__title"><?php esc_html_e( 'Oh, blimey!' ); ?></h1>
	<p class="spf-header__subtitle"><?php esc_html_e( 'The page you\'ve landed on has no content.' ); ?> <br> <?php esc_html_e( 'User our search or go back to the' ); ?> <a class="spf-header__home-link" href="<?php echo esc_url( get_home_url() ); ?>"><?php esc_html_e( 'forums homepage' ); ?></a> </p>
	<?php echo spf_get_search_form(); ?>
</div>
<?php get_footer(); ?>
