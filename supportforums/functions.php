<?php
require_once( __DIR__ . '/template-functions.php' );

const SUPPORTFORUMS_CACHE_GROUP = 'supportforums';
const SUPPORTFORUMS_SUBFORUM_PERMALINKS_CACHE_KEY = 'supportforums_subforum_permalinks';

function theme_setup() {
	add_theme_support( 'responsive-embeds' );

	supportforums_allow_topic_rest_api();
}

// This allows topic tags to be accessed through the REST API, enabling use of the tag autocompleter. We need to do this manually
// as the default taxonomies are already registered by this point and new taxonomies won't be registered afterwards
function supportforums_allow_topic_rest_api() {
	if ( ! function_exists( 'bbp_get_topic_tag_tax_id' ) ) {
		// Running somewhere else outside of the forum - a job maybe
		return;
	}

	// Get the topic tag taxonomy
	$topics = get_taxonomy( bbp_get_topic_tag_tax_id() );

	if ( $topics ) {
		$controller = $topics->get_rest_controller();

		if ( $controller ) {
			$controller->register_routes();
		}
	}
}

add_action( 'after_setup_theme','theme_setup' );

// Jetpack inserts a load of fonts into Gutenberg, which we don't want
remove_action( 'after_setup_theme', 'jetpack_add_google_fonts_provider' );

/**
 * Is the new topic page
 *
 * @return boolean
 */
function supportforums_is_new_topic_page(): bool {
	return ( is_front_page() || bbp_is_single_forum() ) && isset( $_GET['new'] ) && $_GET['new'] == 1;
}

/**
 * Is the spam page
 *
 * @return boolean
 */
function supportforums_is_spam_page() {
	return bbp_is_single_view() && 'spam' === bbp_get_view_id();
}

/**
 * Is the trash page
 *
 * @return boolean
 */
function supportforums_is_trash_page() {
	return bbp_is_single_view() && 'trash' === bbp_get_view_id();
}

/**
 * Getting the URL of the back key
 *
 * @return string
 */
function get_back_url() {
	$prev_url = wp_get_referer();
	$is_wp_com = str_ends_with( parse_url( $prev_url )['host'], 'wordpress.com' );

	// If the user comes from a WP.com site (e.g. HE support, somewhere of the forum, etc.)
	// redirect the user back to the site, otherwise go to the home-page of the forum
	return $is_wp_com ? $prev_url : get_home_url();
}

function supportforums_disable_gravatar_hovercards() {
	if ( ! defined( 'GRAVATAR_HOVERCARDS__DISABLE' ) ) {
		define( 'GRAVATAR_HOVERCARDS__DISABLE', true );
	}
}
add_action( 'wp_loaded', 'supportforums_disable_gravatar_hovercards' );

function supportforums_enqueue_scripts() {
	$template_dir_uri = get_template_directory_uri();

	wp_enqueue_style( 'spf-standalone-css', "$template_dir_uri/style.css", [], '20230127' );

	wp_enqueue_script( 'supportforums-ellipsis-menu', get_template_directory_uri() . '/ellipsis-menu.js', [], '20230216', true );
	wp_enqueue_script( 'supportforums-breadcrumb', get_template_directory_uri() . '/assets/js/breadcrumb.js', [], '20230308', true );

	if ( bbp_is_single_topic() || supportforums_is_spam_page() || supportforums_is_trash_page() ) {
		wp_enqueue_script( 'supportforums-view-topic-form', get_template_directory_uri() . '/view-topic-form.js', [], '20230112', true );
	}

	if ( apply_filters( 'support_forums_use_blocks_everywhere', false ) ) {
		wp_enqueue_style( 'recoleta-font', 'https://s1.wp.com/i/fonts/recoleta/css/400.min.css', [], '20210909' );
		wp_enqueue_style( 'supportforums-overrides', get_template_directory_uri() . '/overrides.css', [], '20220928' );

		if ( is_rtl() ) {
			wp_enqueue_style( 'supportforums-overrides-rtl', "$template_dir_uri/rtl/overrides-rtl.css" );
		}

		wp_enqueue_script( 'supportforums-placeholder', get_template_directory_uri() . '/placeholder.js', [ 'wp-hooks', 'wp-i18n' ], '20221004' );

		if ( supportforums_is_new_topic_page() || bbp_is_topic_edit() || bbp_is_single_topic() || bbp_is_reply_edit() ) {
			wp_enqueue_script( 'supportforums-new-topic-form', get_template_directory_uri() . '/new-topic-form.js', [], '20221004', true );
		}
	}

	wp_enqueue_style( 'supportforums-2022-theme', get_template_directory_uri() . '/style-2022.css', [], '20230124' );

	wp_enqueue_script( 'supportforums-menu', get_template_directory_uri() . '/mobile-menu.js', array( 'jquery' ), '20211213', true );

	wp_enqueue_script( 'supportforums-jetpack-search', get_template_directory_uri() . '/jetpack-search.js', [ 'wp-i18n' ], '20221114' );
}
add_action( 'wp_enqueue_scripts', 'supportforums_enqueue_scripts' );

/**
 * Get a string from the URL usable for the "path" portion of a Tracks event.
 */
function supportforums_get_url_path() {
	if ( is_search() ) {
		return '/?s';
	}

	return $_SERVER[ 'REQUEST_URI' ];
}

if ( ! function_exists( 'supportforums_get_subdomain_lang' ) ) {
	function supportforums_get_subdomain_lang() {
		return 'en';
	}
}

/**
 * Output a link to the new topic form within a page or the ?new=1 topic page.
 */
function supportforums_new_topic_link() {
	$url = false;

	if ( is_front_page() ) {
		$url = esc_url( add_query_arg( array( 'new' => 1 ), home_url() ) );
	} else if ( bbp_is_single_forum() ) {
		$url = esc_url( add_query_arg( [ 'new' => 1 ], bbp_get_forum_permalink( bbp_get_forum_id() ) ) );
	}

	if ( ! empty( $url ) ) {
		echo sprintf( '<a class="button-primary new-topic" href="%s">%s</a>',
			$url,
			__( 'Add New Topic' )
		);
	}
}

/**
 * h5 overrides.
 */
function supportforums_remove_h5_wp_title() {
	remove_filter( 'wp_title', 'h5_wp_title', 10 );
}
add_action( 'wp_loaded', 'supportforums_remove_h5_wp_title' );

function h5_title() {
	return;
}

function h5_tagline() {
	return;
}

/**
 * Don't autodelete content for now.
 */
remove_action( 'wp_scheduled_delete', 'wp_scheduled_delete' );

/**
 * h5 actions and filters.
 */
function supportforums_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( supportforums_is_new_topic_page() ) {
		return esc_html_e( 'Add a new topic' );
	}

	if ( is_feed() ) {
		return $title;
	}

	$title .= get_bloginfo( 'name' );
	return $title;
}
add_filter( 'wp_title', 'supportforums_wp_title', 10, 2 );

function bbp_is_topic_new() {
	return isset( $_GET['new'] ) && $_GET['new'] == 1;
}

function spf_get_search_form() {
	$label       = esc_html( __( 'Search for:' ) );
	$placeholder = esc_attr( __( 'Search questions, keywords, topics' ) );
	return <<<FORM
	<form role="search" method="get" id="searchform" class="spf-search-form">
		<div>
			<label class="screen-reader-text" for="s">$label</label>
			<input type="search" class="spf-search__search-input" value="" name="s" id="s" placeholder="$placeholder" title="$placeholder">
		</div>
	</form>
FORM;
}

/**
 * bbPress overrides.
 */

function supportforums_topic_title() {
	?>
	<div class="bbp-topic-title"><h1><?php bbp_topic_title(); ?></h1></div>
	<?php
}

function supportforums_forum_title() {
	?>
	<div class="bbp-forum-title"><h1><?php the_title(); ?>

			<?php if ( bbp_current_user_can_access_create_topic_form() ) : ?>

				&mdash; <?php supportforums_new_topic_link(); ?>

			<?php endif; ?>

		</h1></div>
	<?php
}

function jetpackcom_jp_filter_query( $options ) {
	$options['adminQueryFilter'] = array(
		'bool' => array(
			'must_not' => array(
				array( 'term' => array( 'post_type' => 'attachment' ) ),
			)
		)
	);
	$options['overlayOptions']['overlayTrigger'] = 'results';
	$options['overlayOptions']['highlightColor'] = '#e6f2e8';

	return $options;
}
add_filter( 'jetpack_instant_search_options', 'jetpackcom_jp_filter_query' );

/* In order for Elasticsearch to correctly index topics and forums, we need to
 * make sure that the post types are registered */
function supportforums_register_post_types() {
	if ( false === post_type_exists( 'topic' ) ) {
		register_post_type(
			'topic',
			array(
				'exclude_from_search' => false,
				'public'              => true,
			)
		);
	}
	if ( false === post_type_exists( 'forum' ) ) {
		register_post_type(
			'forum',
			array(
				'exclude_from_search' => false,
				'public'              => true,
			)
		);
	}
}
add_action( 'init', 'supportforums_register_post_types', 0 );

function supportforums_get_support_url() {
	$locale = $GLOBALS['locale'];
	if ( 'en' === $locale ) {
		return 'https://wordpress.com/support/';
	} else {
		return "https://wordpress.com/{$locale}/support/";
	}
}

/**
 * Get metadata for the page/post we are currently on.
 *
 * @return array
 */
function supportforums_get_metadata() {
	// URLs need to be stripped of pagination (/page/2) so we need to look for some specialty cases
	// to get the base URL instead of the paginated one.
	if ( bbp_is_topic_tag() ) {
		// e.g. https://wordpress.com/forums/topic-tag/wpcomhelp/page/2/
		$url = bbp_get_topic_tag_link();
	} elseif ( bbp_is_single_user_replies() ) {
		// e.g. https://wordpress.com/forums/users/timethief/replies/page/2/
		$url = bbp_get_user_replies_created_url();
	} elseif ( bbp_is_topics_created() ) {
		// e.g. https://wordpress.com/forums/users/timethief/topics/page/2/
		$url = bbp_get_user_topics_created_url();
	} elseif ( bbp_is_single_user() ) {
		// e.g. https://wordpress.com/forums/users/timethief/
		$url = bbp_get_user_profile_url();
	} elseif ( bbp_is_single_view() ) {
		// e.g. https://wordpress.com/forums/view/no-replies/page/2/
		$url = bbp_get_view_url();
	} else {
		// Catchall for forum pages and topic pages
		// e.g. https://wordpress.com/forums/topic/this-site-is-doing-all-kinds-of-strange-things-now-it-is-no-longer-showing-the-full-image-that-i-am-adding-and-if-i-want-to-blow-face-text-it-only-does-a-little-piece-of-it-at-a-time-instead-of-all/page/2
		// e.g. https://wordpress.com/forums/forum/support/page/2/
		$url = bbp_get_topic_permalink();
	}

	// Fallback in case no URL worked
	if ( empty( $url ) ) {
		$url = supportforums_get_support_url() . supportforums_get_url_path();
	}

	$metadata = [
		'title' => get_the_title(),
		'url'   => $url,
	];

	if ( bbp_is_single_topic() ) {
		$metadata['author']               = bbp_get_topic_author_display_name();
		$metadata['author_thumbnail_url'] = get_avatar_url( bbp_get_topic_author_id() );
		$metadata['date_published']       = get_post_time( 'c', false, bbp_get_topic_id() );
		$terms                            = get_the_terms( bbp_get_topic_id(), bbp_get_topic_tag_tax_id() );
		$metadata['tags']                 = [];
		if ( ! empty( $terms ) ) {
			$metadata['tags'] = wp_list_pluck( $terms, 'name' );
		}
	}

	if ( bbp_is_single_forum() || bbp_is_single_topic() ) {
		$metadata['section'] = bbp_get_forum_title();
	}

	return $metadata;
}

/**
 * Output a script tag with JSON-LD for the current page.
 *
 * @return void
 */
function supportforums_add_json_ld(): void {
	$metadata = supportforums_get_metadata();
	$options  = [
		'@context' => 'https://schema.org',
		'url'      => $metadata['url'],
	];

	if ( is_front_page() ) {
		$options['@type']            = 'WebSite';
		$options['potentialAction'] = [
			'@type'       => 'SearchAction',
			'target'      => [
				'@type'       => 'EntryPoint',
				'urlTemplate' => untrailingslashit( get_home_url() ) . '/?s={search_term_string}',
			],
			'query-input' => 'required name=search_term_string',
		];

	} else {
		$is_single_topic     = bbp_is_single_topic();
		$options['headline'] = $metadata['title'];
		$options['@type']     = $is_single_topic ? 'NewsArticle' : 'WebPage';

		if ( $is_single_topic ) {
			$options['creator']       = $metadata['author'];
			$options['thumbnailUrl']  = $metadata['author_thumbnail_url'];
			$options['datePublished'] = $metadata['date_published'];

			if ( ! empty( $metadata['tags'] ) ) {
				$options['keywords'] = $metadata['tags'];
			}
		}

		if ( ! empty( $metadata['section'] ) ) {
			$options['articleSection'] = $metadata['section'];
		}
	}

	echo '<script type="application/ld+json">' . wp_json_encode( $options ) . '</script>' . "\n";
}

add_action( 'wp_head', 'supportforums_add_json_ld' );

function add_original_poster_indicator() {
	$reply_id = bbp_get_reply_id();
	if ( bbp_get_reply_author_id( $reply_id ) === bbp_get_topic_author_id( bbp_get_reply_topic_id( $reply_id ) ) ) {
		echo '<div class="bbp-author-original-poster">' . esc_html__('Original poster') . '</div>';
	}
}
add_action( 'bbp_theme_after_reply_author_details', 'add_original_poster_indicator' );

function supportforums_bilmur_get_page_type(): string {
	global $wp_query;

	if ( bbp_is_single_view() ) {
		$matches = [];
		preg_match( '#view/([a-z\-]+?)/#', bbp_get_view_url(), $matches );
		return empty( $matches[1] ) ? 'view-unknown' : 'view-' . $matches[1];
	} elseif ( bbp_is_single_topic() ) {
		return 'topic';
	}

	// Sandboxed test forum urls are not prefixed by /forums/ like production ones.
	$forum_url_path = supportforums_get_url_path();
	$page_type      = 'other';
	if( str_contains( $forum_url_path, '/forums/users/' ) ){
		$page_type = 'user';
		if ( str_contains( $forum_url_path, '/topics/' ) ) {
			$page_type = 'user_topics';
		} elseif ( str_contains( $forum_url_path, '/replies/') ) {
			$page_type = 'user_replies';
		} elseif ( str_contains( $forum_url_path, '/favorites/') ) {
			$page_type = 'user_favorites';
		} elseif ( str_contains( $forum_url_path, '/subscriptions/') ) {
			$page_type = 'user_subscriptions';
		}
	} elseif( str_contains( $forum_url_path, '/forums/?new=1' ) ){
		$page_type = 'topic_new';
	} elseif( str_contains( $forum_url_path, '/forums/forum/' ) ){
		$page_type = 'subforum';
	} elseif( str_contains( $forum_url_path, '/forums/topic-tag/' ) ) {
		$page_type = 'topic_tag';
	} elseif ( str_contains( $forum_url_path, '/forums/' ) ) {
		$page_type = 'front';
	} elseif ( $wp_query->is_search ) {
		$page_type = 'search';
	}

	return $page_type;
}

// By default, 'notify me' is checked for new topics.
function supportforums_filter_get_form_topic_subscribed( $checked ) {
	if ( bbp_is_topic_edit() || bbp_is_reply_edit() ) {
		// should keep its state in edit for all users
		return $checked;
	} else {
		return 'checked';
	}
}

add_filter( 'bbp_get_form_topic_subscribed', 'supportforums_filter_get_form_topic_subscribed' );

function supportforums_strip_topic_title( $topic_title ) {
	return wp_strip_all_tags( $topic_title );
}
add_filter( 'bbp_get_topic_title', 'supportforums_strip_topic_title' );

// Don't display the breadcrumb on some pages.
function supportforums_should_show_breadcrumb( $show_breadcrumbs ) {
	if ( bbp_is_forum_archive() ) {
		return true;
	}

	return $show_breadcrumbs;
}

add_filter( 'bbp_no_breadcrumb', 'supportforums_should_show_breadcrumb', 10 );

add_filter( 'bbp_use_single_topic_description', '__return_false' );
add_filter( 'bbp_use_single_forum_description', '__return_false' );

/**
 * bbPress does some odd upper-casing on English strings so we need to override it.
 *
 * If we fixed the strings all translated strings would break because translations
 * are case-sensitive.
 *
 * @param string $string The translated string.
 *
 * @return void
 */
function supportforums_output_en_as_lowercase( string $string ): void {

	if ( 'en' === supportforums_get_subdomain_lang() ) {
		echo esc_html( ucfirst( strtolower( $string ) ) );
		return;
	}

	echo esc_html( $string );
}

/**
 * Adding body classes for the current page.
 */
function supportforums_body_class( $classes ) {
	$view_name = '';
	if ( bbp_is_single_view() ) {
		$view_name = bbp_get_view_id();
	}

	if (
		// Is the home page & not the new topic page
		is_front_page() && ! supportforums_is_new_topic_page() ||
		// Is a topic tag page
		bbp_is_topic_tag() ||
		// Is a list page
		bbp_is_single_view() ||
		// Is a sub-forum page & not the new topic page
		bbp_is_single_forum() && ! supportforums_is_new_topic_page() ||
		// Is a topic page
		bbp_is_single_topic() ||
		// Is a profile page
		bbp_is_single_user() ||
		// Is 404
		is_404()
	) {
		$classes[] = 'has-2022-theme-styling';
	}

	if ( post_password_required( get_the_ID() ) ) {
		$classes[] = 'is-password-protected';
	}

	if ( bbp_is_single_view() || bbp_is_single_forum() || bbp_is_topic_tag() || is_front_page() ) {
		if ( ! in_array( $view_name, [ 'trash', 'spam' ] ) ) {
			$classes[] = 'has-top-color';
		}
	}

	return $classes;
}

function supportforums_should_page_have_sidebar( $post_id ) {
	return bbp_is_single_topic() && ! post_password_required( $post_id );
}

function supportforums_should_have_search(): bool {
	return ! bbp_is_single_user() && ! bbp_is_topic_tag_edit() && ! is_404() && ! bbp_is_topic_edit() && ! bbp_is_reply_edit() && ! bbp_is_topic_merge();
}

function supportforums_should_have_resources_section() {
	if (
		is_front_page() && ! supportforums_is_new_topic_page() ||
		bbp_is_topic_tag() ||
		bbp_is_single_view() && ! supportforums_is_spam_page() && ! supportforums_is_trash_page() ||
		bbp_is_single_forum() ||
		is_404()
	) {
		return apply_filters( 'supportforums_should_have_resources_section', true );
	}

	return false;
}

/**
 * Overrides bbpress pagination.
 */
function supportforums_pagination( $settings ) {
	$settings['prev_text'] = '';
	$settings['next_text'] = '';

	return $settings;
}

add_filter( 'bbp_topic_pagination', 'supportforums_pagination' );
add_filter( 'bbp_replies_pagination', 'supportforums_pagination' );

/**
 * Overrides pagination output
 */
function supportforums_paginate_output( $output, $arguments ) {
	$separator                  = "\n";
	$links                      = explode( $separator, $output );
	$current_page               = $arguments['current'];
	$second_page                = 2;
	$third_page                 = 3;
	$forth_page                 = 4;
	$last_page                  = $arguments['total'] - 1;
	$penultimate_page           = $last_page - 1;
	$antepenultimate_page       = $last_page - 2;
	$last_page_index            = count( $links ) - 2;
	$penultimate_page_index     = $last_page_index - 1;
	$antepenultimate_page_index = $last_page_index - 2;

	$dots = '';
	foreach ( $links as $key => $link ) {
		if ( strpos( $link, '&hellip;' ) !== false ) {
			$dots = $link;
			// Hide dots
			$links[ $key ] = '';
		}
	}

	if ( $current_page > $third_page ) {
		$links[ $second_page ] = $dots;
	}

	if ( $current_page > $forth_page ) {
		$links[ $third_page ] = '';
	}

	if ( $current_page < $penultimate_page ) {
		$links[ $penultimate_page_index ] = $dots;
	}

	if ( $current_page < $antepenultimate_page ) {
		$links[ $penultimate_page_index ]     = '';
		$links[ $antepenultimate_page_index ] = $dots;
	}

	return implode( $separator, $links );
}

add_filter( 'paginate_links_output', 'supportforums_paginate_output', 1, 2 );

/**
 * Check if the secondary sidebar is available
 * @return boolean
 */
function spf_has_additional_sidebar() {
	return apply_filters( 'spf_has_additional_sidebar', false );
}


function supportforums_is_super_admin() {
	return is_multisite() && ( bbp_is_single_user() || bbp_is_single_user_edit() ) && current_user_can( 'manage_network_options' ) && is_super_admin( bbp_get_displayed_user_id() );
}

/**
 * Use custom 404 page template from the current version templates folder
 *
 * @param $template
 *
 * @return mixed|string
 */
function spf_custom_404_template( $template ) {
	if ( is_404() ) {
		$template = locate_template( "bbpress/404.php" );

		if ( ! $template ) {
			$template = locate_template( '404.php' );
		}
	}
	return $template;
}
add_filter( 'template_include', 'spf_custom_404_template' );

function supportforums_custom_pw_protected_post_form( $post_id = 0 ) {
	$form_action = esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) );
	$title_text = __( 'This content is password protected' );
	$label_text = __( 'To view it please enter the password below:' );
	$input_id  = 'pwbox-' . ( empty( $post->ID ) ? rand() : $post->ID );

	return <<<FORM
<form action="$form_action" class="spf-pw-protected__form" method="post">
	<h1 class="spf-pw-protected__title">$title_text</h1>

	<label for="$input_id">$label_text</label>

	<div class="spf-pw-protected__form-actions">
		<input name="post_password" id="$input_id" class="spf-pw-protected__pw-input" type="password" />

		<input type="submit" name="Submit" class="spf-pw-protected__submit" />
	</div>
</form>
FORM;

}

function supportforums_init_theme() {
	add_filter( 'the_password_form', 'supportforums_custom_pw_protected_post_form' );
	add_filter( 'body_class', 'supportforums_body_class' );

	/**
	 * Hide notice if user is super admin, we will handle it in the new template
	 */
	remove_action( 'bbp_template_notices', 'bbp_notice_edit_user_is_super_admin', 2 );
}

add_action( 'init', 'supportforums_init_theme' );

add_action( 'bbp_new_forum', 'supportforums_clear_bbp_subforum_permalinks_cache' );
add_action( 'bbp_update_forum', 'supportforums_clear_bbp_subforum_permalinks_cache' );

function supportforums_clear_bbp_subforum_permalinks_cache() {
	wp_cache_delete( SUPPORTFORUMS_SUBFORUM_PERMALINKS_CACHE_KEY, SUPPORTFORUMS_CACHE_GROUP );
}

function spf_bbp_before_get_user_subscribe_link_parse_args( $r, $args, $defaults) {
	if ( ! bbp_is_single_user() ) {
		$r['subscribe']   = '<span class="spf-reply__icon-wrapper" data-tooltip="' . esc_attr( __( 'Subscribe to topic' ) ) . '">' . spf_get_svg( 'icon-bell.svg' ) . '</span>';
		$r['unsubscribe'] = '<span class="spf-reply__icon-wrapper" data-tooltip="' . esc_attr( __( 'Unsubscribe from topic' ) ) . '">' . spf_get_svg( 'icon-bell-disabled.svg' ) . '</span>';
		$r['before']      = '';
	}

	return $r;
}

add_filter( 'bbp_before_get_user_subscribe_link_parse_args','spf_bbp_before_get_user_subscribe_link_parse_args', 10, 3 );

/**
 * Register widgetized area and update sidebar with default widgets
 */
function spf_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Sidebar' ),
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );
}
add_action( 'init', 'spf_widgets_init' );

/**
 * User must select a forum for their new thread.
 *
 * @param array $args The arguments for bbp_get_dropdown()
 * @return array The filtered array
 */
function supportforums_remove_show_none_from_forum_dropdown( $args ) {
	if ( bbp_get_forum_post_type() === $args['post_type'] ) {
		$args['show_none'] = false;
		remove_filter( 'bbp_after_get_dropdown_parse_args','supportforums_remove_show_none_from_forum_dropdown' );
	}
	return $args;
}

/**
 * Display the new topic template at ?new=1.
 *
 * @param string $template The default template location
 * @return string The filtered template location, if available
 */
function supportforums_maybe_load_new_topic_page( $template ) {
	if ( ! isset( $_GET['new'] ) || ! $_GET['new'] === '1' ) {
		return $template;
	}

	$found = locate_template( array( 'page-create-topic.php' ) );

	if ( $found === '' ) {
		return $template;
	}

	add_filter( 'bbp_after_get_dropdown_parse_args',  'supportforums_remove_show_none_from_forum_dropdown' );

	return $found;
}

// Back-compat for ?new=1 new topic template
add_action( 'template_include', 'supportforums_maybe_load_new_topic_page', 11 );

