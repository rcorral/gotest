<?php
return;
if ( Helper::is_home() ) {
	return;
}

ob_start(); ?>
		<aside class="sidebar col-md-2">
			merp
		</aside>
<?php return ob_get_clean(); ?>