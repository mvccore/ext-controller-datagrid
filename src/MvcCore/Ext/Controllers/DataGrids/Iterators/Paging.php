<?php

/**
 * MvcCore
 *
 * This source file is subject to the BSD 3 License
 * For the full copyright and license information, please view
 * the LICENSE.md file that are distributed with this source code.
 *
 * @copyright	Copyright (c) 2016 Tom Flidr (https://github.com/mvccore)
 * @license		https://mvccore.github.io/docs/mvccore/5.0.0/LICENSE.md
 */

namespace MvcCore\Ext\Controllers\DataGrids\Iterators;

class	Paging 
extends	\MvcCore\Ext\Tools\Collections\Set {

	/**
	 * Return current iterator value.
	 * @return \MvcCore\Ext\Controllers\DataGrids\Paging\Item
	 */
	public function current () {
		return $this->array[$this->position];
	}

	/**
	 * Set given `$value` into iterator under `$offset`.
	 * @param  int                                            $offset 
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Paging\Item $value 
	 * @return void
	 */
	public function offsetSet ($offset = NULL, $value = NULL) {
		$offsetInt = intval($offset);
		if ($offset === NULL) {
			$this->array[] = $value;
		} else {
			$this->array[$offsetInt] = $value;
		}
	}

	/**
	 * Get iterator value under given `$offset`.
	 * @param  int $offset 
	 * @return \MvcCore\Ext\Controllers\DataGrids\Paging\Item
	 */
	public function offsetGet ($offset) {
		$offsetInt = intval($offset);
		return array_key_exists($offsetInt, $this->array)
			? $this->array[$offsetInt] 
			: NULL;
	}

}