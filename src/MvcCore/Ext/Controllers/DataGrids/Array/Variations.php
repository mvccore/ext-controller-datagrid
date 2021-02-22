<?php

class Grid_Array_Variations
{
	public static function get ($array) {
		// initialize by adding the empty set
		$results = array(array());

		foreach ($array as $element)
			foreach ($results as $combination)
				array_push($results, array_merge(array($element), $combination));

		return $results;
	}

}