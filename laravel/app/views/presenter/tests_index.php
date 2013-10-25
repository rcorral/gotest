<div>
	<table class="tests-list table table-striped table-hover">
		<thead>
			<tr>
				<th>Title</th>
				<th width="20%">Sub title</th>
				<th width="20%">Subject</th>
				<th width="9%">Interactive</th>
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
				<td><?php echo Form::test_interactive($test->interactive, $test->id); ?></td>
				<td><a href="<?php echo Url::route('test', array('id' => $test->id, 'name' => $test->alias)); ?>" class="btn btn-primary js-start-session" target="_blank">Start Session</a></td>
				<td align="center"><button type="button" href="<?php echo Url::route('tests.destroy', $test->id); ?>" class="btn btn-danger btn-sm js-delete" title="Delete"><span class="glyphicon glyphicon-remove"></span></button></td>
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