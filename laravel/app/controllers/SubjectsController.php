<?php

class SubjectsController extends \BaseController {

	/**
	 * Create a subject
	 *
	 * @return Response
	 */
	public function store()
	{
		Helper::csrf_check();

		$subject = ucwords(strtolower(Input::get('subject', '')));
		if ( ($parent_catid = Input::get('nested_catid')) == 0 )
			$parent_catid = 1;

		try
		{
			$new_subject = $this->add($subject, $parent_catid);

			// Get the html for the nested_catid select
			$nested_catid = Form::categories('nested_catid', 0, array('default_opt' => array(0 => '')), true);

			return Response::json(array(
				'html' => Form::categories('catid', $new_subject->id),
				'nested_catid' => $nested_catid,
				'exec' => 'jQuery("#catid").html(html);core.modal_close();if(nested_catid){jQuery("select[name=\"nested_catid\"]").replaceWith(nested_catid)};')
			, 200);
		} catch (Exception $e) {
			return Response::json(array('message' => $e->getMessage()), 400);
		}
	}

	public static function add( $subject, $parent_catid = 1 )
	{
		if ( empty($subject) ) throw new Exception('Please enter a subject.');

		$root = Categories::find($parent_catid);
		$children = $root->findChildren(2);
		$new_subject = new Categories(array('name' => $subject, 'published' => 1));

		// If no children then no need to organize
		if ( empty($children) )
		{
			$new_subject->makeFirstChildOf($root);
		}
		else
		{
			// See where it should go alphabetically
			$organized = array(strtolower($subject));
			$children_keys = array();
			foreach ( $children as $key => $child )
			{
				if ( $subject == $child->name ) throw new Exception('A subject with this name already exists.');

				$organized[$child->id] = strtolower($child->name);

				// Store child by key so that it's easier to access later
				$children_keys[$child->id] = $child;

				// Remove from memory
				unset($children[$key]);
			}
			unset($children);

			asort($organized);
			reset($organized);
			$prev = 0;
			foreach ( $organized as $key => $subject_name )
			{
				if ( 0 === $key )
					// Our new subject is supposed to be at the first on the list
					if ( 0 === $prev )
						$new_subject->makeFirstChildOf($root);
					else
						$new_subject->makeNextSiblingOf($children_keys[$prev]);

				$prev = $key;
			}
		}

		return $new_subject;
	}

}