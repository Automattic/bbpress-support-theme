<?php

function supportforums_local_enqueue_scripts() {
	wp_enqueue_style( 'supportforums-local', get_stylesheet_directory_uri() . '/style.css', [], '20230321' );
}

add_action( 'wp_enqueue_scripts', 'supportforums_local_enqueue_scripts' );

add_filter( 'supportforums_should_have_resources_section', '__return_false', 11 );

function support_forums_not_single_forum_subtitle() {
	return __( 'Get Help with stuff on our public forum.' );
}

add_filter( 'support_forums_not_single_forum_subtitle', 'support_forums_not_single_forum_subtitle' );

function supportforums_header_nav() {
	get_template_part( 'header-nav' );
}

add_action( 'supportforums_header_nav', 'supportforums_header_nav' );

function supportforums_footer_nav() {
	get_template_part( 'footer-nav/footer-nav' );
}

add_action( 'supportforums_footer_nav', 'supportforums_footer_nav' );

function supportforums_before_loop() {
	if ( ! bbp_is_search() && ! bbp_is_topic_new() ) {
		if ( supportforums_should_have_search() ) {
			?>
			<div class="spf-search">
				<?php if ( bbp_is_single_forum() ): ?>
					<h1 class="spf-header__title"><?php esc_html_e( sprintf( "%s subforum", bbp_get_forum_title() ) ); ?></h1>
					<p class="spf-header__subtitle"><?php echo bbp_get_forum_content(); ?></p>
				<?php else: ?>
					<h1 class="spf-header__title"><?php esc_html_e( apply_filters( 'support_forums_not_single_forum_title', bbp_get_forum_title() ) ); ?></h1>
					<p class="spf-header__subtitle"><?php esc_html_e( apply_filters( 'support_forums_not_single_forum_subtitle', bbp_get_forum_content() ) ); ?></p>
				<?php endif; ?>
				<?php echo spf_get_search_form(); ?>
			</div>
			<?php
		}
	}
}
add_action( 'h5_before_loop', 'supportforums_before_loop' );

function supportforums_widgets_init() {
	register_sidebar(
		[
			'name'          => __( 'Sidebar 2022', 'support-forums' ),
			'id'            => 'sidebar-2022',
			'description'   => __( 'Widgets in this area will be shown on the right-hand side.', 'support-forums' ),
			'before_widget' => '<aside id="%1$s" class="spf-admin-tools__widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h1 class="spf-admin-tools__widget-title">',
			'after_title'   => '</h1>',
		]
	);
}

add_action( 'widgets_init', 'supportforums_widgets_init' );

function supportforums_register_menus() {
	register_nav_menu( 'supportforums_header_nav', __( 'Header Navigation' ) );
	register_nav_menu( 'supportforums_footer_nav', __( 'Footer Navigation' ) );
}

add_action( 'after_setup_theme', 'supportforums_register_menus' );

add_filter( 'supportforums_render_forums_dropdown_always', '__return_true' );
