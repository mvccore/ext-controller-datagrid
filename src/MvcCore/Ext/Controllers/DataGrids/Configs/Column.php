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
	 * @jsonSerialize
	 * @var string|NULL
	 */
	#[JsonSerialize]
	protected $propName = NULL;
	
	/**
	 * Grid heading human readable text.
	 * @jsonSerialize
	 * @var string|NULL
	 */
	#[JsonSerialize]
	protected $headingName = NULL;
	
	/**
	 * Grid heading title attribute, displayed on heading mouse over.
	 * @jsonSerialize
	 * @var string|NULL
	 */
	#[JsonSerialize]
	protected $title = NULL;
	
	/**
	 * Database column name.
	 * @var string|NULL
	 */
	protected $dbColumnName = NULL;
	
	/**
	 * URL column name when sorting or filtering.
	 * @jsonSerialize
	 * @var string|NULL
	 */
	#[JsonSerialize]
	protected $urlName = NULL;
	
	/**
	 * URL helper to generate cell anchor URL.
	 * Boolean to enable/disable anchor url for filtering
	 * (`TRUE` by default) or string to declare row instance
	 * model public method to generate custom URL.
	 * @var bool|string|NULL
	 */
	protected $urlHelper = TRUE;

	/**
	 * Datagrid column index, starting with `0`, optional.
	 * @jsonSerialize
	 * @var int|NULL
	 */
	#[JsonSerialize]
	protected $columnIndex = NULL;
	
	/**
	 * Default sorting definition with values `ASC | DESC` 
	 * or `TRUE | FALSE` to enable/disable sorting.
	 * @jsonSerialize
	 * @var string|bool|NULL
	 */
	#[JsonSerialize]
	protected $sort = FALSE;

	/**
	 * Boolean to allow column filtering.
	 * @jsonSerialize
	 * @var int|bool
	 */
	#[JsonSerialize]
	protected $filter = FALSE;

	/**
	 * Property type(s), necessary for automatic formating.
	 * @jsonSerialize
	 * @var \string[]|NULL
	 */
	#[JsonSerialize]
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
	 * Column initial or current width, it can be defined 
	 * as integer for pixel value, float for flex value
	 * or string including `px` or `%` units.
	 * Width is used only for table grid type.
	 * @jsonSerialize
	 * @var string|int|float|NULL
	 */
	#[JsonSerialize]
	protected $width = NULL;

	/**
	 * Column min. width, it can be defined as integer 
	 * or float for pixel value or string including `px` 
	 * or `%` units. Min. width is used only for table grid type.
	 * @jsonSerialize
	 * @var string|int|float|NULL
	 */
	#[JsonSerialize]
	protected $minWidth = NULL;

	/**
	 * Column max. width, it can be defined as integer 
	 * or float for pixel value or string including `px` 
	 * or `%` units. Min. width is used only for table grid type.
	 * @jsonSerialize
	 * @var string|int|float|NULL
	 */
	#[JsonSerialize]
	protected $maxWidth = NULL;

	/**
	 * Column additional css classes for head cell and body cell.
	 * @jsonSerialize
	 * @var \string[]
	 */
	#[JsonSerialize]
	protected $cssClasses = [];
	
	/**
	 * `TRUE` for disabled column, `FALSE|NULL` for enabled column.
	 * @jsonSerialize
	 * @var bool|NULL
	 */
	#[JsonSerialize]
	protected $disabled = NULL;
	

	/**
	 * Create datagrid column config item.
	 * @param string|NULL           $propName     Data grid model property name.
	 * @param string|NULL           $dbColumnName Database column name. If `NULL`, `$propName` is used.
	 * @param string|NULL           $headingName  Data grid visible column name. If `NULL`, `$propName` is used.
	 * @param string|NULL           $title        Grid heading title attribute, displayed on heading mouse over.
	 * @param string|NULL           $urlName      Data grid url column name to define sorting or filtering. 
	 *                                            If `NULL`, `$propName` is used.
	 * @param bool|string|NULL      $urlHelper    URL helper to generate cell anchor URL.
	 *                                            Boolean to enable/disable anchor url for filtering
	 *                                            (`TRUE` by default) or string to declare row instance
	 *                                            model public method to generate custom URL.
	 * @param int|NULL              $columnIndex  Datagrid column index, starting with `0`, optional.
	 * @param string|bool|NULL      $sort         Default sorting definition with values `ASC | DESC` 
	 *                                            or `TRUE | FALSE` to enable/disable sorting.
	 * @param int|bool              $filter       Filtering mode flags to allow specify operators 
	 *                                            for each column or boolean to allow filtering only.
	 * @param \string[]|NULL        $types        Property type(s), necessary for automatic formating.
	 * @param array|NULL            $format       Property automatic formating arguments.
	 * @param string|NULL           $viewHelper   Property automatic formating view helper name.
	 * @param string|int|float|NULL $width        Column initial or current width, it can be defined 
	 *                                            as integer for pixel value, float for flex value
	 *                                            or string including `px` or `%` units.
	 *                                            Width is used only for table grid type.
	 * @param string|int|float|NULL $minWidth     Column min. width, it can be defined as integer 
	 *                                            or float for pixel value or string including `px` 
	 *                                            or `%` units. Min. width is used only for table grid type.
	 * @param string|int|float|NULL $maxWidth     Column max. width, it can be defined as integer 
	 *                                            or float for pixel value or string including `px` 
	 *                                            or `%` units. Min. width is used only for table grid type.
	 * @param \string[]             $cssClasses   Column additional css classes for head cell and body cell.
	 * @param bool|NULL             $disabled     Force column disable.
	 */
	public function __construct (
		$propName = NULL, 
		$dbColumnName = NULL, 
		$headingName = NULL, 
		$title = NULL, 
		$urlName = NULL, 
		$urlHelper = NULL,
		$columnIndex = NULL,
		$sort = NULL, 
		$filter = NULL, 
		$types = NULL, 
		$format = NULL, 
		$viewHelper = NULL,
		$width = NULL,
		$minWidth = NULL,
		$maxWidth = NULL,
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

		if ($headingName !== NULL) {
			$this->headingName = $headingName;
		} else if ($propNameHasValue) {
			$this->headingName = $propName;
		}

		if ($urlName !== NULL) {
			$this->urlName = $urlName;
		} else if ($propNameHasValue) {
			$this->urlName = $propName;
		}
		
		if ($title !== NULL)		$this->title		= $title;
		if ($urlHelper !== NULL)	$this->urlHelper	= $urlHelper;
		if ($columnIndex !== NULL)	$this->columnIndex	= $columnIndex;
		if ($sort !== NULL)			$this->sort			= $sort;
		if ($filter !== NULL)		$this->filter		= $filter;
		if ($types !== NULL)		$this->types		= $types;
		if ($format !== NULL)		$this->format		= $format;
		if ($viewHelper !== NULL)	$this->viewHelper	= $viewHelper;
		if ($width !== NULL)		$this->width		= $width;
		if ($minWidth !== NULL)		$this->minWidth		= $minWidth;
		if ($maxWidth !== NULL)		$this->maxWidth		= $maxWidth;
		if ($cssClasses !== NULL)	$this->cssClasses	= $cssClasses;
		if ($disabled !== NULL)		$this->disabled		= $disabled;
	}

	/**
	 * @inheritDocs
	 * @return string|NULL
	 */
	public function GetPropName () {
		return $this->propName;
	}

	/**
	 * @inheritDocs
	 * @param  string|NULL $propName
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetPropName ($propName) {
		$this->propName = $propName;
		return $this;
	}
	
	/**
	 * @inheritDocs
	 * @return string|NULL
	 */
	public function GetHeadingName () {
		return $this->headingName;
	}

	/**
	 * @inheritDocs
	 * @param  string|NULL $headingName
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetHeadingName ($headingName) {
		$this->headingName = $headingName;
		return $this;
	}
	
	/**
	 * @inheritDocs
	 * @return string|NULL
	 */
	public function GetTitle () {
		return $this->title;
	}
	
	/**
	 * @inheritDocs
	 * @param  string|NULL $title
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetTitle ($title) {
		$this->title = $title;
		return $this;
	}
	
	/**
	 * @inheritDocs
	 * @return string|NULL
	 */
	public function GetDbColumnName () {
		return $this->dbColumnName;
	}

	/**
	 * @inheritDocs
	 * @param  string|NULL $dbColumnName
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetDbColumnName ($dbColumnName) {
		$this->dbColumnName = $dbColumnName;
		return $this;
	}
	
	/**
	 * @inheritDocs
	 * @return string|NULL
	 */
	public function GetUrlName () {
		return $this->urlName;
	}
	
	/**
	 * @inheritDocs
	 * @param  string|NULL $urlName
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetUrlName ($urlName) {
		$this->urlName = $urlName;
		return $this;
	}
	
	/**
	 * @inheritDocs
	 * @return bool|string|NULL
	 */
	public function GetUrlHelper () {
		return $this->urlHelper;
	}
	
	/**
	 * @inheritDocs
	 * @param  bool|string|NULL $urlHelper
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetUrlHelper ($urlHelper) {
		$this->urlHelper = $urlHelper;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return int|NULL
	 */
	public function GetColumnIndex () {
		return $this->columnIndex;
	}

	/**
	 * @inheritDocs
	 * @param  int|NULL $columnIndex
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetColumnIndex ($columnIndex) {
		$this->columnIndex = $columnIndex;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return string|bool|NULL
	 */
	public function GetSort () {
		return $this->sort;
	}

	/**
	 * @inheritDocs
	 * @param  string|bool|NULL $sort
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetSort ($sort) {
		$this->sort = $sort;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return int|bool
	 */
	public function GetFilter () {
		return $this->filter;
	}

	/**
	 * @inheritDocs
	 * @param  int|bool $filter
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetFilter ($filter) {
		$this->filter = $filter;
		return $this;
	}
	
	/**
	 * @inheritDocs
	 * @return \string[]|NULL
	 */
	public function GetTypes () {
		return $this->types;
	}

	/**
	 * @inheritDocs
	 * @param  \string[] $types
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetTypes ($types) {
		$this->types = $types;
		return $this;
	}
	
	/**
	 * @inheritDocs
	 * @return array|NULL
	 */
	public function GetFormat () {
		return $this->format;
	}

	/**
	 * @inheritDocs
	 * @param  array|NULL $format
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetFormat ($format) {
		$this->format = $format;
		return $this;
	}
	
	/**
	 * @inheritDocs
	 * @return array|NULL
	 */
	public function GetViewHelper () {
		return $this->viewHelper;
	}

	/**
	 * @inheritDocs
	 * @param  array|NULL $viewHelper
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetViewHelper ($viewHelper) {
		$this->viewHelper = $viewHelper;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return string|int|float|NULL
	 */
	public function GetWidth () {
		return $this->width;
	}

	/**
	 * @inheritDocs
	 * @param  string|int|float|NULL $width
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetWidth ($width) {
		$this->width = $width;
		return $this;
	}

	/**
	 * Get column min. width, it can be defined as integer 
	 * or float for pixel value or string including `px` 
	 * or `%` units. Min. width is used only for table grid type.
	 * @return string|int|float|NULL
	 */
	public function GetMinWidth () {
		return $this->minWidth;
	}

	/**
	 * Set column min. width, it can be defined as integer 
	 * or float for pixel value or string including `px` 
	 * or `%` units. Min. width is used only for table grid type.
	 * @param  string|int|float|NULL $minWidth
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetMinWidth ($minWidth) {
		$this->minWidth = $minWidth;
		return $this;
	}
	
	/**
	 * Get column max. width, it can be defined as integer 
	 * or float for pixel value or string including `px` 
	 * or `%` units. Min. width is used only for table grid type.
	 * @return string|int|float|NULL
	 */
	public function GetMaxWidth () {
		return $this->maxWidth;
	}

	/**
	 * Set column max. width, it can be defined as integer 
	 * or float for pixel value or string including `px` 
	 * or `%` units. Min. width is used only for table grid type.
	 * @param  string|int|float|NULL $maxWidth
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetMaxWidth ($maxWidth) {
		$this->maxWidth = $maxWidth;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return \string[]|NULL
	 */
	public function GetCssClasses () {
		return $this->cssClasses;
	}

	/**
	 * @inheritDocs
	 * @param  \string[] $cssClasses
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetCssClasses ($cssClasses) {
		$this->cssClasses = $cssClasses;
		return $this;
	}
	
	/**
	 * @inheritDocs
	 * @return bool|NULL
	 */
	public function GetDisabled () {
		return $this->disabled;
	}

	/**
	 * @inheritDocs
	 * @param  bool|NULL $disabled
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetDisabled ($disabled) {
		$this->disabled = $disabled;
		return $this;
	}
	
	/**
	 * Return data for JSON serialization.
	 * @return array|mixed
	 */
	#[\ReturnTypeWillChange]
	public function jsonSerialize () {
		return JsonSerialize::Serialize($this, \ReflectionProperty::IS_PROTECTED);
	}
}
