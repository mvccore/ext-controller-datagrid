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

abstract class	SequenceArray 
implements		\Iterator, \ArrayAccess, \Countable {
	
	/**
	 * @var int
	 */
	protected $position = 0;

	/**
	 * Iterator store.
	 * @var array
	 */
	protected $array = [];

	/**
	 * Create iterator instance by given array.
	 * @param array $array 
	 */
	public function __construct (array & $array) {
		$this->position = 0;
		$this->array = & $array;
	}
	
	/**
	 * Return internal store.
	 * @return array
	 */
	public function & GetArray () {
		return $this->array;
	}
	
	/**
	 * Return count of iterator items.
	 * @return int
	 */
	public function count() {
		return count($this->array);
	}
	
	/**
	 * Rewind iterator to the beginning.
	 * @return void
	 */
	public function rewind () {
		$this->position = 0;
	}
	
	/**
	 * Move iterator to next position.
	 * @return void
	 */
	public function next() {
		++$this->position;
	}
	
	/**
	 * Return iterator index value.
	 * @return int
	 */
	public function key () {
		return $this->position;
	}

	/**
	 * Return boolean if iterator could continue to next position.
	 * @return bool
	 */
	public function valid () {
		return array_key_exists($this->position, $this->array);
	}

	/**
	 * Return if iterator has defined any non `NULL` value in given `$offset`.
	 * @param  int  $offset 
	 * @return bool
	 */
	public function __isset ($key) {
		$keyInt = intval($key);
		return isset($this->array[$keyInt]);
	}

	/**
	 * Return if iterator has defined any value in given `$offset` including `NULL` value.
	 * @param  int  $offset
	 * @return bool
	 */
	public function offsetExists ($offset) {
		$offsetInt = intval($offset);
		return array_key_exists($offsetInt, $this->array);
	}
	
	/**
	 * Unset value in iterator under given `$offset`.
	 * @param  int  $offset 
	 * @return void
	 */
	public function offsetUnset ($offset) {
		$offsetInt = intval($offset);
		unset($this->array[$offsetInt]);
	}
	

	#region Template methods:
	
	/**
	 * Return current iterator value.
	 * @return mixed
	 */
	public function current () {
		return $this->array[$this->position];
	}
	
	/**
	 * Set given `$value` into iterator under `$offset`.
	 * @param  int|NULL $offset 
	 * @param  mixed    $value 
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
	 * @param  int|NULL $offset 
	 * @return mixed
	 */
	public function offsetGet ($offset) {
		$offsetInt = intval($offset);
		return array_key_exists($offsetInt, $this->array)
			? $this->array[$offsetInt] 
			: NULL;
	}

	#endregion
}