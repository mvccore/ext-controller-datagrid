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

namespace MvcCore\Ext\Controllers\DataGrids\Configs;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class		Column
implements	\MvcCore\Ext\Controllers\DataGrids\Configs\IColumn,
			\JsonSerializable {
	
	const PHP_DOCS_TAG_NAME = '@datagrid';
	
	/**
	 * Model PHP code propery name.
	 * @var string|NULL
	 */
	protected $propName = NULL;
	
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
	 * URL column name when sorting or filtering.
	 * @var string|NULL
	 */
	protected $urlName = NULL;

	/**
	 * Datagrid column index, starting with `0`, optional.
	 * @var int|NULL
	 */
	protected $columnIndex = NULL;
	
	/**
	 * Default sorting definition with values `ASC | DESC` 
	 * or `TRUE | FALSE` to enable/disable sorting.
	 * @var string|bool|NULL
	 */
	protected $sort = FALSE;

	/**
	 * Boolean to allow column filtering.
	 * @var int|bool
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
	 * Column width attribute for head cell, if value.
	 * Width is used only for table grid type.
	 * @var string|int|float|NULL
	 */
	protected $width = NULL;

	/**
	 * Column additional css classes for head cell and body cell.
	 * @var \string[]
	 */
	protected $cssClasses = [];
	
	/**
	 * Force column disable.
	 * @var bool|NULL
	 */
	protected $disabled = NULL;
	

	/**
	 * Create datagrid column config item.
	 * @param string|NULL           $propName     Data grid model property name.
	 * @param string|NULL           $dbColumnName Database column name. If `NULL`, `$propName` is used.
	 * @param string|NULL           $humanName    Data grid visible column name. If `NULL`, `$propName` is used.
	 * @param string|NULL           $urlName      Data grid url column name to define sorting or filtering. 
	 *                                            If `NULL`, `$propName` is used.
	 * @param int|NULL              $columnIndex  Datagrid column index, starting with `0`, optional.
	 * @param string|bool|NULL      $sort         Default sorting definition with values `ASC | DESC` 
	 *                                            or `TRUE | FALSE` to enable/disable sorting.
	 * @param int|bool              $filter       Filtering mode flags to allow specify operators 
	 *                                            for each column or boolean to allow filtering only.
	 * @param \string[]|NULL        $types        Property type(s), necessary for automatic formating.
	 * @param array|NULL            $format       Property automatic formating arguments.
	 * @param string|NULL           $viewHelper   Property automatic formating view helper name.
	 * @param string|int|float|NULL $width        Column width attribute for head cell, if value.
	 *                                            Width is used only for table grid type.
	 * @param \string[]             $cssClasses   Column additional css classes for head cell and body cell.
	 * @param bool|NULL             $disabled     Force column disable.
	 */
	public function __construct (
		$propName = NULL, 
		$dbColumnName = NULL, 
		$humanName = NULL, 
		$urlName = NULL, 
		$columnIndex = NULL,
		$sort = NULL, 
		$filter = NULL, 
		$types = NULL, 
		$format = NULL, 
		$viewHelper = NULL,
		$width = NULL,
		$cssClasses = NULL,
		$disabled = NULL
	) {
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
		
		if ($columnIndex !== NULL)	$this->columnIndex	= $columnIndex;
		if ($sort !== NULL)			$this->sort			= $sort;
		if ($filter !== NULL)		$this->filter		= $filter;
		if ($types !== NULL)		$this->types		= $types;
		if ($format !== NULL)		$this->format		= $format;
		if ($viewHelper !== NULL)	$this->viewHelper	= $viewHelper;
		if ($width !== NULL)		$this->width		= $width;
		if ($cssClasses !== NULL)	$this->cssClasses	= $cssClasses;
		if ($disabled !== NULL)		$this->disabled		= $disabled;
	}


	/**
	 * Return properties and values to serialize.
	 * @return mixed
	 */
	public function jsonSerialize () {
		return get_object_vars($this);
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
	 * Get URL column name when sorting or filtering.
	 * @return string|NULL
	 */
	public function GetUrlName () {
		return $this->urlName;
	}
	/**
	 * Set URL column name when sorting or filtering.
	 * @param  string|NULL $urlName
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetUrlName ($urlName) {
		$this->urlName = $urlName;
		return $this;
	}

	/**
	 * Get datagrid column index, starting with `0`, optional.
	 * @return int|NULL
	 */
	public function GetColumnIndex () {
		return $this->columnIndex;
	}
	/**
	 * Set datagrid column index, starting with `0`, optional.
	 * @param  int|NULL $columnIndex
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetColumnIndex ($columnIndex) {
		$this->columnIndex = $columnIndex;
		return $this;
	}

	/**
	 * Get default sorting definition with values `ASC | DESC` 
	 * or `TRUE | FALSE` to enable/disable sorting.
	 * @return string|bool|NULL
	 */
	public function GetSort () {
		return $this->sort;
	}
	/**
	 * Set default sorting definition with values `ASC | DESC` 
	 * or `TRUE | FALSE` to enable/disable sorting.
	 * @param  string|bool|NULL $sort
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetSort ($sort) {
		$this->sort = $sort;
		return $this;
	}

	/**
	 * Get filtering mode flags to allow specify operators 
	 * for each column or boolean to allow filtering only.
	 * @return int|bool
	 */
	public function GetFilter () {
		return $this->filter;
	}
	/**
	 * Set filtering mode flags to allow specify operators 
	 * for each column or boolean to allow filtering only.
	 * @param  int|bool $filter
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

	/**
	 * Get column width attribute for head cell, if value.
	 * Width is used only for table grid type.
	 * @return string|NULL
	 */
	public function GetWidth () {
		if ($this->width === NULL || is_string($this->width)) 
			return $this->width;
		return rtrim(rtrim(number_format($this->width, 3, '.', ''), '0'), '.');
	}
	/**
	 * Set column width attribute for head cell, if value.
	 * Width is used only for table grid type.
	 * @param  string|int|float|NULL $width
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetWidth ($width) {
		$this->width = $width;
		return $this;
	}

	/**
	 * Get column additional css classes for head cell and body cell.
	 * @return \string[]|NULL
	 */
	public function GetCssClasses () {
		return $this->cssClasses;
	}
	/**
	 * Set column additional css classes for head cell and body cell.
	 * @param  \string[] $cssClasses
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetCssClasses ($cssClasses) {
		$this->cssClasses = $cssClasses;
		return $this;
	}
	
	/**
	 * Get if column is disabled forcely.
	 * @return bool|NULL
	 */
	public function GetDisabled () {
		return $this->disabled;
	}
	/**
	 * Set disabled column forcely.
	 * @param  bool $disabled
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetDisabled ($disabled) {
		$this->disabled = $disabled;
		return $this;
	}
	
}
