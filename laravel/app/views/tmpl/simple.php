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
</head>
<body class="simple">

<div class="wrapper container-fluid">
	<?php if ( $error ): ?>
	<div class="row-fluid">
		<div class="error-msg span<?php echo $content_span; ?>"><?php echo $error; ?></div>
	</div>
	<?php endif; ?>

	<div class="row-fluid">
		<section class="content span<?php echo $content_span; ?>">
			<?php echo $contents; ?>
		</section>
	</div>
</div>
<div>
	<div id="modal-container" class="modal hide fade" role="dialog" aria-hidden="true"></div>
</div>

<?php echo $doc->get_footer(); ?>
</body>
</html>
