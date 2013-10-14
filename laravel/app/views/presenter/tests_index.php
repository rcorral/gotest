<div>
	<table class="tests-list table table-striped table-hover">
		<thead>
			<tr>
				<th>Title</th>
				<th width="20%">Sub title</th>
				<th width="20%">Subject</th>
				<th width="150px">Administer</th>
				<th width="60px">Delete</th>
			</tr>
		</thead>
		<tbody>
			<?php if ( !empty($tests) ): foreach ( $tests as $test ): ?>
			<tr>
				<td><a href="<?php echo Url::route('tests.edit', $test->id); ?>"><?php echo $test->title; ?></a></td>
				<td><?php echo $test->sub_title; ?></td>
				<td><?php echo $test->category_title; ?></td>
				<td><a href="<?php echo Url::route('test', array('id' => $test->id, 'name' => $test->alias)); ?>" class="btn btn-default" target="_blank">Start Session</a></td>
				<td align="center"><a href="<?php echo Url::route('tests.destroy', $test->id); ?>" class="btn btn-danger js-delete">x</a></td>
			</tr>
			<?php endforeach; else: ?>
			<tr>
				<td colspan="4"><a href="<?php echo Url::route('tests.create'); ?>">Create a test</a></td>
			</tr>
			<?php endif; ?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="15">
					<?php echo $tests->links(); ?>
				</td>
			</tr>
		</tfoot>
	</table>
</div>