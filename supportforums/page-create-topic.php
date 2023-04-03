<?php
/**
 * Template Name: bbPress - Create Topic
 */

get_header(); ?>

<?php do_action( 'bbp_before_main_content' ); ?>

<?php do_action( 'bbp_template_notices' ); ?>

<div>

	<?php bbp_get_template_part( 'form', 'topic' ); ?>

</div><!-- #bbp-new-topic -->

<?php do_action( 'bbp_after_main_content' ); ?>

<?php get_footer(); ?>
