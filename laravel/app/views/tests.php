<?php
?>
<div>
	<table>
		<thead>
			<td>
				<th>Title</th>
				<th>Sub title</th>
				<th>Subject</th>
				<th></th>
			</td>
		</thead>
		<tbody>
			<?php if ( !empty($tests) ): foreach ( $tests as $test ): ?>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<?php endforeach; else: ?>
			<tr>
				<td colspan="4"><a href="<?php echo Url::route('tests.create'); ?>">Create a test</a></td>
			</tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>