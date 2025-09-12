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
	 * @var string|null
	 */
	#[JsonSerialize]
	protected $propName = null;
	
	/**
	 * Grid heading human readable text.
	 * @jsonSerialize
	 * @var string|array|null
	 */
	#[JsonSerialize]
	protected $headingName = null;
	
	/**
	 * Grid heading title attribute, displayed on heading mouse over.
	 * @jsonSerialize
	 * @var string|array|null
	 */
	#[JsonSerialize]
	protected $title = null;
	
	/**
	 * Database column name.
	 * @var string|null
	 */
	protected $dbColumnName = null;
	
	/**
	 * URL column name when sorting or filtering.
	 * @jsonSerialize
	 * @var string|array|null
	 */
	#[JsonSerialize]
	protected $urlName = null;
	
	/**
	 * URL helper to generate cell anchor URL.
	 * Boolean to enable/disable anchor url for filtering
	 * (`TRUE` by default) or string to declare row instance
	 * model public method to generate custom URL.
	 * @var bool|string|null
	 */
	protected $urlHelper = TRUE;

	/**
	 * Datagrid column index, starting with `0`, optional.
	 * @jsonSerialize
	 * @var int|null
	 */
	#[JsonSerialize]
	protected $columnIndex = null;

	/**
	 * Datagrid column index customized by user, starting with `0`, optional.
	 * @jsonSerialize
	 * @var int|null
	 */
	#[JsonSerialize]
	protected $columnIndexUser = null;
	
	/**
	 * Default sorting definition with values `ASC | DESC` 
	 * or `TRUE | FALSE` to enable/disable sorting.
	 * @jsonSerialize
	 * @var string|bool|null
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
	 * @var \string[]|null
	 */
	#[JsonSerialize]
	protected $types = null;

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
	 * @var array|null
	 */
	#[JsonSerialize]
	protected $parserArgs = null;

	/**
	 * Property automatic formating arguments.
	 * @jsonSerialize
	 * @var array|null
	 */
	#[JsonSerialize]
	protected $formatArgs = null;

	/**
	 * Boolean `TRUE` if column has primary key 
	 * or unique key to compute client row id.
	 * @jsonSerialize
	 * @var bool|null
	 */
	#[JsonSerialize]
	protected $idColumn = null;

	/**
	 * Property automatic formating view helper name.
	 * @jsonSerialize
	 * @var string|null
	 */
	#[JsonSerialize]
	protected $viewHelper = null;

	/**
	 * Column initial or current width, it can be defined 
	 * as integer for pixel value, float for flex value
	 * or string including `px` or `%` units.
	 * Width is used only for table grid type.
	 * @jsonSerialize
	 * @var string|int|float|null
	 */
	#[JsonSerialize]
	protected $width = null;

	/**
	 * Column min. width, it can be defined as integer 
	 * or float for pixel value or string including `px` 
	 * or `%` units. Min. width is used only for table grid type.
	 * @jsonSerialize
	 * @var string|int|float|null
	 */
	#[JsonSerialize]
	protected $minWidth = null;

	/**
	 * Column max. width, it can be defined as integer 
	 * or float for pixel value or string including `px` 
	 * or `%` units. Min. width is used only for table grid type.
	 * @jsonSerialize
	 * @var string|int|float|null
	 */
	#[JsonSerialize]
	protected $maxWidth = null;

	/**
	 * Column CSS `flex` value, it can be defined as single 
	 * integer or float value for `flex-grow` only or as string 
	 * value for full CSS `flex` shorthand to define `flex-grow`, 
	 * `flex-shrink` and `flex-basis`.
	 * @jsonSerialize
	 * @var string|int|float|null
	 */
	#[JsonSerialize]
	protected $flex = null;

	/**
	 * Column additional css classes for head cell and body cell.
	 * @jsonSerialize
	 * @var \string[]
	 */
	#[JsonSerialize]
	protected $cssClasses = [];
	
	/**
	 * `TRUE` for editable column, `FALSE|null` for not editable column (by default).
	 * @jsonSerialize
	 * @var bool|null
	 */
	#[JsonSerialize]
	protected $editable = null;
	
	/**
	 * `TRUE` for disabled column, `FALSE|null` for enabled column (enabled by default).
	 * @jsonSerialize
	 * @var bool|null
	 */
	#[JsonSerialize]
	protected $disabled = null;
	
	/**
	 * `TRUE` to always send column in AJAX response, no matter if column is active 
	 * or not, `FALSE|null` to not send inactive column (`null` by default).
	 * @jsonSerialize
	 * @var bool|null
	 */
	#[JsonSerialize]
	protected $alwaysSend = null;
	
	/**
	 * Create datagrid column config item.
	 * @param string|null           $propName     Data grid model property name.
	 * @param string|null           $dbColumnName Database column name. If `null`, `$propName` is used.
	 * @param string|array|null     $headingName  Data grid visible column name. If `null`, `$propName` is used.
	 * @param string|array|null     $title        Grid heading title attribute, displayed on heading mouse over.
	 * @param string|array|null     $urlName      Data grid url column name to define sorting or filtering. 
	 *                                            If `null`, `$propName` is used.
	 * @param \string[]|null        $types        Property type(s), necessary for automatic formating.
	 * @param int|null              $columnIndex  Datagrid column index, starting with `0`, optional.
	 * @param string|bool|null      $sort         Default sorting definition with values `ASC | DESC` 
	 *                                            or `TRUE | FALSE` to enable/disable sorting.
	 * @param int|bool              $filter       Filtering mode flags to allow specify operators 
	 *                                            for each column or boolean to allow filtering only.
	 * @param array|null            $parserArgs   Property automatic parsing arguments.
	 * @param array|null            $formatArgs   Property automatic formating arguments.
	 * @param bool|null             $idColumn     Boolean `TRUE` if column has primary key or unique key 
	 *                                            to compute client row id.
	 * @param string|null           $viewHelper   Property automatic formating view helper name.
	 * @param bool|string|null      $urlHelper    URL helper to generate cell anchor URL.
	 *                                            Boolean to enable/disable anchor url for filtering
	 *                                            (`TRUE` by default) or string to declare row instance
	 *                                            model public method to generate custom URL.
	 * @param string|int|float|null $width        Column initial or current width, it can be defined 
	 *                                            as integer for pixel value, float for flex value
	 *                                            or string including `px` or `%` units.
	 *                                            Width is used only for table grid type.
	 * @param string|int|float|null $minWidth     Column min. width, it can be defined as integer 
	 *                                            or float for pixel value or string including `px` 
	 *                                            or `%` units. Min. width is used only for table grid type.
	 * @param string|int|float|null $maxWidth     Column max. width, it can be defined as integer 
	 *                                            or float for pixel value or string including `px` 
	 *                                            or `%` units. Min. width is used only for table grid type.
	 * @param string|int|float|null $flex         Column CSS `flex` value, it can be defined as single 
	 *                                            integer or float value for `flex-grow` only or as string 
	 *                                            value for full CSS `flex` shorthand to define `flex-grow`, 
	 *                                            `flex-shrink` and `flex-basis`.
	 * @param \string[]             $cssClasses   Column additional css classes for head cell and body cell.
	 * @param bool|null             $editable     `TRUE` for editable column, `FALSE|null` for 
	 *                                            not editable column (by default).
	 * @param bool|null             $disabled     Force column disable (enabled by default).
	 * @param bool|null             $alwaysSend   `TRUE` to always send column in AJAX response, no matter 
	 *                                            if column is active or not, `FALSE|null` to not send inactive 
	 *                                            column (`null` by default).
	 */
	public function __construct (
		$propName = null, 
		$dbColumnName = null, 
		$headingName = null, 
		$title = null, 
		$urlName = null, 
		$types = null, 
		$columnIndex = null,
		$sort = null, 
		$filter = null, 
		$parserArgs = null, 
		$formatArgs = null, 
		$idColumn = null,
		$viewHelper = null,
		$urlHelper = null,
		$width = null,
		$minWidth = null,
		$maxWidth = null,
		$flex = null,
		$cssClasses = null,
		$editable = null,
		$disabled = null,
		$alwaysSend = null
	) {
		$propNameHasValue = $propName !== null;
		if ($propNameHasValue) 
			$this->propName = $propName;

		if ($dbColumnName !== null) {
			$this->dbColumnName = $dbColumnName;
		} else if ($propNameHasValue) {
			$this->dbColumnName = $propName;
		}

		if ($headingName !== null) {
			$this->headingName = $headingName;
		} else if ($propNameHasValue) {
			$this->headingName = $propName;
		}

		if ($urlName !== null) {
			$this->urlName = $urlName;
		} else if ($propNameHasValue) {
			$this->urlName = $propName;
		}

		if ($alwaysSend === null && $idColumn)
			$alwaysSend = TRUE;
		
		if ($title !== null)		$this->title		= $title;
		if ($urlHelper !== null)	$this->urlHelper	= $urlHelper;
		if ($columnIndex !== null)	$this->columnIndex	= $columnIndex;
		if ($sort !== null)			$this->sort			= $sort;
		if ($filter !== null)		$this->filter		= $filter;
		if ($types !== null)		$this->SetTypes($types);
		if ($parserArgs !== null)	$this->parserArgs	= $parserArgs;
		if ($formatArgs !== null)	$this->formatArgs	= $formatArgs;
		if ($idColumn !== null)		$this->idColumn		= $idColumn;
		if ($viewHelper !== null)	$this->viewHelper	= $viewHelper;
		if ($width !== null)		$this->width		= $width;
		if ($minWidth !== null)		$this->minWidth		= $minWidth;
		if ($maxWidth !== null)		$this->maxWidth		= $maxWidth;
		if ($flex !== null)			$this->flex			= $flex;
		if ($cssClasses !== null)	$this->cssClasses	= $cssClasses;
		if ($editable !== null)		$this->editable		= $editable;
		if ($disabled !== null)		$this->disabled		= $disabled;
		if ($alwaysSend !== null)	$this->alwaysSend	= $alwaysSend;
	}
	
	/**
	 * @inheritDoc
	 * @return string|null
	 */
	public function GetPropName () {
		return $this->propName;
	}

	/**
	 * @inheritDoc
	 * @param  string|null $propName
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetPropName ($propName) {
		$this->propName = $propName;
		return $this;
	}
	
	/**
	 * @inheritDoc
	 * @return string|array|null
	 */
	public function GetHeadingName () {
		return $this->headingName;
	}

	/**
	 * @inheritDoc
	 * @param  string|array|null $headingName
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetHeadingName ($headingName) {
		$this->headingName = $headingName;
		return $this;
	}
	
	/**
	 * @inheritDoc
	 * @return string|array|null
	 */
	public function GetTitle () {
		return $this->title;
	}
	
	/**
	 * @inheritDoc
	 * @param  string|array|null $title
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetTitle ($title) {
		$this->title = $title;
		return $this;
	}
	
	/**
	 * @inheritDoc
	 * @return string|null
	 */
	public function GetDbColumnName () {
		return $this->dbColumnName;
	}

	/**
	 * @inheritDoc
	 * @param  string|null $dbColumnName
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetDbColumnName ($dbColumnName) {
		$this->dbColumnName = $dbColumnName;
		return $this;
	}
	
	/**
	 * @inheritDoc
	 * @return string|array|null
	 */
	public function GetUrlName () {
		return $this->urlName;
	}
	
	/**
	 * @inheritDoc
	 * @param  string|array|null $urlName
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetUrlName ($urlName) {
		$this->urlName = $urlName;
		return $this;
	}
	
	/**
	 * @inheritDoc
	 * @return bool|string|null
	 */
	public function GetUrlHelper () {
		return $this->urlHelper;
	}
	
	/**
	 * @inheritDoc
	 * @param  bool|string|null $urlHelper
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetUrlHelper ($urlHelper) {
		$this->urlHelper = $urlHelper;
		return $this;
	}
	
	/**
	 * @inheritDoc
	 * @return int|null
	 */
	public function GetColumnIndex () {
		return $this->columnIndex;
	}

	/**
	 * @inheritDoc
	 * @param  int|null $columnIndex
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetColumnIndex ($columnIndex) {
		$this->columnIndex = $columnIndex;
		return $this;
	}

	/**
	 * @inheritDoc
	 * @return int|null
	 */
	public function GetColumnIndexUser () {
		return $this->columnIndexUser;
	}

	/**
	 * @inheritDoc
	 * @param  int|null $columnIndexUser
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetColumnIndexUser ($columnIndexUser) {
		$this->columnIndexUser = $columnIndexUser;
		return $this;
	}

	/**
	 * @inheritDoc
	 * @return string|bool|null
	 */
	public function GetSort () {
		return $this->sort;
	}

	/**
	 * @inheritDoc
	 * @param  string|bool|null $sort
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
	 * @return \string[]|null
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
	 * @return array|null
	 */
	public function GetParserArgs () {
		return $this->parserArgs;
	}

	/**
	 * @inheritDoc
	 * @param  array|null $parserArgs
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetParserArgs ($parserArgs) {
		$this->parserArgs = $parserArgs;
		return $this;
	}
	
	/**
	 * @inheritDoc
	 * @return array|null
	 */
	public function GetFormatArgs () {
		return $this->formatArgs;
	}

	/**
	 * @inheritDoc
	 * @param  array|null $formatArgs
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetFormatArgs ($formatArgs) {
		$this->formatArgs = $formatArgs;
		return $this;
	}

	/**
	 * @inheritDoc
	 * @return bool|null
	 */
	public function GetIdColumn () {
		return $this->idColumn;
	}

	/**
	 * @inheritDoc
	 * @param  bool|null $idColumn
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetIdColumn ($idColumn) {
		$this->idColumn = $idColumn;
		if ($idColumn) 
			$this->alwaysSend = TRUE;
		return $this;
	}

	/**
	 * @inheritDoc
	 * @return string|null
	 */
	public function GetViewHelper () {
		return $this->viewHelper;
	}

	/**
	 * @inheritDoc
	 * @param  string|null $viewHelper
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetViewHelper ($viewHelper) {
		$this->viewHelper = $viewHelper;
		return $this;
	}

	/**
	 * @inheritDoc
	 * @return string|int|float|null
	 */
	public function GetWidth () {
		return $this->width;
	}

	/**
	 * @inheritDoc
	 * @param  string|int|float|null $width
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetWidth ($width) {
		$this->width = $width;
		return $this;
	}

	/**
	 * @inheritDoc
	 * @return string|int|float|null
	 */
	public function GetMinWidth () {
		return $this->minWidth;
	}

	/**
	 * @inheritDoc
	 * @param  string|int|float|null $minWidth
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetMinWidth ($minWidth) {
		$this->minWidth = $minWidth;
		return $this;
	}
	
	/**
	 * @inheritDoc
	 * @return string|int|float|null
	 */
	public function GetMaxWidth () {
		return $this->maxWidth;
	}

	/**
	 * @inheritDoc
	 * @param  string|int|float|null $maxWidth
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetMaxWidth ($maxWidth) {
		$this->maxWidth = $maxWidth;
		return $this;
	}
	
	/**
	 * @inheritDoc
	 * @return string|int|float|null
	 */
	public function GetFlex () {
		return $this->flex;
	}

	/**
	 * @inheritDoc
	 * @param  string|int|float|null $flex
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetFlex ($flex) {
		$this->flex = $flex;
		return $this;
	}

	/**
	 * @inheritDoc
	 * @return \string[]|null
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
	 * @return bool|null
	 */
	public function GetEditable () {
		return $this->editable;
	}

	/**
	 * @inheritDoc
	 * @param  bool|null $editable
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetEditable ($editable) {
		$this->editable = $editable;
		return $this;
	}
	
	/**
	 * @inheritDoc
	 * @return bool|null
	 */
	public function GetDisabled () {
		return $this->disabled;
	}

	/**
	 * @inheritDoc
	 * @param  bool|null $disabled
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetDisabled ($disabled) {
		$this->disabled = $disabled;
		return $this;
	}
	
	/**
	 * @inheritDoc
	 * @return bool|null
	 */
	public function GetAlwaysSend () {
		return $this->alwaysSend;
	}

	/**
	 * @inheritDoc
	 * @param  bool|null $alwaysSend
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
