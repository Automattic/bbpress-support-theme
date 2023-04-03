<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 */
?>

<?php do_action( 'h5_after_loop' ); ?>
</div><!-- #main -->
</div><!-- #page .hfeed .site -->

<?php
if ( supportforums_should_have_resources_section() ) :
$resource_items = [
	[
		'icon' => 'icon-help.svg',
		'title' => __( 'Support site'),
		'description' => __( 'The best guides and tutorials for everything WordPress.com are now at your fingertips.' ),
		'link_text' => __( 'Learn More' ),
		'link_url' => 'https://wordpress.com/'. get_locale() . '/support',
	],
	[
		'icon'        => 'icon-desktop.svg',
		'title'       => __( 'Webinars' ),
		'description' => __( 'Come learn a new skill and have your questions answered live by WordPress experts.' ),
		'link_text'   => __( 'Join for Free' ),
		'link_url'    => 'https://wordpress.com/' . get_locale() . '/webinars',
	],
	[
		'icon' => 'icon-video.svg',
		'title' => __( 'YouTube Channel'),
		'description' => __( 'Our YouTube channel has dozens of educational videos to help get your site up and running.' ),
		'link_text' => __( 'Join for Free' ),
		'link_url' => 'https://www.youtube.com/wordpress',
		'is_external' => true,
	],
];
?>

<section class="spf-resources-section">

	<div class="spf-resources-section__content-wrapper">

		<h1 class="spf-resources-section__title"><?php esc_html_e( 'These can help you too:' ); ?></h1>

		<div class="spf-resources-section__resource-items">

			<?php foreach ( $resource_items as $item ) : ?>

				<div class="spf-resources-section__resource-item">

					<div class="spf-resources-section__resource-icon-wrapper">

						<img src="<?php spf_render_images_path( $item['icon'] ); ?>" alt="<?php echo esc_attr( $item['title'] ); ?>">

					</div>

					<h2 class="spf-resources-section__resource-title"><?php echo esc_html( $item['title'] ); ?></h2>

					<p class="spf-resources-section__resource-description"><?php echo esc_html( $item['description'] ); ?></p>

					<a class="spf-resources-section__resource-link" href="<?php echo esc_url( $item['link_url'] ); ?>" target="<?php echo isset( $item['is_external'] ) && $item['is_external'] === true ? '_blank' : '_self' ?>">

						<?php echo esc_html( $item['link_text'] ); ?>

						<?php spf_render_svg( 'icon-link-arrow.svg' ); ?>

					</a>

				</div>

			<?php endforeach; ?>

		</div>

	</div>

</section>

<?php endif; ?><!-- Resources section -->

<div>

	<footer>
		<?php do_action( 'supportforums_footer_nav' ); ?>
	</footer>

	<?php
	$supportforums_page_path = supportforums_get_url_path();
	$supportforums_page_locale = supportforums_get_subdomain_lang();
	?>
	<script>
		// Run code after the DOM is loaded
		jQuery( document ).ready( function($) {
			// Fire off Tracks event
			window._tkq = window._tkq || [];
			window._tkq.push( [
				'recordEvent',
				'wpcom_forums_page_view',
				{
					path: '<?php echo esc_js( $supportforums_page_path ); ?>',
					page_locale: '<?php echo esc_js( $supportforums_page_locale ); ?>',
				}
			] );
		} );

		jQuery( '#banner a' ).on( 'click', function() {
			// Fire off Tracks event
			window._tkq = window._tkq || [];
			window._tkq.push( [
				'recordEvent',
				'wpcom_forums_banner_click',
				{
					path: '<?php echo esc_js( $supportforums_page_path ); ?>',
					page_locale: '<?php echo esc_js( $supportforums_page_locale ); ?>',
				}
			] );
		} );
	</script>

	<?php wp_footer(); ?>
</div>

</body>
</html>
