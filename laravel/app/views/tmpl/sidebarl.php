<?php
if ( Helper::is_home() ) {
	return;
}

ob_start(); ?>
		<aside class="sidebar span2">
			merp
		</aside>
<?php return ob_get_clean(); ?>