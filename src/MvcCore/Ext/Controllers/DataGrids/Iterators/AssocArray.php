<?php

namespace MvcCore\Ext\Controllers\DataGrids\Iterators;

abstract class	AssocArray 
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
	 * Iterator associative store keys.
	 * @var \string[]
	 */
	protected $keys = [];

	/**
	 * Create iterator instance by given array.
	 * @param array $array 
	 */
	public function __construct (array & $array) {
		$this->position = 0;
		$this->array = & $array;
		$this->keys = array_keys($array);
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
	 * @return string
	 */
	public function key () {
		return $this->keys[$this->position];
	}

	/**
	 * Return boolean if iterator could continue to next position.
	 * @return bool
	 */
	public function valid () {
		if (!array_key_exists($this->position, $this->keys)) return FALSE;
		$key = $this->keys[$this->position];
		return array_key_exists($key, $this->array);
	}

	/**
	 * Return if iterator has defined any non `NULL` value in given `$offset`.
	 * @param  string $offset 
	 * @return bool
	 */
	public function __isset ($offset) {
		return isset($this->array[$offset]);
	}

	/**
	 * Return if iterator has defined any value in given `$offset` including `NULL` value.
	 * @param  string $offset
	 * @return bool
	 */
	public function offsetExists ($offset) {
		$offsetStr = (string) $offset;
		return array_key_exists($offsetStr, $this->array);
	}

	/**
	 * Unset value in iterator under given `$offset`.
	 * @param  string $offset 
	 * @return void
	 */
	public function offsetUnset ($offset) {
		$offsetStr = (string) $offset;
		unset($this->array[$offsetStr]);
		$this->keys = array_keys($this->array);
	}
	

	#region Template methods:

	/**
	 * Return current iterator value.
	 * @return mixed
	 */
	public function current () {
		$key = $this->keys[$this->position];
		return $this->array[$key];
	}

	/**
	 * Set given `$value` into iterator under `$offset`.
	 * @param  string $offset 
	 * @param  mixed  $value 
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
	 * @return mixed
	 */
	public function offsetGet ($offset) {
		$offsetStr = (string) $offset;
		return array_key_exists($offsetStr, $this->array)
			? $this->array[$offsetStr] 
			: NULL;
	}

	#endregion
}