<?php
defined('JPATH_PLATFORM') or die;

class TestModelTest extends JModelDatabase
{
	public function get_test()
	{
		$app = JFactory::getApplication();

		$query = $this->db->getQuery(true)
			->select( 't.*' )
			->from( '`tests` AS t' )
			->where( 't.`id` = ' . (int) $app->input->get( 'test_id' ) )
			;
		return $this->db->setQuery( $query )->loadObject();
	}
}