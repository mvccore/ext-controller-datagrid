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

class Columns extends \MvcCore\Ext\Controllers\DataGrids\Iterators\AssocArray {

	/**
	 * Return current iterator value.
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function current () {
		$key = $this->keys[$this->position];
		return $this->array[$key];
	}

	/**
	 * Set given `$value` into iterator under `$offset`.
	 * @param  string                                            $offset 
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\Column $value 
	 * @return void
	 */
	public function offsetSet ($offset = NULL, $value = NULL) {
		if ($offset === NULL) {
			$this->array[] = $value;
		} else {
			$offsetStr = (string) $offset;
			$this->array[$offsetStr] = $value;
		}
		$this->keys = array_keys($this->array);
	}

	/**
	 * Get iterator value under given `$offset`.
	 * @param  string $offset 
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function offsetGet ($offset) {
		$offsetStr = (string) $offset;
		return array_key_exists($offsetStr, $this->array)
			? $this->array[$offsetStr] 
			: NULL;
	}

}