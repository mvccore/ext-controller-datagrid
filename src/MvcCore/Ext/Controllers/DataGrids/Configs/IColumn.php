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
	 * @return string|NULL
	 */
	public function GetPropName ();

	/**
	 * Set data grid model property name.
	 * @param  string|NULL $propName
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetPropName ($propName);
	
	/**
	 * Get grid heading human readable text.
	 * @return string|NULL
	 */
	public function GetHeadingName ();
	
	/**
	 * Set grid heading human readable text.
	 * @param  string|NULL $headingName
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetHeadingName ($headingName);
	
	/**
	 * Get grid heading title attribute, displayed on heading mouse over.
	 * @return string|NULL
	 */
	public function GetTitle ();
	
	/**
	 * Set grid heading title attribute, displayed on heading mouse over.
	 * @param  string|NULL $title
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetTitle ($title);

	/**
	 * Get database column name.
	 * @return string|NULL
	 */
	public function GetDbColumnName ();
	
	/**
	 * Set database column name.
	 * @param  string|NULL $dbColumnName
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetDbColumnName ($dbColumnName);

	/**
	 * Get URL column name when sorting or filtering.
	 * @return string|NULL
	 */
	public function GetUrlName ();
	
	/**
	 * Set URL column name when sorting or filtering.
	 * @param  string|NULL $urlName
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetUrlName ($urlName);

	/**
	 * Get URL helper to generate cell anchor URL.
	 * Boolean to enable/disable anchor url for filtering
	 * (`TRUE` by default) or string to declare row instance
	 * model public method to generate custom URL.
	 * @return bool|string|NULL
	 */
	public function GetUrlHelper ();

	/**
	 * Set URL helper to generate cell anchor URL.
	 * Boolean to enable/disable anchor url for filtering
	 * (`TRUE` by default) or string to declare row instance
	 * model public method to generate custom URL.
	 * @param  bool|string|NULL $urlHelper
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetUrlHelper ($urlHelper);

	/**
	 * Get datagrid column index, starting with `0`, optional.
	 * @return int|NULL
	 */
	public function GetColumnIndex ();

	/**
	 * Set datagrid column index, starting with `0`, optional.
	 * @param  int|NULL $columnIndex
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetColumnIndex ($columnIndex);
	
	/**
	 * Get default sorting definition with values `ASC | DESC` 
	 * or `TRUE | FALSE` to enable/disable sorting.
	 * @return string|bool|NULL
	 */
	public function GetSort ();
	
	/**
	 * Set default sorting definition with values `ASC | DESC` 
	 * or `TRUE | FALSE` to enable/disable sorting.
	 * @param  string|bool|NULL $sort
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
	 * @return \string[]|NULL
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
	 * @return array|NULL
	 */
	public function GetParserArgs ();
	
	/**
	 * Set property automatic parser arguments.
	 * @param  array|NULL $parserArgs
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetParserArgs ($parserArgs);
	
	/**
	 * Get property automatic formating arguments.
	 * @return array|NULL
	 */
	public function GetFormatArgs ();
	
	/**
	 * Set property automatic formating arguments.
	 * @param  array|NULL $formatArgs
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetFormatArgs ($formatArgs);
	
	/**
	 * Get property automatic formating view helper name.
	 * @return string|NULL
	 */
	public function GetViewHelper ();
	
	/**
	 * Set property automatic formating view helper name.
	 * @param  string|NULL $viewHelper
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetViewHelper ($viewHelper);

	/**
	 * Get column initial or current width, it can be defined 
	 * as integer for pixel value, float for flex value
	 * or string including `px` or `%` units.
	 * Width is used only for table grid type.
	 * @return string|int|float|NULL
	 */
	public function GetWidth ();

	/**
	 * Set column initial or current width, it can be defined 
	 * as integer for pixel value, float for flex value
	 * or string including pixels or percentage value.
	 * Width is used only for table grid type.
	 * @param  string|int|float|NULL $width
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetWidth ($width);

	/**
	 * Get column min. width, it can be defined as integer 
	 * or float for pixel value or string including `px` 
	 * or `%` units. Min. width is used only for table grid type.
	 * @return string|int|float|NULL
	 */
	public function GetMinWidth ();

	/**
	 * Set column min. width, it can be defined as integer 
	 * or float for pixel value or string including `px` 
	 * or `%` units. Min. width is used only for table grid type.
	 * @param  string|int|float|NULL $minWidth
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetMinWidth ($minWidth);
	
	/**
	 * Get column max. width, it can be defined as integer 
	 * or float for pixel value or string including `px` 
	 * or `%` units. Min. width is used only for table grid type.
	 * @return string|int|float|NULL
	 */
	public function GetMaxWidth ();

	/**
	 * Set column max. width, it can be defined as integer 
	 * or float for pixel value or string including `px` 
	 * or `%` units. Min. width is used only for table grid type.
	 * @param  string|int|float|NULL $maxWidth
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetMaxWidth ($maxWidth);

	/**
	 * Get column CSS `flex` value, it can be defined as single 
	 * integer or float value for `flex-grow` only or as string 
	 * value for full CSS `flex` shorthand to define `flex-grow`, 
	 * `flex-shrink` and `flex-basis`.
	 * @return string|int|float|NULL
	 */
	public function GetFlex ();

	/**
	 * Set column CSS `flex` value, it can be defined as single 
	 * integer or float value for `flex-grow` only or as string 
	 * value for full CSS `flex` shorthand to define `flex-grow`, 
	 * `flex-shrink` and `flex-basis`.
	 * @param  string|int|float|NULL $flex
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetFlex ($flex);

	/**
	 * Get column additional css classes for head cell and body cell.
	 * @return \string[]|NULL
	 */
	public function GetCssClasses ();

	/**
	 * Set column additional css classes for head cell and body cell.
	 * @param  \string[] $cssClasses
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetCssClasses ($cssClasses);
	
	/**
	 * Get `TRUE` for editable column, `FALSE|NULL` for not editable column (by default).
	 * @return bool|NULL
	 */
	public function GetEditable ();

	/**
	 * Set `TRUE` for editable column, `FALSE|NULL` for not editable column (by default).
	 * @param  bool|NULL $editable
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetEditable ($editable);
	
	/**
	 * Get `TRUE` for disabled column, `FALSE|NULL` for enabled column (enabled by default).
	 * @return bool|NULL
	 */
	public function GetDisabled ();

	/**
	 * Set `TRUE` for disabled column, `FALSE|NULL` for enabled column (enabled by default).
	 * @param  bool|NULL $disabled
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetDisabled ($disabled);
}
