<?php
$doc = Document::get_instance();
$tmpl_path = dirname(__FILE__);
$content_span = 12;
$sidebarl = include $tmpl_path . '/sidebarl.php';
$sidebarr = include $tmpl_path . '/sidebarr.php';

if ( $sidebarl ) $content_span -= 2;
if ( $sidebarr ) $content_span -= 2;
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<?php echo $doc->get_head(); ?>
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
	<script src="/js/html5shiv.js"></script>
	<script src="/js/respond.min.js"></script>
<![endif]-->
</head>
<body>
<?php echo $doc->google_analytics(); ?>
<?php include $tmpl_path . '/nav.php'; ?>

<div id="wrap">
	<div class="container">
		<?php if ( $error ): ?>
		<div class="row">
			<div class="error-msg col-xs-<?php echo $content_span; ?> col-sm-<?php echo $content_span; ?> col-md-<?php echo $content_span; ?> col-lg-<?php echo $content_span; ?>"><?php echo $error; ?></div>
		</div>
		<?php endif; ?>

		<div class="row">
			<?php echo $sidebarl; ?>

			<section class="content col-xs-<?php echo $content_span; ?> col-sm-<?php echo $content_span; ?> col-md-<?php echo $content_span; ?> col-lg-<?php echo $content_span; ?>">
				<?php echo $contents; ?>
			</section>

			<?php echo $sidebarr; ?>
		</div>

	</div>
</div>
<?php include $tmpl_path . '/footer.php'; ?>
<div>
	<div class="modal fade" id="modal-container" tabindex="-1" role="dialog" aria-labelledby="modal-label" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"></div></div></div>
</div>

<?php echo $doc->get_footer(); ?>
</body>
</html>
