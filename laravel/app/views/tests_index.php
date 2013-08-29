<?php
?>
<div>
	<table class="tests-list">
		<thead>
			<tr>
				<th>Title</th>
				<th>Sub title</th>
				<th>Subject</th>
				<th>Administer</th>
				<th>Delete</th>
			</tr>
		</thead>
		<tbody>
			<?php if ( !empty($tests) ): foreach ( $tests as $test ): ?>
			<tr>
				<td><a href="<?php echo Url::route('tests.edit', $test->id); ?>"><?php echo $test->title; ?></a></td>
				<td><?php echo $test->sub_title; ?></td>
				<td><?php echo $test->category_title; ?></td>
				<td>A</td>
				<td><a href="<?php echo Url::route('tests.destroy', $test->id); ?>" class="btn btn-danger js-delete">x</a></td>
			</tr>
			<?php endforeach; else: ?>
			<tr>
				<td colspan="4"><a href="<?php echo Url::route('tests.create'); ?>">Create a test</a></td>
			</tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>