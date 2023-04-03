<?php

/**
 * Rendering the replies of a topic
 *
 * @return string
 */
function spf_render_replies_count( $topic_id = 0 ) {
	$replies_count = spf_get_replies_count( $topic_id );

	esc_html_e( sprintf( _n( '%s reply', '%s replies', $replies_count ), $replies_count ) );
}

/**
 * Getting the replies count of a topic
 *
 * @return int
 */
function spf_get_replies_count( $topic_id = 0 ) {
	return bbp_show_lead_topic() ? bbp_get_topic_reply_count( $topic_id ) : bbp_get_topic_post_count( $topic_id );
}

/**
 * Is a resolved topic
 *
 * @return boolean
 */
function spf_is_resolved_topic( $topic_id = 0 ) {
	$topic_id = empty( $topic_id ) ? bbp_get_topic_id() : $topic_id;

	if ( empty( $topic_id ) ) {
		return false;
	}

	return get_post_meta( $topic_id, 'topic_resolved', true ) === 'yes';
}

function spf_get_images_path( string $image_name ) {
	if ( is_child_theme() && file_exists( get_stylesheet_directory() . "/images/$image_name" ) ) {
		return get_stylesheet_directory_uri() . "/images/$image_name";
	}

	return get_template_directory_uri() . "/images/$image_name";
}

/**
 * Renders the full path to the specified image from the theme's `images/` folder.
 *
 * @param string $image_name
 *
 * @return void
 */
function spf_render_images_path( string $image_name ) {
	echo esc_url( spf_get_images_path( $image_name ) );
}

/**
 * Getting the SVG element from the theme's `images/` folder.
 *
 * @param string $svg_name
 *
 * @return string
 */
function spf_get_svg( string $svg_name ): string {
	if ( is_child_theme() && file_exists( get_stylesheet_directory() . "/images/$svg_name" ) ) {
		return file_get_contents( get_stylesheet_directory() . "/images/$svg_name" );
	}

	return file_get_contents( get_template_directory() . "/images/$svg_name" );
}

/**
 * Renders a SVG element from the theme's `images/` folder.
 *
 * @param string $svg_name
 *
 * @return void
 */
function spf_render_svg( string $svg_name ) {
	echo spf_get_svg( $svg_name );
}

/**
 * Getting the URL of the new topic page
 *
 * @return string
 */
function spf_get_new_topic_url() {
	$url = add_query_arg( array( 'new' => 1 ), home_url() );

	if ( bbp_is_single_forum() ) {
		$url = add_query_arg( [ 'new' => 1 ], bbp_get_forum_permalink( bbp_get_forum_id() ) );
	}

	return $url;
}

/**
 * Rendering desktop view list
 */
function spf_render_desktop_view_list() {
	?>

	<ul class="spf-topics-nav__h-view-list">

		<?php foreach ( spf_get_view_list_items( 3 ) as $item_key => $item_value ) : ?>

			<?php if ( $item_key === '_more' ) : ?>

				<li class="spf-topics-nav__h-view-item spf-topics-nav__h-view-item--is-more" data-ellipsis-menu>

					<?php

					$header_title = esc_html( 'More' );

					foreach ( $item_value['_items'] as $view_key => $view_value ) {
						if ( array_key_exists( 'is_active', $view_value ) && $view_value['is_active'] ) {
							$header_title = esc_html( $view_key );
						}

					}

					?>
					<a href="javascript:;"
					   class="spf-header-navigation__view-link spf-header-navigation__view-link--is-more<?php echo isset( $item_value['is_active'] ) && $item_value['is_active'] ? ' spf-header-navigation__view-link--is-active' : ''; ?>">

						<?php echo $header_title; ?>

						<span class="spf-header-navigation__arrow-icon">
							<?php spf_render_svg( 'icon-down-arrow.svg' ); ?>
						</span>
					</a>

					<ul class="spf-header-navigation__view-list">

						<?php foreach ( $item_value['_items'] as $view_key => $view_value ) : ?>

							<li class="spf-header-navigation__view-list-item">

								<a class="spf-header-navigation__view-link<?php echo isset( $view_value['is_active'] ) && $view_value['is_active'] ? ' spf-header-navigation__view-link--is-active' : ''; ?>"
								   href="<?php echo esc_url( $view_value['url'] ); ?>">

									<?php esc_html_e( $view_key ); ?>

								</a>

							</li>

						<?php endforeach; ?>

					</ul>

				</li>

			<?php else : ?>

				<li class="spf-topics-nav__h-view-item">

					<a class="spf-header-navigation__view-link<?php echo isset( $item_value['is_active'] ) && $item_value['is_active'] ? ' spf-header-navigation__view-link--is-active' : ''; ?>"
					   href="<?php echo esc_url( $item_value['url'] ); ?>">

						<?php esc_html_e( $item_key ); ?>

					</a>

				</li>

			<?php endif; ?>

		<?php endforeach; ?>

	</ul>

	<?php
}

/**
 * Rendering mobile view list
 */
function spf_render_mobile_view_list() {
	?>

	<div class="spf-header-navigation__selector-mobile-view-list-wrapper" data-ellipsis-menu>

		<?php $list_item = spf_get_view_list_items(); ?>

		<div class="spf-header-navigation__selector-view-list-toggle">

			<?php esc_html_e( array_keys( $list_item )[ array_search( true, array_column( $list_item, 'is_active' ) ) ] ); ?>
			<span class="spf-header-navigation__arrow-icon"><?php spf_render_svg( 'icon-down-arrow.svg' ); ?></span>

		</div>

		<ul class="spf-header-navigation__view-list">

			<?php foreach ( $list_item as $k => $v ) : ?>

				<li class="spf-header-navigation__view-list-item">

					<a class="spf-header-navigation__view-link spf-header-navigation__view-link--is-mobile<?php echo $v['is_active'] ? ' spf-header-navigation__view-link--is-active' : ''; ?>"
					   href="<?php echo esc_url( $v['url'] ); ?>">

						<?php esc_html_e( $k ); ?>

					</a>

				</li>

			<?php endforeach; ?>

		</ul>

	</div>

	<?php
}

/**
 * Getting topics nav > view list items
 *
 * @param int $displayed_items The number of items to be displayed / unfolded
 *
 * @return array
 */
function spf_get_view_list_items( $displayed_items = 0 ) {
	if ( ! bbp_get_views() ) {
		return [];
	}

	$views        = array_keys( bbp_get_views() );
	$current_path = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );
	$home_url     = trailingslashit( get_home_url() );
	$home_path    = parse_url( $home_url, PHP_URL_PATH );
	$is_active    = $current_path === $home_path || str_starts_with( $current_path, $home_path . 'page' );
	$items        = [ __( 'All topics' ) => [ 'url' => $home_url, 'is_active' => $is_active ] ];

	$idx = 0;
	foreach ( $views as $k ) {
		$url       = bbp_get_view_url( $k );
		$is_active = str_starts_with( $current_path, parse_url( $url, PHP_URL_PATH ) );
		$item      = [ 'url' => $url, 'is_active' => $is_active ];

		if ( $displayed_items <= 0 || $displayed_items - 2 >= $idx ) {
			$items[ bbp_get_view_title( $k ) ] = $item;
		} else {
			$items['_more']['_items'][ bbp_get_view_title( $k ) ] = $item;

			if ( $item['is_active'] ) {
				$items['_more']['is_active'] = true;
			}
		}

		$idx ++;
	}

	return $items;
}

/**
 * Getting topics nav > sub-forum select options
 *
 * @return array
 */
function spf_get_subforums_select_options() {
	if ( ! bbp_get_views() ) {
		return [];
	}

	$current_path = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );
	$home_url     = trailingslashit( get_home_url() );
	$home_path    = parse_url( $home_url, PHP_URL_PATH );
	$is_selected  = $current_path === $home_path || str_starts_with( $current_path, $home_path . 'page' );
	$items        = [ [ __( 'All forums' ), $home_url, $is_selected ] ];

	foreach ( bbp_get_forums_for_current_user() as $forum ) {
		if ( bbp_is_forum_open( $forum->ID ) ) {
			$url         = get_permalink( $forum->ID );
			$is_selected = str_ends_with( $current_path, parse_url( $url, PHP_URL_PATH ) );
			$items[]     = [ $forum->post_title, $url, $is_selected ];
		}
	}

	return $items;
}

function spf_get_topic_forum_permalink( int $topic_id ) {
	$topic_forum_id = bbp_get_topic_forum_id( $topic_id );

	return bbp_get_forum_permalink( $topic_forum_id );
}

function spf_render_topic_forum_permalink( int $topic_id ) {
	echo esc_url( spf_get_topic_forum_permalink( $topic_id ) );
}

function spf_get_topic_reply_link( int $topic_id ) {
	$topic = bbp_get_topic( $topic_id );

	// Bail if no topic or user cannot reply
	if ( empty( $topic ) || bbp_is_single_reply() || ! bbp_current_user_can_access_create_reply_form() ) {
		return false;
	}

	return remove_query_arg( array( 'bbp_reply_to', '_wpnonce' ) ) . '#new-post';
}

function spf_get_topic_reply_engagers( int $topic_id ): array {
	$engagers = [];
	// The topic OP is always engaged.
	$topic_author = bbp_get_topic_author_id( $topic_id );

	// If the topic has replies, get the reply authors.
	if ( spf_get_replies_count( $topic_id ) > 0 ) {
		$bbp_db = bbp_db();

		$sql = "SELECT DISTINCT(post_author) FROM {$bbp_db->posts} WHERE post_parent = %d AND post_status = %s AND post_type = %s ORDER BY ID";

		$query   = $bbp_db->prepare(
			$sql,
			$topic_id,
			bbp_get_public_status_id(),
			bbp_get_reply_post_type()
		);
		$results = $bbp_db->get_col( $query );

		if ( ! is_wp_error( $results ) ) {
			$engagers = wp_parse_id_list( array_filter( $results ) );
		}
	}

	return (array) apply_filters(
		'spf_get_topic_reply_engagers',
		array_unique( [ $topic_author, ...$engagers ] ),
		$topic_id
	);
}

function spf_render_topic_engager_avatars( int $topic_id, int $max_num ): void {
	$user_ids = spf_get_topic_reply_engagers( $topic_id );
	$add_dots = false;
	if ( count( $user_ids ) > $max_num ) {
		$user_ids = [ $user_ids[0], ...array_slice( $user_ids, - ( $max_num - 1 ) ) ];
		$add_dots = true;
	}

	static $engagers = [];
	foreach ( $user_ids as $idx => $user_id ) {
		if ( ! isset( $engagers[ $user_id ] ) ) {
			$usr               = get_userdata( $user_id );
			$cached_key        = "spf_user_v1_$user_id";
			$cached_expiration = 1 * HOUR_IN_SECONDS;
			$cached_usr_info   = wp_cache_get( $cached_key );

			if ( false === $cached_usr_info ) {
				$user_email = apply_filters( 'spf_user_display_value', $usr->get( 'user_email' ), $usr, 'user_email' );
				$user_login = apply_filters( 'spf_user_display_value', $usr->get( 'user_login' ), $usr, 'user_login' );
				$usr_info   = [
												'user_login' => $user_login,
												'author_url' => bbp_get_user_profile_url( $user_id ),
												'avatar_url' => get_avatar_url( $user_email, [ 'size' => 80 ] ),
												'alt'        => sprintf( __( 'User avatar for %s' ), $user_login ),
											];

				wp_cache_set( $cached_key, $usr_info, '', $cached_expiration );
			} else {
				$usr_info = $cached_usr_info;
			}

			// Filter displayed fields and hang on to the values in the static array because this gets called a lot.
			$engagers[ $user_id ] = $usr_info;
		}

		[ 'user_login' => $user_login, 'author_url' => $author_url, 'avatar_url' => $avatar_url, 'alt' => $alt ] = $engagers[ $user_id ];

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '<a href="' . esc_url( $author_url ) . '" data-tooltip="' . esc_attr( $user_login ) . '"><img src="' . esc_url( $avatar_url ) . '" alt="' . esc_attr( $alt ) . '" class="avatar avatar-40 nocard"></a>';

		if ( $add_dots && $idx === 0 ) {
			echo '<span class="spf-topics__dots"></span>';
		}
	}
}

/**
 * Is an accepted answer.
 *
 * @return boolean
 */
function spf_is_accepted_answer( int $topic_id = 0, int $reply_id = 0 ): bool {
	$topic_id = empty( $topic_id ) ? bbp_get_topic_id() : $topic_id;
	$reply_id = empty( $reply_id ) ? bbp_get_reply_id() : $reply_id;

	if ( empty( $topic_id ) || empty( $reply_id ) ) {
		return false;
	}

	return $reply_id === (int) get_post_meta( $topic_id, 'supportforums_accepted_answer', true );
}

/**
 * Getting topic edit URL.
 *
 * @return string
 */
function spf_get_topic_edit_url( int $topic_id = 0 ): string {
	$topic = bbp_get_topic( $topic_id );

	if (
		! current_user_can( 'edit_others_topics' ) &&
		( empty( $topic ) || ! current_user_can( 'edit_topic', $topic->ID ) || bbp_past_edit_lock( $topic->post_date_gmt ) )
	) {
		return '';
	}

	return bbp_get_topic_edit_url( $topic->ID );
}

/**
 * Getting reply edit URL.
 *
 * @return string
 */
function spf_get_reply_edit_url( int $reply_id = 0 ): string {
	$reply  = bbp_get_reply( $reply_id );

	if (
		! current_user_can( 'edit_others_replies' ) &&
		( empty( $reply ) || ! current_user_can( 'edit_reply', $reply->ID ) || bbp_past_edit_lock( $reply->post_date_gmt ) )
	) {
		return '';
	}

	return bbp_get_reply_edit_url( $reply->ID );
}

/**
 * Getting topic stick URL.
 *
 * @return string
 */
function spf_get_topic_stick_url( int $topic_id = 0 ): string {
	$topic_id = empty( $topic_id ) ? bbp_get_topic_id( $topic_id ) : $topic_id;

	if ( empty( $topic_id ) || ! current_user_can( 'moderate', $topic_id ) ) {
		return '';
	}

	return wp_nonce_url( add_query_arg( [ 'action' => 'bbp_toggle_topic_stick', 'topic_id' => $topic_id, 'super' => 1 ] ), 'stick-topic_' . $topic_id );
}

/**
 * Getting topic merge URL.
 *
 * @return string
 */
function spf_get_topic_merge_url( int $topic_id = 0 ): string {
	$topic_id = empty( $topic_id ) ? bbp_get_topic_id( $topic_id ) : $topic_id;

	if ( empty( $topic_id ) || ! current_user_can( 'moderate', $topic_id ) ) {
		return '';
	}

	return add_query_arg( [ 'action' => 'merge' ], bbp_get_topic_edit_url( $topic_id ) );
}

/**
 * Getting topic split URL.
 *
 * @return string
 */
function spf_get_topic_split_url( int $reply_id = 0 ): string {
	$reply_id = empty( $reply_id ) ? bbp_get_reply_id( $reply_id ) : $reply_id;

	if ( empty( $reply_id ) ) {
		return '';
	}

	$topic_id = bbp_get_reply_topic_id( $reply_id );

	if ( empty( $topic_id ) || ! current_user_can( 'moderate', $topic_id ) ) {
		return '';
	}

	return add_query_arg( [ 'action'   => 'split', 'reply_id' => $reply_id ], bbp_get_topic_edit_url( $topic_id ) );
}

/**
 * Getting topic close URL.
 *
 * @return string
 */
function spf_get_topic_close_url( int $topic_id = 0 ): string {
	$topic_id = empty( $topic_id ) ? bbp_get_topic_id( $topic_id ) : $topic_id;

	if ( empty( $topic_id ) || ! current_user_can( 'moderate', $topic_id ) ) {
		return '';
	}

	return wp_nonce_url( add_query_arg( [ 'action' => 'bbp_toggle_topic_close', 'topic_id' => $topic_id ] ), 'close-topic_' . $topic_id );
}

/**
 * Getting topic trash URL.
 *
 * @return string
 */
function spf_get_topic_trash_url( int $topic_id = 0, bool $is_trash = false ): string {
	$topic_id = empty( $topic_id ) ? bbp_get_topic_id( $topic_id ) : $topic_id;

	if ( empty( $topic_id ) || ! current_user_can( 'delete_topic', $topic_id ) ) {
		return '';
	}

	return wp_nonce_url( add_query_arg( [ 'action' => 'bbp_toggle_topic_trash', 'sub_action' => $is_trash ? 'untrash' : 'trash', 'topic_id' => $topic_id ] ),  ( $is_trash ? 'untrash-topic_' : 'trash-topic_' ) . $topic_id );
}

/**
 * Getting reply trash URL.
 *
 * @return string
 */
function spf_get_reply_trash_url( int $reply_id = 0, bool $is_trash = false ): string {
	$reply_id = empty( $reply_id ) ? bbp_get_reply_id( $reply_id ) : $reply_id;

	if ( empty( $reply_id ) || ! current_user_can( 'delete_reply', $reply_id ) ) {
		return '';
	}

	return wp_nonce_url( add_query_arg( [ 'action' => 'bbp_toggle_reply_trash', 'sub_action' => $is_trash ? 'untrash' : 'trash', 'reply_id' => $reply_id ] ),  ( $is_trash ? 'untrash-reply_' : 'trash-reply_' ) . $reply_id );
}

/**
 * Getting topic spam URL.
 *
 * @return string
 */
function spf_get_topic_spam_url( int $topic_id = 0 ): string {
	$topic_id = empty( $topic_id ) ? bbp_get_topic_id( $topic_id ) : $topic_id;

	if ( empty( $topic_id ) || ! current_user_can( 'moderate', $topic_id ) ) {
		return '';
	}

	return wp_nonce_url( add_query_arg( [ 'action' => 'bbp_toggle_topic_spam', 'topic_id' => $topic_id ] ), 'spam-topic_' . $topic_id );
}

/**
 * Getting reply spam URL.
 *
 * @return string
 */
function spf_get_reply_spam_url( int $reply_id = 0 ): string {
	$reply_id = empty( $reply_id ) ? bbp_get_reply_id( $reply_id ) : $reply_id;

	if ( empty( $reply_id ) || ! current_user_can( 'moderate', $reply_id ) ) {
		return '';
	}

	return wp_nonce_url( add_query_arg( [ 'action' => 'bbp_toggle_reply_spam', 'reply_id' => $reply_id ] ), 'spam-reply_' . $reply_id );
}

/**
 * Getting topic approve URL.
 *
 * @return string
 */
function spf_get_topic_approve_url( int $topic_id = 0 ): string {
	$topic_id = empty( $topic_id ) ? bbp_get_topic_id( $topic_id ) : $topic_id;

	if ( empty( $topic_id ) || ! current_user_can( 'moderate', $topic_id ) ) {
		return '';
	}

	return wp_nonce_url( add_query_arg( [ 'action' => 'bbp_toggle_topic_approve', 'topic_id' => $topic_id ] ), 'approve-topic_' . $topic_id );
}

/**
 * Getting reply approve URL.
 *
 * @return string
 */
function spf_get_reply_approve_url( int $reply_id = 0 ): string {
	$reply_id = empty( $reply_id ) ? bbp_get_reply_id( $reply_id ) : $reply_id;

	if ( empty( $reply_id ) || ! current_user_can( 'moderate', $reply_id ) ) {
		return '';
	}

	return wp_nonce_url( add_query_arg( [ 'action' => 'bbp_toggle_reply_approve', 'reply_id' => $reply_id ] ), 'approve-reply_' . $reply_id );
}

/**
 * Getting admin action items.
 *
 * @return array
 */
function spf_get_admin_action_items(): array {
	$topic_id             = bbp_get_topic_id();
	$reply_id             = bbp_get_reply_id();
	$user_id              = get_current_user_id();
	$is_topic             = 'topic' === get_post_type();
	$is_trash             = $is_topic ? bbp_is_topic_trash( $topic_id ) : bbp_is_reply_trash( $reply_id );
	$is_spam              = $is_topic ? bbp_is_topic_spam( $topic_id ) : bbp_is_reply_spam( $reply_id );
	$is_favorite          = bbp_is_user_favorite( $user_id, $topic_id );
	$is_pending           = $is_topic ? bbp_is_topic_pending( $topic_id ) : bbp_is_reply_pending( $reply_id );
	$is_topic_open        = bbp_is_topic_open( $topic_id );
	$is_sticky            = bbp_is_topic_sticky( $topic_id );
	$is_accepted_answer   = spf_is_accepted_answer();
	$accepted_answer_type = $is_accepted_answer ? 'remove' : 'add';
	$favorite_label       = __( 'Add topic to favorites' );
	$unfavorite_label     = __( 'Remove from favorites' );
	$favorite_data        = 'data-favorite data-bbp-object-type="post" data-bbp-object-id="' . $topic_id . '" ' .
	                        'data-bbp-object-action="favorite" ' .
	                        'data-bbp-object-nonce="' . wp_create_nonce( 'toggle-favorite_' . $topic_id ) . '" ' .
	                        'data-favorite-label="' . $favorite_label . '" ' .
	                        'data-unfavorite-label="' . $unfavorite_label . '" ' .
	                        'data-favorite-image="' . spf_get_images_path( 'icon-star-filled.svg' ) . '" ' .
	                        'data-unfavorite-image="' . spf_get_images_path( 'icon-star.svg' ) . '" ';

	$copy_url     = bbp_get_reply_url();
	$favorite_url = bbp_get_topic_favorite_link();
	$edit_url     = $is_topic ? spf_get_topic_edit_url( $topic_id ) : spf_get_reply_edit_url( $reply_id );
	$stick_url    = spf_get_topic_stick_url( $topic_id );
	$merge_url    = spf_get_topic_merge_url( $topic_id );
	$split_url    = spf_get_topic_split_url( $reply_id );
	$close_url 	  = spf_get_topic_close_url( $topic_id );
	$trash_url    = $is_topic ? spf_get_topic_trash_url( $topic_id, $is_trash ) : spf_get_reply_trash_url( $reply_id, $is_trash );
	$spam_url     = $is_topic ? spf_get_topic_spam_url( $topic_id ) : spf_get_reply_spam_url( $reply_id );
	$approve_url  = $is_topic ? spf_get_topic_approve_url( $topic_id ) : spf_get_reply_approve_url( $reply_id );

	$hide_edit            = ! $edit_url;
	$hide_stick					  = ! $is_topic || ! $stick_url;
	$hide_merge           = ! $is_topic || ! $merge_url;
	$hide_split           = $is_topic || ! $split_url;
	$hide_accepted_answer = $is_topic || $is_trash || $is_spam || $is_pending || ! current_user_can( 'moderate', $reply_id );
	$hide_close           = ! $is_topic || ! $close_url || $is_spam || $is_trash;
	$hide_trash           = ! $trash_url || $is_spam;
	$hide_spam            = ! $spam_url || $is_trash;
	$hide_approve         = ! $approve_url;

	$items = [
		[
			'icon'       => 'icon-link.svg',
			'label'      => __( 'Copy link' ),
			'data_attrs' => "data-clipboard-text=$copy_url",
		],
		[
			'icon'       => $is_favorite ? 'icon-star-filled.svg' : 'icon-star.svg',
			'label'      => $is_favorite ? $unfavorite_label : $favorite_label,
			'url'        => $favorite_url,
			'data_attrs' => $favorite_data,
			'is_hidden'  => ! $is_topic,
		],
		$hide_edit && $hide_stick && $hide_merge && $hide_split && $hide_accepted_answer ? '' : '_divider',
		[
			'icon'      => 'icon-edit.svg',
			'label'     => __( 'Edit' ),
			'url'       => $edit_url,
			'is_hidden' => $hide_edit,
		],
		[
			'icon'      => 'icon-pin.svg',
			'label'     => $is_sticky ? __( 'Unpin topic' ) : __( 'Pin topic' ),
			'url'       => $stick_url,
			'is_hidden' => $hide_stick,
		],
		[
			'icon'      => 'icon-merge.svg',
			'label'     => __( 'Merge' ),
			'url'       => $merge_url,
			'is_hidden' => $hide_merge,
		],
		[
			'data_attrs' => "data-accepted-answer=1 data-type=$accepted_answer_type data-topic-id=$topic_id data-reply-id=$reply_id",
			'icon'       => 'icon-accept.svg',
			'label'      => $is_accepted_answer ? __( 'Remove from accepted answer' ) : __( 'Accepted answer' ),
			'is_hidden'  => $hide_accepted_answer,
		],
		[
			'icon'      => 'icon-split.svg',
			'label'     => __( 'Split' ),
			'url'       => $split_url,
			'is_hidden' => $hide_split,
		],
		$hide_close && $hide_trash && $hide_spam && $hide_approve ? '' : '_divider',
		[
			'icon'      => $is_topic_open ? 'icon-close.svg' : 'icon-open.svg',
			'label'     => $is_topic_open ? __( 'Close' ) : __( 'Open' ),
			'url'       => $close_url,
			'is_hidden' => $hide_close,
		],
		[
			'icon'      => $is_trash ? 'icon-restore.svg' : 'icon-trash.svg',
			'label'     => $is_trash ? __( 'Restore' ) :  __( 'Trash' ),
			'url'       => $trash_url,
			'is_hidden' => $hide_trash,
		],
		[
			'icon'      => 'icon-warn.svg',
			'label'     => $is_spam ? __( 'Unspam' ) : __( 'Spam' ),
			'url'       => $spam_url,
			'is_hidden' => $hide_spam,
		],
		[
			'icon'      => $is_pending ? 'icon-seen.svg' : 'icon-unseen.svg',
			'label'     => $is_pending ? __( 'Approve' ) : __( 'Unapprove' ),
			'url'       => $approve_url,
			'is_hidden' => $hide_approve,
		],
	];

	// To filter the empty divider out
	$items = array_filter( $items, function( $item ) {
		return ! empty( $item );
	} );

	return $items;
}

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
function spf_render_en_as_lowercase( string $string ): void {

	if ( 'en' === supportforums_get_subdomain_lang() ) {
		echo esc_html( ucfirst( strtolower( $string ) ) );
		return;
	}

	echo esc_html( $string );
}

// Output a forums dropdown with disabled forums hidden and only if there is more than one forum to choose.
function spf_render_forums_dropdown( string $wrapper_class = '' ): void {
	$default_forum = bbp_get_form_topic_forum();

	$dropdown = bbp_get_dropdown(
		[
			'selected' => $default_forum,
			'select_class' => 'spf-topic-form__select',
		]
	);

	// Hide disabled forums that shouldn't be posted to ever.
	$dropdown = preg_replace( '@<option.*disabled="disabled".*<\/option>@', '', $dropdown );

	if (
		! apply_filters( 'supportforums_render_forums_dropdown_always', false ) &&
		preg_match_all( '/<option/', $dropdown ) < 2
	) {
		echo <<<INPUT
<input type="hidden" id="bbp_forum_id" name="bbp_forum_id" value="$default_forum" />
INPUT;
		return;
	}

	$label_text    = esc_html( __( 'Forum', 'bbpress' ) );
	$wrapper_class = esc_attr( $wrapper_class );
	echo <<<DROPDOWN
<p class='$wrapper_class'>
	<label class="spf-topic-form__label" for="bbp_forum_id">$label_text</label>
	$dropdown
</p>
DROPDOWN;
}

/**
 * Renders the login and signup link.
 *
 * @param string $title
 *
 * @return void
 */
function spf_render_login_signup_links( $title ) {
	$title        = esc_html( $title );
	$login_label  = esc_html__( 'Login' );
	$login_url    = esc_url( wp_login_url( get_permalink() ) );
	$signup_label = esc_html__( 'Sign Up' );
	$signup_url   = esc_url( add_query_arg( 'action', 'register', wp_login_url( get_permalink() ) ) );

	echo <<<LINK
		<div class="spf-login-signup-link">
			<span>$title</span>
			<a class="spf-login-signup-link__button" href="$login_url">$login_label</a>
			<a class="spf-login-signup-link__button spf-login-signup-link__button--primary" href="$signup_url">$signup_label</a>
		</div>
	LINK;
}

/**
 * Check wether the topic author is a paid user.
 * @return boolean
 */
function spf_is_topic_author_paid_user() {
	return apply_filters( 'spf_is_topic_author_paid_user', false );
}

/**
 * Renders user's profile link.
 *
 * @return void
 */
function spf_render_author_link( int $user_id, int $post_id = 0 ) {
	$user = get_userdata( $user_id );

	if ( empty( $user ) ) {
		return;
	}

	$display_name = esc_html( bbp_get_reply_author_display_name( $post_id ) );
	$profile_url  = esc_url( bbp_get_user_profile_url( $user_id ) );

	if ( $user->deleted ) {
		echo "<span class='spf-author-link'>$display_name</span>";
	} else {
		echo "<a href='$profile_url'>$display_name</a>";
	}
}

/**
 * Getting the back URL of the new topic page.
 *
 * @return string
 */
function spf_get_new_topic_back_url(): string {
	$prev_url = wp_get_referer();
	$is_wp_com = str_ends_with( parse_url( $prev_url )['host'], 'wordpress.com' );

	// If the user comes from a WP.com site (e.g. HE support, somewhere of the forum, etc.)
	// redirect the user back to the site, otherwise go to the home-page of the forum
	return $is_wp_com ? $prev_url : get_home_url();
}

/**
 * Renders breadcrumb for the spf.
 *
 * @param array $args. The arguments to pass to the breadcrumb
 * @return void
 */
function spf_render_breadcrumb( array $args = [] ) {
	$args = array_merge(
		[
			'before'         => '<div class="spf-breadcrumb is-hidden">',
			'after'          => '</div>',
			'sep'            => file_get_contents( __DIR__ . '/images/icon-right-arrow-no-tail.svg' ),
			'sep_before'     => '<span class="spf-breadcrumb-sep">',
			'current_before' => '<span class="spf-breadcrumb-current">',
			'include_root'   => false
		],
		$args
	);

	echo bbp_get_breadcrumb( $args );
}
