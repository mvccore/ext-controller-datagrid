<?php

namespace MvcCore\Ext\Controllers\DataGrids\Configs;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class		Column
implements	\MvcCore\Ext\Controllers\DataGrids\Configs\IColumn{
	
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
	 * Property type(s), necessary for automatic formating.
	 * @var \string[]|NULL
	 */
	protected $types = NULL;

	/**
	 * Property automatic formating arguments.
	 * @var array|NULL
	 */
	protected $format = NULL;

	/**
	 * Property automatic formating view helper name.
	 * @var string|NULL
	 */
	protected $viewHelper = NULL;
	

	/**
	 * Create datagrid column config item.
	 * @param string|NULL      $propName     Data grid model property name.
	 * @param string|NULL      $dbColumnName Database column name. If `NULL`, `$propName` is used.
	 * @param string|NULL      $humanName    Data grid visible column name. If `NULL`, `$propName` is used.
	 * @param string|NULL      $urlName      Data grid url column name to define ordering or filtering. 
	 *                                       If `NULL`, `$propName` is used.
	 * @param string|bool|NULL $order        Default ordering definition with values `ASC | DESC` 
	 *                                       or `TRUE | FALSE` to enable/disable ordering.
	 * @param bool             $filter       Boolean to allow filtering.
	 * @param \string[]|NULL   $types        Property type(s), necessary for automatic formating.
	 * @param array|NULL       $format       Property automatic formating arguments.
	 * @param string|NULL      $viewHelper   Property automatic formating view helper name.
	 */
	public function __construct ($propName = NULL, $dbColumnName = NULL, $humanName = NULL, $urlName = NULL, $order = FALSE, $filter = FALSE, $types = NULL, $format = NULL, $viewHelper = NULL) {
		$propNameHasValue = $propName !== NULL;
		if ($propNameHasValue) 
			$this->propName = $propName;

		if ($dbColumnName !== NULL) {
			$this->dbColumnName = $dbColumnName;
		} else if ($propNameHasValue) {
			$this->dbColumnName = $propName;
		}

		if ($humanName !== NULL) {
			$this->humanName = $humanName;
		} else if ($propNameHasValue) {
			$this->humanName = $propName;
		}

		if ($urlName !== NULL) {
			$this->urlName = $urlName;
		} else if ($propNameHasValue) {
			$this->urlName = $propName;
		}

		if ($order !== NULL)		$this->order		= $order;
		if ($filter !== NULL)		$this->filter		= $filter;
		if ($types !== NULL)		$this->types		= $types;
		if ($format !== NULL)		$this->format		= $format;
		if ($viewHelper !== NULL)	$this->viewHelper	= $viewHelper;
	}

	
	/**
	 * Get data grid model property name.
	 * @return string|NULL
	 */
	public function GetPropName () {
		return $this->propName;
	}
	/**
	 * Set data grid model property name.
	 * @param  string|NULL $propName
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetPropName ($propName) {
		$this->propName = $propName;
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
	 * Get property type(s), necessary for automatic formating.
	 * @return \string[]|NULL
	 */
	public function GetTypes () {
		return $this->types;
	}
	/**
	 * Set property type(s), necessary for automatic formating.
	 * @param  \string[] $types
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetTypes ($types) {
		$this->types = $types;
		return $this;
	}
	
	/**
	 * Get property automatic formating arguments.
	 * @return array|NULL
	 */
	public function GetFormat () {
		return $this->format;
	}
	/**
	 * Set property automatic formating arguments.
	 * @param  array $format
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetFormat ($format) {
		$this->format = $format;
		return $this;
	}
	
	/**
	 * Get property automatic formating view helper name.
	 * @return array|NULL
	 */
	public function GetViewHelper () {
		return $this->viewHelper;
	}
	/**
	 * Set property automatic formating view helper name.
	 * @param  array $viewHelper
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetViewHelper ($viewHelper) {
		$this->viewHelper = $viewHelper;
		return $this;
	}
}
