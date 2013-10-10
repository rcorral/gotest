<?php
$doc = Document::get_instance();
$tmpl_path = dirname(__FILE__);
$content_span = 12;
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
<body class="simple">
<div id="wrap">
	<div class="container">
		<?php if ( $error ): ?>
		<div class="row">
			<div class="error-msg span<?php echo $content_span; ?>"><?php echo $error; ?></div>
		</div>
		<?php endif; ?>

		<div class="row">
			<section class="content span<?php echo $content_span; ?>">
				<?php echo $contents; ?>
			</section>
		</div>
	</div>
</div>
<div>
	<div class="modal fade" id="modal-container" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"></div></div></div>
</div>

<?php echo $doc->get_footer(); ?>
</body>
</html>
