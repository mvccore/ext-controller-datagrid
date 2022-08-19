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

class	Columns
extends \MvcCore\Ext\Tools\Collections\Map {
	
	/**
	 * Reverse keys map, keyed by grid columns properties names,
	 * values are keys into local iterator array store.
	 * @var array
	 */
	protected $propsNamesMap = [];

	/**
	 * Reverse keys map, keyed by grid columns database columns names,
	 * values are keys into local iterator array store.
	 * @var array
	 */
	protected $dbColumnsNamesMap = [];

	/**
	 * Create grid columns iterator instance by given array.
	 * @param array<string, \MvcCore\Ext\Controllers\DataGrids\Configs\Column> $array 
	 */
	public function __construct (array & $array) {
		parent::__construct($array);
		foreach ($array as $columnUrlName => $gridColumnConfig) {
			/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\Column $gridColumnConfig */
			$this->propsNamesMap[$gridColumnConfig->GetPropName()] = $columnUrlName;
			$this->dbColumnsNamesMap[$gridColumnConfig->GetDbColumnName()] = $columnUrlName;
		}
	}
	
	/**
	 * Get grid column config by column config property name.
	 * @param  string $propName 
	 * @param  bool   $thrownException
	 * @throws \InvalidArgumentException Grid doesn't contain column config with code property name `{$propName}`.
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column|NULL
	 */
	public function GetByPropName ($propName, $thrownException = TRUE) {
		if (!isset($this->propsNamesMap[$propName])) {
			if (!$thrownException) return NULL;
			throw new \InvalidArgumentException(
				"Datagrid doesn't contain column config with code property name `{$propName}`."
			);
		}
		$key = $this->propsNamesMap[$propName];
		return $this->array[$key];
	}
	
	/**
	 * Get grid column config by column config database column name.
	 * @param  string $dbColumnName 
	 * @throws \InvalidArgumentException Grid doesn't contain column config with database column name `{$dbColumnName}`.
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column|NULL
	 */
	public function GetByDbColumnName ($dbColumnName, $thrownException = TRUE) {
		if (!isset($this->dbColumnsNamesMap[$dbColumnName])) {
			if (!$thrownException) return NULL;
			throw new \InvalidArgumentException(
				"Datagrid doesn't contain column config with database column name `{$dbColumnName}`."
			);
		}
		$key = $this->dbColumnsNamesMap[$dbColumnName];
		return $this->array[$key];
	}

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