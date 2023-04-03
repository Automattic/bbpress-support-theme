<?php

/**
 * User Details
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

do_action( 'bbp_template_before_user_details' ); ?>

<div class="spf-user-details">
	<div class="spf-user-details__user-data">
		<div class="spf-user-details__avatar">
			<a class="spf-user-details__avatar-link" href="<?php bbp_user_profile_url(); ?>"
			   data-tooltip="<?php bbp_displayed_user_field( 'display_name' ); ?>" rel="me">
				<?php echo get_avatar( bbp_get_displayed_user_field( 'user_email', 'raw' ), apply_filters( 'bbp_single_user_details_avatar_size', 172 ) ); ?>
			</a>
		</div>

		<div class="spf-user-details__name-mobile">
			<?php bbp_get_template_part( 'user', 'name' ); ?>
		</div>
	</div>

	<?php do_action( 'bbp_template_before_user_details_menu_items' ); ?>

	<?php
	$menu_items = [
		'user_profile'       => [
			'is_visible'  => true,
			'is_selected' => bbp_is_single_user_profile(),
			'url'         => bbp_get_user_profile_url(),
			/* translators: %s: user profile link */
			'title'       => sprintf( esc_attr__( "%s's Profile" ), bbp_get_displayed_user_field( 'display_name' ) ),
			'label'       => __( 'Profile' ),
		],
		'user_topics'        => [
			'is_visible'  => true,
			'is_selected' => bbp_is_single_user_topics(),
			'url'         => bbp_get_user_topics_created_url(),
			/* translators: %s: user topics link */
			'title'       => sprintf( esc_attr__( "%s's Topics Started" ), bbp_get_displayed_user_field( 'display_name' ) ),
			'label'       => __( 'Topics started' ),
		],
		'user_replies'       => [
			'is_visible'  => true,
			'is_selected' => bbp_is_single_user_replies(),
			'url'         => bbp_get_user_replies_created_url(),
			/* translators: %s: user replies link */
			'title'       => sprintf( esc_attr__( "%s's Replies Created", ), bbp_get_displayed_user_field( 'display_name' ) ),
			'label'       => __( 'Replies created' ),
		],
		'user_favorites'     => [
			'is_visible'  => bbp_is_favorites_active(),
			'is_selected' => bbp_is_favorites(),
			'url'         => bbp_get_favorites_permalink(),
			/* translators: %s: user favorites link */
			'title'       => sprintf( esc_attr__( "%s's Favorites" ), bbp_get_displayed_user_field( 'display_name' ) ),
			'label'       => __( 'Favorites' ),
		],
		'user_subscriptions' => [
			'is_visible'  => ( bbp_is_user_home() || current_user_can( 'edit_user', bbp_get_displayed_user_id() ) ) && bbp_is_subscriptions_active(),
			'is_selected' => bbp_is_subscriptions(),
			'url'         => bbp_get_subscriptions_permalink(),
			/* translators: %s: user subscriptions link */
			'title'       => sprintf( esc_attr__( "%s's Subscriptions" ), bbp_get_displayed_user_field( 'display_name' ) ),
			'label'       => __( 'Subscriptions' ),
		],
	];

	do_action( 'bbp_template_before_user_details_menu_items' );
	?>

	<ul class="spf-user-details__navigation">
		<?php foreach ( $menu_items as $menu_item ) : ?>
			<?php if ( $menu_item['is_visible'] ) : ?>
				<li class="spf-user-details__navigation-item">
					<a class="spf-user-details__navigation-item-link <?php echo $menu_item['is_selected'] ? 'is-selected' : ''; ?>" title="<?php echo esc_attr( $menu_item['title'] ); ?>" href="<?php echo esc_url( $menu_item['url'] ); ?>">
						<?php echo esc_html( $menu_item['label'] ); ?>
					</a>
				</li>
			<?php endif; ?>
		<?php endforeach; ?>
	</ul>

	<select class="spf-user-details__navigation-mobile spf-select-input" title="<?php esc_attr_e( 'User navigation' ); ?>">
		<?php foreach ( $menu_items as $menu_item ) : ?>
			<?php if ( $menu_item['is_visible'] ) : ?>
				<option class="spf-user-details__navigation-item" value="<?php echo esc_url( $menu_item['url'] ); ?>" <?php echo $menu_item['is_selected'] ? 'selected' : ''; ?>>
					<?php echo esc_html( $menu_item['label'] ); ?>
				</option>
			<?php endif; ?>
		<?php endforeach; ?>
	</select>

	<?php do_action( 'bbp_template_after_user_details_menu_items' ); ?>

</div>

<?php do_action( 'bbp_template_after_user_details' ); ?>
