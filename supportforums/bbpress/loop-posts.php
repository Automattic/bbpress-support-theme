<?php
/**
 * Posts Loop
 *
 * This template is a straight copy from the 2.5 version of the templates in the supportforums theme.
 *
 * It's used to display the list of topics and replies in the spam view.
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<?php spf_render_breadcrumb(); ?>

<h1 class="spf-page-title">
	<?php bbp_view_title(); ?>
</h1>

<div class="spf-topic-content-list">
	<?php
	while ( bbp_topics() ) :

	bbp_the_topic();
	
	$topic_id = bbp_get_reply_topic_id();
	$forum_id = bbp_get_reply_forum_id();
	$reply_id = bbp_get_reply_id();
	$topic_label = esc_html( 'In reply to' );
	
	if ( 'topic' === get_post_type() ) {
		$topic_id = $reply_id = bbp_get_topic_id();
		$forum_id = bbp_get_topic_forum_id();
		$topic_label = esc_html( 'As the topic' );
	} else {
		bbpress()->reply_query = bbpress()->topic_query;
	}
	
	if ( ! $topic_id || ! $forum_id ) {
		$topic_id = wp_get_post_parent_id( $reply_id );
		$forum_id = wp_get_post_parent_id( $topic_id );
	}
	?>

		<div id="post-<?php echo esc_attr( $topic_id ); ?>" class="spf-topic-content-list__header">					
			<a class="spf-topic-content-list__permalink" href="<?php bbp_forum_permalink( $forum_id ); ?>"><?php bbp_forum_title( $forum_id ); ?></a>				
			<?php esc_html_e( 'forum' ); ?>
			<span class="spf-topic-content-list__dot">·</span>				
			<?php echo $topic_label; ?>			
			<a class="spf-topic-content-list__permalink" href="<?php bbp_topic_permalink( $topic_id ); ?>"><?php bbp_topic_title( $topic_id ); ?></a>			
		</div>
		
		<div class="spf-topic-content-list__body">
			<?php bbp_get_template_part( 'loop', 'single-reply' ); ?>
		</div>
		
	<?php endwhile; ?>
</div>

