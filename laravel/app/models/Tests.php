<?php

class Tests extends Eloquent
{
	public static function get_tests()
	{
		$prefix = Helper::get_db_prefix();

		$query = Test::
			select('test_tests.*')
			->addSelect('test_categories.name AS category_title')
			->addSelect(DB::raw("COUNT({$prefix}test_sessions.`test_id`) as `hits`"))
			->join('test_categories', 'test_categories.id', '=', 'test_tests.catid')
			->join('test_sessions', 'test_sessions.test_id', '=', 'test_tests.id', 'left')
			->where('test_tests.published', (int) Input::get('published', 1))
			->where('test_tests.created_by', Helper::get_current_user()->id)
			->groupBy('test_tests.id')
			;


		// Filter by search in title.
		// $search = Input::get('search');
		if ( false && !empty($search) ) // Disabled for now
		{
			if ( stripos($search, 'id:') === 0 )
			{
				$query->where('test_tests.id = ' . (int) substr($search, 3));
			} else {
				$search = $this->_db->Quote('%'.$this->_db->escape($search, true).'%');
				$query->where('(test_tests.`title` LIKE ' . $search . ' OR test_tests.alias LIKE ' . $search . ')');
			}
		}

		// Filter category id
		// $baselevel = 1;
		// $catid = $this->getState('filter.category_id');
		if ( false && $catid && is_numeric($catid) ) // Disabled for now
		{
			$cat_tbl = JTable::getInstance('Category', 'JTable');
			$cat_tbl->load($catid);
			$rgt = $cat_tbl->rgt;
			$lft = $cat_tbl->lft;
			$baselevel = (int) $cat_tbl->level;
			$query->where('test_categories.lft >= '.(int) $lft);
			$query->where('test_categories.rgt <= '.(int) $rgt);
		}

		// Filter on the level.
		if ( false && $level = $this->getState('filter.level') )
		{
			$query->where('test_categories.level <= ' . ((int) $level + (int) $baselevel - 1));
		}

		// Add the list ordering clause.
		// $order_col  = $this->state->get('list.ordering', 'test_tests.title');
		// $order_dirn = $this->state->get('list.direction', 'asc');

		$query->orderBy('test_tests.title', 'asc');

		return $query->paginate(Helper::paginate_by());
	}
}