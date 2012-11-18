<?php
/**
 * @copyright	Copyright (C) 2012 Rafael Corral. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('behavior.modal');

$user = JFactory::getUser();
$listOrder = $this->escape( $this->state->get( 'list.ordering' ) );
$listDirn = $this->escape( $this->state->get( 'list.direction' ) );
$loggeduser = JFactory::getUser();
?>

<form action="<?php echo JRoute::_('index.php?option=com_tests&view=sessions');?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
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

	<table class="adminlist">
		<thead>
			<tr>
				<th width="1%">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th>
					<?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
				</th>
				<th width="5%">
					<?php echo JHtml::_('grid.sort', 'Active', 'a.is_active', $listDirn, $listOrder); ?>
				</th>
				<th width="15%">
					<?php echo JHtml::_('grid.sort', 'JDATE', 'a.date', $listDirn, $listOrder); ?>
				</th>
				<th width="8%">
					Tests taken
				</th>
				<th width="8%"></th>
				<th width="1%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="15">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php foreach ( $this->items as $i => $item ) :
			$item->max_ordering = 0; //??
			$ordering	= ($listOrder == 'a.ordering');
			$canEdit	= $user->authorise( 'core.edit', 'com_tests.test_edit.' . $item->id );
			$canCheckin	= $user->authorise( 'core.manage', 'com_checkin' );
			$canEditOwn	= $user->authorise( 'core.edit.own', 'com_tests.test_edit.' . $item->id );
			$canChange	= $user->authorise( 'core.edit.state', 'com_tests.test_edit.' . $item->id );
			?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center">
					<?php echo JHtml::_( 'grid.id', $i, $item->id ); ?>
				</td>
				<td>
					<?php echo $this->escape( $item->title ); ?>
					<?php if ( $item->is_active ) : ?>
					<a href="<?php echo JRoute::_( 'index.php?option=com_tests&view=test&test_id=' . $item->test_id . '&tmpl=component&unique_id=' . $item->unique_id );?>" target="_blank">[administer]</a>
					<?php endif; ?>
				</td>
				<td class="center">
					<?php
					// Ugly hack ;)
					echo str_replace( array( '.publish', '.unpublish' ),
						array( '.activate', '.deactivate' ),
						JHtml::_('jgrid.published', $item->is_active, $i, 'sessions.', $canChange, 'cb' ) ); ?>
				</td>
				<td class="center">
					<?php echo date( 'm/d/Y g:ia', strtotime( $item->date ) ); ?>
				</td>
				<td class="center">
					<?php echo $item->count; ?>
				</td>
				<td class="center">
					<a href="index.php?option=com_tests&amp;view=session_results&amp;id=<?php echo $item->id; ?>" target="_blank">Download</a>
				</td>
				<td class="center">
					<?php echo (int) $item->id; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
