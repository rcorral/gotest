<?php
return;
if ( Helper::is_home() ) {
	return;
}

ob_start(); ?>
		<aside class="sidebar col-xs-2 col-sm-2 col-md-2 col-lg-2">
			merp
		</aside>
<?php return ob_get_clean(); ?>