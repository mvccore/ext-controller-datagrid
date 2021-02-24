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
	 * Human readable name used in grid heading.
	 * @return string|NULL
	 */
	public function GetHumanName () {
		return $this->humanName;
	}
	
	/**
	 * Database column name.
	 * @return string|NULL
	 */
	public function GetDbColumnName () {
		return $this->dbColumnName;
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
	 * Get boolean to allow column filtering.
	 * @return bool
	 */
	public function GetFilter () {
		return $this->filter;
	}

	/**
	 * Create datagrid column config item.
	 * @param string|NULL      $humanName    Data grid visible column name.
	 * @param string|NULL      $dbColumnName Database column name.
	 * @param string|bool|NULL $order        Default ordering definition with values `ASC | DESC` 
	 *                                       or `TRUE | FALSE` to enable/disable ordering.
	 * @param bool             $filter       Boolean to allow filtering.
	 * @param string|NULL      $urlName      Data grid url column name to define ordering or filtering.
	 */
	public function __construct ($humanName = NULL, $dbColumnName = NULL, $order = FALSE, $filter = FALSE, $urlName = NULL) {
		if ($humanName !== NULL)	$this->humanName	= $humanName;
		if ($dbColumnName !== NULL)	$this->dbColumnName	= $dbColumnName;
		if ($order !== NULL)		$this->order		= $order;
		if ($filter !== NULL)		$this->filter		= $filter;
		if ($urlName !== NULL)		$this->urlName		= $urlName;
	}

}
