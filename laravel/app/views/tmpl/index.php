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
	<style type="text/css">
		body { padding-top: 60px; padding-bottom: 40px; }
	</style>
</head>
<body>
<?php include $tmpl_path . '/nav.php'; ?>

<div class="container-fluid">
	<div class="row-fluid">
		<?php echo $sidebarl; ?>

		<section class="content span<?php echo $content_span; ?>">
			<?php echo $contents; ?>
		</section>

		<?php echo $sidebarr; ?>
	</div>

<?php include $tmpl_path . '/footer.php'; ?>
</div>

<?php echo $doc->get_footer(); ?>
</body>
</html>
