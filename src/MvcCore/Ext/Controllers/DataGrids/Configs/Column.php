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
	 * @var string|array|NULL
	 */
	#[JsonSerialize]
	protected $headingName = NULL;
	
	/**
	 * Grid heading title attribute, displayed on heading mouse over.
	 * @jsonSerialize
	 * @var string|array|NULL
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
	 * @var string|array|NULL
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
	 * Datagrid column index customized by user, starting with `0`, optional.
	 * @jsonSerialize
	 * @var int|NULL
	 */
	#[JsonSerialize]
	protected $columnIndexUser = NULL;
	
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
	 * Boolean `TRUE` if first type implements `\DateTimeInterface`.
	 * @jsonSerialize
	 * @var bool
	 */
	#[JsonSerialize]
	protected $isDateTime = FALSE;

	/**
	 * Boolean `TRUE` if first type is `string`.
	 * @jsonSerialize
	 * @var bool
	 */
	#[JsonSerialize]
	protected $isString = FALSE;

	/**
	 * Property automatic parsing arguments.
	 * @jsonSerialize
	 * @var array|NULL
	 */
	#[JsonSerialize]
	protected $parserArgs = NULL;

	/**
	 * Property automatic formating arguments.
	 * @jsonSerialize
	 * @var array|NULL
	 */
	#[JsonSerialize]
	protected $formatArgs = NULL;

	/**
	 * Property automatic formating view helper name.
	 * @jsonSerialize
	 * @var string|NULL
	 */
	#[JsonSerialize]
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
	 * Column CSS `flex` value, it can be defined as single 
	 * integer or float value for `flex-grow` only or as string 
	 * value for full CSS `flex` shorthand to define `flex-grow`, 
	 * `flex-shrink` and `flex-basis`.
	 * @jsonSerialize
	 * @var string|int|float|NULL
	 */
	#[JsonSerialize]
	protected $flex = NULL;

	/**
	 * Column additional css classes for head cell and body cell.
	 * @jsonSerialize
	 * @var \string[]
	 */
	#[JsonSerialize]
	protected $cssClasses = [];
	
	/**
	 * `TRUE` for editable column, `FALSE|NULL` for not editable column (by default).
	 * @jsonSerialize
	 * @var bool|NULL
	 */
	#[JsonSerialize]
	protected $editable = NULL;
	
	/**
	 * `TRUE` for disabled column, `FALSE|NULL` for enabled column (enabled by default).
	 * @jsonSerialize
	 * @var bool|NULL
	 */
	#[JsonSerialize]
	protected $disabled = NULL;
	
	/**
	 * `TRUE` to always send column in AJAX response, no matter if column is active 
	 * or not, `FALSE|NULL` to not send inactive column (`NULL` by default).
	 * @jsonSerialize
	 * @var bool|NULL
	 */
	#[JsonSerialize]
	protected $alwaysSend = NULL;
	

	/**
	 * Create datagrid column config item.
	 * @param string|NULL           $propName     Data grid model property name.
	 * @param string|NULL           $dbColumnName Database column name. If `NULL`, `$propName` is used.
	 * @param string|array|NULL     $headingName  Data grid visible column name. If `NULL`, `$propName` is used.
	 * @param string|array|NULL     $title        Grid heading title attribute, displayed on heading mouse over.
	 * @param string|array|NULL     $urlName      Data grid url column name to define sorting or filtering. 
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
	 * @param array|NULL            $parserArgs   Property automatic parsing arguments.
	 * @param array|NULL            $formatArgs   Property automatic formating arguments.
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
	 * @param string|int|float|NULL $flex         Column CSS `flex` value, it can be defined as single 
	 *                                            integer or float value for `flex-grow` only or as string 
	 *                                            value for full CSS `flex` shorthand to define `flex-grow`, 
	 *                                            `flex-shrink` and `flex-basis`.
	 * @param \string[]             $cssClasses   Column additional css classes for head cell and body cell.
	 * @param bool|NULL             $editable     `TRUE` for editable column, `FALSE|NULL` for 
	 *                                            not editable column (by default).
	 * @param bool|NULL             $disabled     Force column disable (enabled by default).
	 * @param bool|NULL             $alwaysSend   `TRUE` to always send column in AJAX response, no matter 
	 *                                            if column is active or not, `FALSE|NULL` to not send inactive 
	 *                                            column (`NULL` by default).
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
		$parserArgs = NULL, 
		$formatArgs = NULL, 
		$viewHelper = NULL,
		$width = NULL,
		$minWidth = NULL,
		$maxWidth = NULL,
		$flex = NULL,
		$cssClasses = NULL,
		$editable = NULL,
		$disabled = NULL,
		$alwaysSend = NULL
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
		if ($types !== NULL)		$this->SetTypes($types);
		if ($parserArgs !== NULL)	$this->parserArgs	= $parserArgs;
		if ($formatArgs !== NULL)	$this->formatArgs	= $formatArgs;
		if ($viewHelper !== NULL)	$this->viewHelper	= $viewHelper;
		if ($width !== NULL)		$this->width		= $width;
		if ($minWidth !== NULL)		$this->minWidth		= $minWidth;
		if ($maxWidth !== NULL)		$this->maxWidth		= $maxWidth;
		if ($flex !== NULL)			$this->flex			= $flex;
		if ($cssClasses !== NULL)	$this->cssClasses	= $cssClasses;
		if ($editable !== NULL)		$this->editable		= $editable;
		if ($disabled !== NULL)		$this->disabled		= $disabled;
		if ($alwaysSend !== NULL)	$this->alwaysSend	= $alwaysSend;
	}

	/**
	 * @inheritDoc
	 * @return string|NULL
	 */
	public function GetPropName () {
		return $this->propName;
	}

	/**
	 * @inheritDoc
	 * @param  string|NULL $propName
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetPropName ($propName) {
		$this->propName = $propName;
		return $this;
	}
	
	/**
	 * @inheritDoc
	 * @return string|array|NULL
	 */
	public function GetHeadingName () {
		return $this->headingName;
	}

	/**
	 * @inheritDoc
	 * @param  string|array|NULL $headingName
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetHeadingName ($headingName) {
		$this->headingName = $headingName;
		return $this;
	}
	
	/**
	 * @inheritDoc
	 * @return string|array|NULL
	 */
	public function GetTitle () {
		return $this->title;
	}
	
	/**
	 * @inheritDoc
	 * @param  string|array|NULL $title
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetTitle ($title) {
		$this->title = $title;
		return $this;
	}
	
	/**
	 * @inheritDoc
	 * @return string|NULL
	 */
	public function GetDbColumnName () {
		return $this->dbColumnName;
	}

	/**
	 * @inheritDoc
	 * @param  string|NULL $dbColumnName
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetDbColumnName ($dbColumnName) {
		$this->dbColumnName = $dbColumnName;
		return $this;
	}
	
	/**
	 * @inheritDoc
	 * @return string|array|NULL
	 */
	public function GetUrlName () {
		return $this->urlName;
	}
	
	/**
	 * @inheritDoc
	 * @param  string|array|NULL $urlName
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetUrlName ($urlName) {
		$this->urlName = $urlName;
		return $this;
	}
	
	/**
	 * @inheritDoc
	 * @return bool|string|NULL
	 */
	public function GetUrlHelper () {
		return $this->urlHelper;
	}
	
	/**
	 * @inheritDoc
	 * @param  bool|string|NULL $urlHelper
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetUrlHelper ($urlHelper) {
		$this->urlHelper = $urlHelper;
		return $this;
	}
	
	/**
	 * @inheritDoc
	 * @return int|NULL
	 */
	public function GetColumnIndex () {
		return $this->columnIndex;
	}

	/**
	 * @inheritDoc
	 * @param  int|NULL $columnIndex
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetColumnIndex ($columnIndex) {
		$this->columnIndex = $columnIndex;
		return $this;
	}

	/**
	 * @inheritDoc
	 * @return int|NULL
	 */
	public function GetColumnIndexUser () {
		return $this->columnIndexUser;
	}

	/**
	 * @inheritDoc
	 * @param  int|NULL $columnIndexUser
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetColumnIndexUser ($columnIndexUser) {
		$this->columnIndexUser = $columnIndexUser;
		return $this;
	}

	/**
	 * @inheritDoc
	 * @return string|bool|NULL
	 */
	public function GetSort () {
		return $this->sort;
	}

	/**
	 * @inheritDoc
	 * @param  string|bool|NULL $sort
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetSort ($sort) {
		$this->sort = $sort;
		return $this;
	}

	/**
	 * @inheritDoc
	 * @return int|bool
	 */
	public function GetFilter () {
		return $this->filter;
	}

	/**
	 * @inheritDoc
	 * @param  int|bool $filter
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetFilter ($filter) {
		$this->filter = $filter;
		return $this;
	}
	
	/**
	 * @inheritDoc
	 * @return \string[]|NULL
	 */
	public function GetTypes () {
		return $this->types;
	}

	/**
	 * @inheritDoc
	 * @param  \string[] $types
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetTypes ($types) {
		$this->types = $types;
		$this->isString = FALSE;
		$this->isDateTime = FALSE;
		if (count($types) > 0) {
			$firstType = str_replace('?', '', (string) $types[0]);
			if ($firstType === 'string') {
				$this->isString = TRUE;
			} else if (class_exists($firstType)) {
				$phpWithDtInterface = PHP_VERSION_ID >= 50500;
				if ($phpWithDtInterface) {
					$columnTypeInterfaces = class_implements($firstType);
					$this->isDateTime = isset($columnTypeInterfaces['DateTimeInterface']);
				} else {
					$this->isDateTime = (
						$firstType === 'DateTime' ||
						$firstType === 'DateTimeImmutable' ||
						is_a($firstType, '\\DateTime') || 
						is_a($firstType, '\\DateTimeImmutable') || 
						is_subclass_of($firstType, '\\DateTime') ||
						is_subclass_of($firstType, '\\DateTimeImmutable')
					);
				}
			}
		}
		return $this;
	}
	
	/**
	 * @inheritDoc
	 * @return bool
	 */
	public function GetIsDateTime () {
		return $this->isDateTime;
	}
	
	/**
	 * @inheritDoc
	 * @return bool
	 */
	public function GetIsString () {
		return $this->isString;
	}
	
	/**
	 * @inheritDoc
	 * @return array|NULL
	 */
	public function GetParserArgs () {
		return $this->parserArgs;
	}

	/**
	 * @inheritDoc
	 * @param  array|NULL $parserArgs
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetParserArgs ($parserArgs) {
		$this->parserArgs = $parserArgs;
		return $this;
	}
	
	/**
	 * @inheritDoc
	 * @return array|NULL
	 */
	public function GetFormatArgs () {
		return $this->formatArgs;
	}

	/**
	 * @inheritDoc
	 * @param  array|NULL $formatArgs
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetFormatArgs ($formatArgs) {
		$this->formatArgs = $formatArgs;
		return $this;
	}
	
	/**
	 * @inheritDoc
	 * @return string|NULL
	 */
	public function GetViewHelper () {
		return $this->viewHelper;
	}

	/**
	 * @inheritDoc
	 * @param  string|NULL $viewHelper
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetViewHelper ($viewHelper) {
		$this->viewHelper = $viewHelper;
		return $this;
	}

	/**
	 * @inheritDoc
	 * @return string|int|float|NULL
	 */
	public function GetWidth () {
		return $this->width;
	}

	/**
	 * @inheritDoc
	 * @param  string|int|float|NULL $width
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetWidth ($width) {
		$this->width = $width;
		return $this;
	}

	/**
	 * @inheritDoc
	 * @return string|int|float|NULL
	 */
	public function GetMinWidth () {
		return $this->minWidth;
	}

	/**
	 * @inheritDoc
	 * @param  string|int|float|NULL $minWidth
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetMinWidth ($minWidth) {
		$this->minWidth = $minWidth;
		return $this;
	}
	
	/**
	 * @inheritDoc
	 * @return string|int|float|NULL
	 */
	public function GetMaxWidth () {
		return $this->maxWidth;
	}

	/**
	 * @inheritDoc
	 * @param  string|int|float|NULL $maxWidth
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetMaxWidth ($maxWidth) {
		$this->maxWidth = $maxWidth;
		return $this;
	}
	
	/**
	 * @inheritDoc
	 * @return string|int|float|NULL
	 */
	public function GetFlex () {
		return $this->flex;
	}

	/**
	 * @inheritDoc
	 * @param  string|int|float|NULL $flex
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetFlex ($flex) {
		$this->flex = $flex;
		return $this;
	}

	/**
	 * @inheritDoc
	 * @return \string[]|NULL
	 */
	public function GetCssClasses () {
		return $this->cssClasses;
	}

	/**
	 * @inheritDoc
	 * @param  \string[] $cssClasses
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetCssClasses ($cssClasses) {
		$this->cssClasses = $cssClasses;
		return $this;
	}
	
	/**
	 * @inheritDoc
	 * @return bool|NULL
	 */
	public function GetEditable () {
		return $this->editable;
	}

	/**
	 * @inheritDoc
	 * @param  bool|NULL $editable
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetEditable ($editable) {
		$this->editable = $editable;
		return $this;
	}
	
	/**
	 * @inheritDoc
	 * @return bool|NULL
	 */
	public function GetDisabled () {
		return $this->disabled;
	}

	/**
	 * @inheritDoc
	 * @param  bool|NULL $disabled
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetDisabled ($disabled) {
		$this->disabled = $disabled;
		return $this;
	}
	
	/**
	 * @inheritDoc
	 * @return bool|NULL
	 */
	public function GetAlwaysSend () {
		return $this->alwaysSend;
	}

	/**
	 * @inheritDoc
	 * @param  bool|NULL $alwaysSend
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetAlwaysSend ($alwaysSend) {
		$this->alwaysSend = $alwaysSend;
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
