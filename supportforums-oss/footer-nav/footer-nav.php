<nav class="footer-navigation" aria-label="Footer Menu">
	<div class="menu-footer-container">
		<?php
			wp_nav_menu(
				[
					'container' => false,
					'menu_class' => 'spf-footer-nav',
					'theme_location' => 'supportforums_footer_nav'
				]
			);
		?>
	</div>
</nav><!-- .footer-navigation -->

