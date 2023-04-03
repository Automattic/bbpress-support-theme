<!DOCTYPE html>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width">
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php // below removes the no-js class from the html element ?>
<script>(function(H){H.className=H.className.replace(/\bno-js\b/,'js')})(document.documentElement)</script>

<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

<div id="page" class="hfeed site">
	<?php if ( ! isset( $_GET['inside-newdash'] ) ) : ?>

		<header id="sitehead" class="site-header">
			<?php do_action( 'supportforums_header_nav' ); ?>
		</header><!-- #sitehead .site-header -->

	<?php endif; ?>

	<div id="main" class="main">

<?php do_action( 'h5_before_loop' ); ?>
