<form action="<?php echo URL::to('sessions.index'); ?>" method="post" name="adminForm" id="adminForm">
	<?php /*
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo Lang::get('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_CONTENT_FILTER_SEARCH_DESC'); ?>" />

			<button type="submit" class="btn"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
		<div class="filter-select fltrt">
			<select name="filter_published" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true);?>
			</select>
		</div>
	</fieldset>
	<div class="clr"> </div>
	*/ ?>

	<table class="sessions-list">
		<thead>
			<tr>
				<th width="1%">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo Lang::get('Check All'); ?>" onclick="core.check_all(this)" />
				</th>
				<th>Title</th>
				<th>Active</th>
				<th>Date</th>
				<th>Tests taken</th>
				<th>Download Results</th>
				<th>Delete</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ( $sessions as $i => $session ) : ?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center">
					<?php echo Form::cb_id($i, $session->id); ?>
				</td>
				<td>
					<?php echo $session->title; // TODO: Prevent xss here, escape this ?>
					<?php if ( $session->is_active ) : ?>
					<!--<a href="<?php echo URL::to('take_test', array('id' => $session->test_id, 'unique' => substr($session->unique_id, 0, 6))); ?>" target="_blank">[administer]</a>-->
					<?php endif; ?>
				</td>
				<td class="center">
					<?php echo Form::item_state($session->is_active, $session->id, 'sessions'); ?>
				</td>
				<td class="center">
					<?php echo date( 'm/d/Y g:ia', strtotime( $session->created_at ) ); ?>
				</td>
				<td class="center">
					<?php echo $session->count; ?>
				</td>
				<td class="center">
					<a href="<?php echo Url::route('sessions.show', $session->id); ?>" target="_blank">Download</a>
				</td>
				<td><a href="<?php echo Url::route('sessions.destroy', $session->id); ?>" class="btn btn-danger js-delete">x</a></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="15">
					<?php echo $sessions->links(); ?>
				</td>
			</tr>
		</tfoot>
	</table>

	<div>
		<?php echo Form::csrf(); ?>
	</div>
</form>
