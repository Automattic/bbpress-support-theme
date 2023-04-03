<?php

/**
 * Topics list navigation
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

?>

<div class="spf-header-navigation">

	<?php if ( bbp_is_forum_open() ) : ?>

		<?php if ( is_user_logged_in() ) : ?>

			<a class="spf-header-navigation__new-topic"
			   href="<?php echo esc_url( spf_get_new_topic_url() ); ?>"><?php esc_html_e( 'Add new topic' ); ?></a>

		<?php else : ?>

			<p class="spf-header-navigation__log-in">

				<?php

					$support_forums_login_url = wp_login_url( get_permalink() );

					if ( function_exists( 'localized_wpcom_url' ) ) {
						$support_forums_login_url = localized_wpcom_url( '//wordpress.com/log-in/' );
					}

					printf(
							__( '<a href="%s">Log in</a> to add new topics' ),
							esc_url(
									apply_filters( 'support_forums_login_url', $support_forums_login_url, spf_get_new_topic_url() )
							)
					); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped, WordPress.WP.I18n.MissingTranslatorsComment
				?>

			</p>

		<?php endif; ?>

	<?php endif; ?>

	<?php
	$subforums      = spf_get_subforums_select_options();
	$selected_index = array_search( true, array_column( $subforums, 2 ) );
	$title          = empty( $selected_index ) ? $subforums[0][0] : $subforums[ $selected_index ][0];
	?>

	<div class="spf-header-navigation__subforum-selector" data-ellipsis-menu>
		<a class="spf-header-navigation__selector-view-list-toggle" href="javascript:;">
			<?php esc_html_e( $title ); ?>
			<span class="spf-header-navigation__arrow-icon"><?php spf_render_svg( 'icon-down-arrow.svg' ); ?></span>
		</a>

		<ul class="spf-header-navigation__view-list">
			<?php foreach ( spf_get_subforums_select_options() as [ $forum_title, $forum_url, $forum_is_selected ] ) : ?>
				<li class="spf-header-navigation__view-list-item">
					<a class="spf-header-navigation__view-link <?php echo $forum_is_selected ? ' spf-header-navigation__view-link--is-active' : ''; ?>"
					   href="<?php echo esc_url( $forum_url ); ?>">
						<?php esc_html_e( $forum_title ); ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div><!-- Sub-forums selector -->

	<?php if ( ! bbp_is_single_forum() && bbp_get_views() ) : ?>

		<?php spf_render_desktop_view_list(); ?>

		<?php spf_render_mobile_view_list(); ?>

	<?php endif; ?>

</div>

