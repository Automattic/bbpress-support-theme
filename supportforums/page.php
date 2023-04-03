<?php

get_header(); ?>

<div id="primary" class="spf-content full-width" role="main">

	<?php while ( have_posts() ) : the_post(); ?>

		<?php get_template_part( 'content', 'page' ); ?>

	<?php endwhile; ?>

</div>

<?php get_footer(); ?>
