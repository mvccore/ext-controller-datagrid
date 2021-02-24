<?php

namespace MvcCore\Ext\Controllers\DataGrids\Configs;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Column {
	
	const PHP_DOCS_TAG_NAME = '@datagrid';

	/**
	 * Human readable name used in grid heading.
	 * @var string|NULL
	 */
	protected $humanName = NULL;
	
	/**
	 * Database column name.
	 * @var string|NULL
	 */
	protected $dbColumnName = NULL;
	
	/**
	 * URL column name when ordering or filtering.
	 * @var string|NULL
	 */
	protected $urlName = NULL;
	
	/**
	 * Default ordering definition with values `ASC | DESC` 
	 * or `TRUE | FALSE` to enable/disable ordering.
	 * @var string|bool|NULL
	 */
	protected $order = FALSE;

	/**
	 * Boolean to allow column filtering.
	 * @var bool
	 */
	protected $filter = FALSE;
	

	/**
	 * Get human readable name used in grid heading.
	 * @return string|NULL
	 */
	public function GetHumanName () {
		return $this->humanName;
	}
	/**
	 * Set human readable name used in grid heading.
	 * @param  string|NULL $humanName
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetHumanName ($humanName) {
		$this->humanName = $humanName;
		return $this;
	}
	
	/**
	 * Get database column name.
	 * @return string|NULL
	 */
	public function GetDbColumnName () {
		return $this->dbColumnName;
	}
	/**
	 * Set database column name.
	 * @param  string|NULL $dbColumnName
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetDbColumnName ($dbColumnName) {
		$this->dbColumnName = $dbColumnName;
		return $this;
	}
	
	/**
	 * Get URL column name when ordering or filtering.
	 * @return string|NULL
	 */
	public function GetUrlName () {
		return $this->urlName;
	}
	/**
	 * Set URL column name when ordering or filtering.
	 * @param  string|NULL $urlName
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetUrlName ($urlName) {
		$this->urlName = $urlName;
		return $this;
	}

	/**
	 * Get default ordering definition with values `ASC | DESC` 
	 * or `TRUE | FALSE` to enable/disable ordering.
	 * @return string|bool|NULL
	 */
	public function GetOrder () {
		return $this->order;
	}
	/**
	 * Set default ordering definition with values `ASC | DESC` 
	 * or `TRUE | FALSE` to enable/disable ordering.
	 * @param  string|bool|NULL $order
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetOrder ($order) {
		$this->order = $order;
		return $this;
	}

	/**
	 * Get boolean to allow column filtering.
	 * @return bool
	 */
	public function GetFilter () {
		return $this->filter;
	}
	/**
	 * Set boolean to allow column filtering.
	 * @param  bool $filter
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetFilter ($filter) {
		$this->filter = $filter;
		return $this;
	}

	/**
	 * Create datagrid column config item.
	 * @param string|NULL      $humanName    Data grid visible column name.
	 * @param string|NULL      $dbColumnName Database column name.
	 * @param string|NULL      $urlName      Data grid url column name to define ordering or filtering.
	 * @param string|bool|NULL $order        Default ordering definition with values `ASC | DESC` 
	 *                                       or `TRUE | FALSE` to enable/disable ordering.
	 * @param bool             $filter       Boolean to allow filtering.
	 */
	public function __construct ($humanName = NULL, $dbColumnName = NULL, $urlName = NULL, $order = FALSE, $filter = FALSE) {
		if ($humanName !== NULL)	$this->humanName	= $humanName;
		if ($dbColumnName !== NULL)	$this->dbColumnName	= $dbColumnName;
		if ($urlName !== NULL)		$this->urlName		= $urlName;
		if ($order !== NULL)		$this->order		= $order;
		if ($filter !== NULL)		$this->filter		= $filter;
	}

}
