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

interface IColumn {
	
	/**
	 * Get data grid model property name.
	 * @return string|null
	 */
	public function GetPropName ();

	/**
	 * Set data grid model property name.
	 * @param  string|null $propName
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetPropName ($propName);
	
	/**
	 * Get grid heading human readable text.
	 * @return string|array|null
	 */
	public function GetHeadingName ();
	
	/**
	 * Set grid heading human readable text.
	 * @param  string|array|null $headingName
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetHeadingName ($headingName);
	
	/**
	 * Get grid heading title attribute, displayed on heading mouse over.
	 * @return string|array|null
	 */
	public function GetTitle ();
	
	/**
	 * Set grid heading title attribute, displayed on heading mouse over.
	 * @param  string|array|null $title
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetTitle ($title);

	/**
	 * Get database column name.
	 * @return string|null
	 */
	public function GetDbColumnName ();
	
	/**
	 * Set database column name.
	 * @param  string|null $dbColumnName
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetDbColumnName ($dbColumnName);

	/**
	 * Get URL column name when sorting or filtering.
	 * @return string|array|null
	 */
	public function GetUrlName ();
	
	/**
	 * Set URL column name when sorting or filtering.
	 * @param  string|array|null $urlName
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetUrlName ($urlName);

	/**
	 * Get URL helper to generate cell anchor URL.
	 * Boolean to enable/disable anchor url for filtering
	 * (`TRUE` by default) or string to declare row instance
	 * model public method to generate custom URL.
	 * @return bool|string|null
	 */
	public function GetUrlHelper ();

	/**
	 * Set URL helper to generate cell anchor URL.
	 * Boolean to enable/disable anchor url for filtering
	 * (`TRUE` by default) or string to declare row instance
	 * model public method to generate custom URL.
	 * @param  bool|string|null $urlHelper
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetUrlHelper ($urlHelper);

	/**
	 * Get datagrid column index, starting with `0`, optional.
	 * @return int|null
	 */
	public function GetColumnIndex ();

	/**
	 * Set datagrid column index, starting with `0`, optional.
	 * @param  int|null $columnIndex
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetColumnIndex ($columnIndex);
	
	/**
	 * Get default sorting definition with values `ASC | DESC` 
	 * or `TRUE | FALSE` to enable/disable sorting.
	 * @return string|bool|null
	 */
	public function GetSort ();
	
	/**
	 * Set default sorting definition with values `ASC | DESC` 
	 * or `TRUE | FALSE` to enable/disable sorting.
	 * @param  string|bool|null $sort
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetSort ($sort);
	
	/**
	 * Get boolean to allow column filtering.
	 * @return int|bool
	 */
	public function GetFilter ();
	
	/**
	 * Set boolean to allow column filtering.
	 * @param  int|bool $filter
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetFilter ($filter);
	
	/**
	 * Get property type(s), necessary for automatic formating.
	 * @return \string[]|null
	 */
	public function GetTypes ();
	
	/**
	 * Set property type(s), necessary for automatic formating.
	 * @param  \string[] $types
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetTypes ($types);

	/**
	 * Get TRUE` if first type implements `\DateTimeInterface`.
	 * @return bool
	 */
	public function GetIsDateTime ();
	
	/**
	 * Get TRUE` if first type is `string`.
	 * @return bool
	 */
	public function GetIsString ();
	
	/**
	 * Get property automatic parser arguments.
	 * @return array|null
	 */
	public function GetParserArgs ();
	
	/**
	 * Set property automatic parser arguments.
	 * @param  array|null $parserArgs
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetParserArgs ($parserArgs);
	
	/**
	 * Get property automatic formating arguments.
	 * @return array|null
	 */
	public function GetFormatArgs ();
	
	/**
	 * Set property automatic formating arguments.
	 * @param  array|null $formatArgs
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetFormatArgs ($formatArgs);

	/**
	 * Get boolean `TRUE` if column has primary key or unique key to compute client row id.
	 * @return bool|null
	 */
	public function GetIdColumn ();

	/**
	 * Set boolean `TRUE` if column has primary key or unique key to compute client row id.
	 * @param  bool|null $idColumn
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetIdColumn ($idColumn);

	/**
	 * Get property automatic formating view helper name.
	 * @return string|null
	 */
	public function GetViewHelper ();
	
	/**
	 * Set property automatic formating view helper name.
	 * @param  string|null $viewHelper
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetViewHelper ($viewHelper);

	/**
	 * Get column initial or current width, it can be defined 
	 * as integer for pixel value, float for flex value
	 * or string including `px` or `%` units.
	 * Width is used only for table grid type.
	 * @return string|int|float|null
	 */
	public function GetWidth ();

	/**
	 * Set column initial or current width, it can be defined 
	 * as integer for pixel value, float for flex value
	 * or string including pixels or percentage value.
	 * Width is used only for table grid type.
	 * @param  string|int|float|null $width
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetWidth ($width);

	/**
	 * Get column min. width, it can be defined as integer 
	 * or float for pixel value or string including `px` 
	 * or `%` units. Min. width is used only for table grid type.
	 * @return string|int|float|null
	 */
	public function GetMinWidth ();

	/**
	 * Set column min. width, it can be defined as integer 
	 * or float for pixel value or string including `px` 
	 * or `%` units. Min. width is used only for table grid type.
	 * @param  string|int|float|null $minWidth
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetMinWidth ($minWidth);
	
	/**
	 * Get column max. width, it can be defined as integer 
	 * or float for pixel value or string including `px` 
	 * or `%` units. Min. width is used only for table grid type.
	 * @return string|int|float|null
	 */
	public function GetMaxWidth ();

	/**
	 * Set column max. width, it can be defined as integer 
	 * or float for pixel value or string including `px` 
	 * or `%` units. Min. width is used only for table grid type.
	 * @param  string|int|float|null $maxWidth
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetMaxWidth ($maxWidth);

	/**
	 * Get column CSS `flex` value, it can be defined as single 
	 * integer or float value for `flex-grow` only or as string 
	 * value for full CSS `flex` shorthand to define `flex-grow`, 
	 * `flex-shrink` and `flex-basis`.
	 * @return string|int|float|null
	 */
	public function GetFlex ();

	/**
	 * Set column CSS `flex` value, it can be defined as single 
	 * integer or float value for `flex-grow` only or as string 
	 * value for full CSS `flex` shorthand to define `flex-grow`, 
	 * `flex-shrink` and `flex-basis`.
	 * @param  string|int|float|null $flex
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetFlex ($flex);

	/**
	 * Get column additional css classes for head cell and body cell.
	 * @return \string[]|null
	 */
	public function GetCssClasses ();

	/**
	 * Set column additional css classes for head cell and body cell.
	 * @param  \string[] $cssClasses
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetCssClasses ($cssClasses);
	
	/**
	 * Get `TRUE` for editable column, `FALSE|null` for not editable column (by default).
	 * @return bool|null
	 */
	public function GetEditable ();

	/**
	 * Set `TRUE` for editable column, `FALSE|null` for not editable column (by default).
	 * @param  bool|null $editable
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetEditable ($editable);
	
	/**
	 * Get `TRUE` for disabled column, `FALSE|null` for enabled column (enabled by default).
	 * @return bool|null
	 */
	public function GetDisabled ();

	/**
	 * Set `TRUE` for disabled column, `FALSE|null` for enabled column (enabled by default).
	 * @param  bool|null $disabled
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetDisabled ($disabled);

	/**
	 * Get `TRUE` to always send column in AJAX response, no matter if column is active 
	 * or not, `FALSE|null` to not send inactive column (`null` by default).
	 * @return bool|null
	 */
	public function GetAlwaysSend ();

	/**
	 * Set `TRUE` to always send column in AJAX response, no matter if column is active 
	 * or not, `FALSE|null` to not send inactive column (`null` by default).
	 * @param  bool|null $alwaysSend
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetAlwaysSend ($alwaysSend);
}
