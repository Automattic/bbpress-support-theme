<?php
/**
 * Main template for displaying all bbPress pages (except homepage which is a static WP page using the
 * `page-2022-theme-fullwidth.php` template.
 *
 * This template has a sidebar.
 */

$should_page_have_sidebar = supportforums_should_page_have_sidebar( get_the_ID() );

$spf_content_classes = "spf-content";

if ( $should_page_have_sidebar ) {
	$spf_content_classes .= ' has-sidebar';
}

if ( post_password_required( get_the_ID() ) ) {
	$spf_content_classes .= ' is-password-protected';
}

get_header(); ?>

<div class="spf-content-wrapper">
	<div class="<?php echo $spf_content_classes; ?>" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', 'page' ); ?>

		<?php endwhile; ?>
	</div>


	<?php if ( $should_page_have_sidebar ): ?>
		<div class="spf-sidebar" role="complementary">
			<?php get_sidebar(); ?>
		</div>
		<?php if ( spf_has_additional_sidebar() ): ?>
			<div id="additional" class="spf-sidebar-additional" role="complementary">
				<?php get_sidebar( 'additional' ); ?>
			</div>
		<?php endif; ?>
	<?php endif; ?>

</div>

<?php get_footer(); ?>
