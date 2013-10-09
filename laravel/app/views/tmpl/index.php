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
<?php include $tmpl_path . '/nav.php'; ?>

<div id="wrap">
	<div class="container">
		<?php if ( $error ): ?>
		<div class="row">
			<div class="error-msg span<?php echo $content_span; ?>"><?php echo $error; ?></div>
		</div>
		<?php endif; ?>

		<div class="row">
			<?php echo $sidebarl; ?>

			<section class="content span<?php echo $content_span; ?>">
				<?php echo $contents; ?>
			</section>

			<?php echo $sidebarr; ?>
		</div>

	</div>
</div>
<?php include $tmpl_path . '/footer.php'; ?>
<div>
	<div id="modal-container" class="modal hide fade" role="dialog" aria-hidden="true"></div>
</div>

<?php echo $doc->get_footer(); ?>
</body>
</html>
