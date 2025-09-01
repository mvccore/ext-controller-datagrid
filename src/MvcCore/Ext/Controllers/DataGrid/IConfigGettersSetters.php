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

namespace MvcCore\Ext\Controllers\DataGrid;

interface IConfigGettersSetters {

	/**
	 * Set model class instance.
	 * @requires
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Models\TGridModel $model 
	 * @return \MvcCore\Ext\Controllers\IDataGrid
	 */
	public function SetModel (\MvcCore\Ext\Controllers\DataGrids\Models\IGridModel $model);

	/**
	 * Get model class instance.
	 * @return ?\MvcCore\Ext\Controllers\DataGrids\Models\TGridModel
	 */
	public function GetModel ();

	/**
	 * Set row model class full name, not required, 
	 * if `NULL`, grid model class is used.
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Models\TGridRow|string|null $rowClass
	 * @param  int                                                            $propsFlags
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetRowClass (/*\MvcCore\Ext\Controllers\DataGrids\Models\IGridRow|string*/ $rowClass, $propsFlags = 0);

	/**
	 * Get row model class full name, not required, 
	 * if `NULL`, grid model class is used.
	 * @return \MvcCore\Ext\Controllers\DataGrids\Models\TGridRow|string|null
	 */
	public function GetRowClass ();

	/**
	 * Set items per page, `10` by default.
	 * @param  int $itemsPerPage
	 * @return \MvcCore\Ext\Controllers\IDataGrid
	 */
	public function SetItemsPerPage ($itemsPerPage);

	/**
	 * Get items per page, `10` by default.
	 * @return int
	 */
	public function GetItemsPerPage ();

	/**
	 * Set count control scales, `[10,100,1000,0]` by default. 
	 * Zero value (usually the last) means unlimited items per page.
	 * @param  \int[] $countScales
	 * @return \MvcCore\Ext\Controllers\IDataGrid
	 */
	public function SetCountScales (array $countScales);

	/**
	 * Get count control scales, `[10,100,1000,0]` by default. 
	 * Zero value (usually the last) means unlimited items per page.
	 * @return \int[]
	 */
	public function GetCountScales ();
	
	/**
	 * Set enabled/disabled custom items paer page value 
	 * defined in URL. Disabled by default.
	 * @param  bool $allowedCustomUrlCountScale
	 * @return \MvcCore\Ext\Controllers\IDataGrid
	 */
	public function SetAllowedCustomUrlCountScale ($allowedCustomUrlCountScale);

	/**
	 * Get enabled/disabled custom items paer page value 
	 * defined in URL. Disabled by default.
	 * @return bool
	 */
	public function GetAllowedCustomUrlCountScale ();

	/**
	 * Set sorting mode to disable columns sorting or to enable 
	 * only single column sort or to enable multi columns sort.
	 * Single column sort enabled by default.
	 * @param  int $sortingMode 
	 * @return \MvcCore\Ext\Controllers\IDataGrid
	 */
	public function SetSortingMode ($sortingMode = \MvcCore\Ext\Controllers\IDataGrid::SORT_MULTIPLE_COLUMNS);

	/**
	 * Get sorting mode to disable columns sorting or to enable 
	 * only single column sort or to enable multi columns sort.
	 * Single column sort enabled by default.
	 * @return int
	 */
	public function GetSortingMode ();

	/**
	 * Add filtering mode flag to enable/disable columns filtering or to set filtering options:
	 * - Enable/disable filtering completelly:
	 *   - `\MvcCore\Ext\Controllers\IDataGrid::FILTER_DISABLED`
	 * - Enable single column filtering only:
	 *   - `\MvcCore\Ext\Controllers\IDataGrid::FILTER_SINGLE_COLUMN`
	 * - Enable multi columns filtering:
	 *   - `\MvcCore\Ext\Controllers\IDataGrid::FILTER_MULTIPLE_COLUMNS`
	 * - Enable columns filtering by equal and not equal:
	 *   - `\MvcCore\Ext\Controllers\IDataGrid::FILTER_ALLOW_EQUALS`
	 * - Enable columns filtering by range operators:
	 *   - `\MvcCore\Ext\Controllers\IDataGrid::FILTER_ALLOW_RANGES`
	 * - Enable columns filtering with like operator 
	 *   on right only, left only or anywhere:
	 *   - `\MvcCore\Ext\Controllers\IDataGrid::FILTER_ALLOW_LIKE_RIGHT_SIDE`
	 *   - `\MvcCore\Ext\Controllers\IDataGrid::FILTER_ALLOW_LIKE_LEFT_SIDE`
	 *   - `\MvcCore\Ext\Controllers\IDataGrid::FILTER_ALLOW_LIKE_ANYWHERE`
	 * By default, there is enabled to filter single column only with equals and ranges operators.
	 * @param  int $filteringMode 
	 * @return \MvcCore\Ext\Controllers\IDataGrid
	 */
	public function AddFilteringMode ($filteringMode = \MvcCore\Ext\Controllers\IDataGrid::FILTER_ALLOW_LIKE_RIGHT_SIDE);
	
	/**
	 * Remove filtering mode flag to enable/disable columns filtering or to set filtering options:
	 * - Enable/disable filtering completelly:
	 *   - `\MvcCore\Ext\Controllers\IDataGrid::FILTER_DISABLED`
	 * - Enable single column filtering only:
	 *   - `\MvcCore\Ext\Controllers\IDataGrid::FILTER_SINGLE_COLUMN`
	 * - Enable multi columns filtering:
	 *   - `\MvcCore\Ext\Controllers\IDataGrid::FILTER_MULTIPLE_COLUMNS`
	 * - Enable columns filtering by equal and not equal:
	 *   - `\MvcCore\Ext\Controllers\IDataGrid::FILTER_ALLOW_EQUALS`
	 * - Enable columns filtering by range operators:
	 *   - `\MvcCore\Ext\Controllers\IDataGrid::FILTER_ALLOW_RANGES`
	 * - Enable columns filtering with like operator 
	 *   on right only, left only or anywhere:
	 *   - `\MvcCore\Ext\Controllers\IDataGrid::FILTER_ALLOW_LIKE_RIGHT_SIDE`
	 *   - `\MvcCore\Ext\Controllers\IDataGrid::FILTER_ALLOW_LIKE_LEFT_SIDE`
	 *   - `\MvcCore\Ext\Controllers\IDataGrid::FILTER_ALLOW_LIKE_ANYWHERE`
	 * By default, there is enabled to filter single column only with equals and ranges operators.
	 * @param  int $filteringMode 
	 * @return \MvcCore\Ext\Controllers\IDataGrid
	 */
	public function RemoveFilteringMode ($filteringMode = \MvcCore\Ext\Controllers\IDataGrid::FILTER_ALLOW_LIKE_ANYWHERE);

	/**
	 * Set filtering mode flags to enable/disable columns filtering or to set filtering options:
	 * - Enable/disable filtering completelly:
	 *   - `\MvcCore\Ext\Controllers\IDataGrid::FILTER_DISABLED`
	 * - Enable single column filtering only:
	 *   - `\MvcCore\Ext\Controllers\IDataGrid::FILTER_SINGLE_COLUMN`
	 * - Enable multi columns filtering:
	 *   - `\MvcCore\Ext\Controllers\IDataGrid::FILTER_MULTIPLE_COLUMNS`
	 * - Enable columns filtering by equal and not equal:
	 *   - `\MvcCore\Ext\Controllers\IDataGrid::FILTER_ALLOW_EQUALS`
	 * - Enable columns filtering by range operators:
	 *   - `\MvcCore\Ext\Controllers\IDataGrid::FILTER_ALLOW_RANGES`
	 * - Enable columns filtering with like operator 
	 *   on right only, left only or anywhere:
	 *   - `\MvcCore\Ext\Controllers\IDataGrid::FILTER_ALLOW_LIKE_RIGHT_SIDE`
	 *   - `\MvcCore\Ext\Controllers\IDataGrid::FILTER_ALLOW_LIKE_LEFT_SIDE`
	 *   - `\MvcCore\Ext\Controllers\IDataGrid::FILTER_ALLOW_LIKE_ANYWHERE`
	 * By default, there is enabled to filter single column only with equals and ranges operators.
	 * @param  int $filteringMode 
	 * @return \MvcCore\Ext\Controllers\IDataGrid
	 */
	public function SetFilteringMode ($filteringMode = \MvcCore\Ext\Controllers\IDataGrid::FILTER_ALLOW_DEFAULT);

	/**
	 * Get filtering mode flags to enable/disable columns filtering or to set filtering options:
	 * - Enable/disable filtering completelly:
	 *   - `\MvcCore\Ext\Controllers\IDataGrid::FILTER_DISABLED`
	 * - Enable single column filtering only:
	 *   - `\MvcCore\Ext\Controllers\IDataGrid::FILTER_SINGLE_COLUMN`
	 * - Enable multi columns filtering:
	 *   - `\MvcCore\Ext\Controllers\IDataGrid::FILTER_MULTIPLE_COLUMNS`
	 * - Enable columns filtering by equal and not equal:
	 *   - `\MvcCore\Ext\Controllers\IDataGrid::FILTER_ALLOW_EQUALS`
	 * - Enable columns filtering by range operators:
	 *   - `\MvcCore\Ext\Controllers\IDataGrid::FILTER_ALLOW_RANGES`
	 * - Enable columns filtering with like operator 
	 *   on right only, left only or anywhere:
	 *   - `\MvcCore\Ext\Controllers\IDataGrid::FILTER_ALLOW_LIKE_RIGHT_SIDE`
	 *   - `\MvcCore\Ext\Controllers\IDataGrid::FILTER_ALLOW_LIKE_LEFT_SIDE`
	 *   - `\MvcCore\Ext\Controllers\IDataGrid::FILTER_ALLOW_LIKE_ANYWHERE`
	 * By default, there is enabled to filter single column only with equals and ranges operators.
	 * @return int
	 */
	public function GetFilteringMode ();

	/**
	 * Set datagrid table sorting, initialized by URL, keys are configured 
	 * database column names and values are sorting direction strings - `ASC | DESC`.
	 * Define those values by your own externally only for your own risk.
	 * @param  array $sorting
	 * @return \MvcCore\Ext\Controllers\IDataGrid
	 */
	public function SetSorting (array $sorting);
	
	/**
	 * Get datagrid table sorting, initialized by URL, keys are configured 
	 * database column names and values are sorting direction strings - `ASC | DESC`.
	 * @return array
	 */
	public function GetSorting ();
	
	/**
	 * Set datagrid table filtering, initialized by URL, keys are configured 
	 * database column names and values are arrays. Each key in value array is
	 * allowed operator and values are values to filter on defined column.
	 * Define those values by your own externally only for your own risk.
	 * @param  array $filtering
	 * @return \MvcCore\Ext\Controllers\IDataGrid
	 */
	public function SetFiltering (array $filtering);
	
	/**
	 * Get datagrid table filtering, initialized by URL, keys are configured 
	 * database column names and values are arrays. Each key in value array is
	 * allowed operator and values are values to filter on defined column.
	 * @return array
	 */
	public function GetFiltering ();

	/**
	 * Set system property to sort or filter by URL param also by not currently visible columns.
	 * `FALSE` (by default) means sorting or filtering by URL will be only by currently visible columns.
	 * `TRUE` means sorting or filtering by URL will be by any column and if column in URL is
	 * not currently visible, it will be enabled automatically.
	 * @param  bool $ignoreDisabledColumns
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetIgnoreDisabledColumns ($ignoreDisabledColumns);
	
	/**
	 * Get system property to sort or filter also by not visible columns.
	 * `FALSE` (by default) means sorting or filtering will be only by visible columns.
	 * `TRUE` means sorting or filtering will be by any column.
	 * @return bool
	 */
	public function GetIgnoreDisabledColumns ();
	
	/**
	 * Set custom filter form instance, implementing interfaces:
	 * - `\MvcCore\Ext\Controllers\DataGrids\Forms\IFilterForm`
	 * - `\MvcCore\Ext\IForm`
	 * Form has to return filtering array configuration by `GetValues()` 
	 * method of by `Submit()` method in second position.
	 * There is no custom filter form by default.
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Forms\IFilterForm|\MvcCore\Ext\IForm $translator
	 * @return \MvcCore\Ext\Controllers\IDataGrid
	 */
	public function SetControlFilterForm (\MvcCore\Ext\Controllers\DataGrids\Forms\IFilterForm $filterForm);
	
	/**
	 * Get custom filter form instance, implementing interfaces:
	 * - `\MvcCore\Ext\Controllers\DataGrids\Forms\IFilterForm`
	 * - `\MvcCore\Ext\IForm`
	 * Form has to return filtering array configuration by `GetValues()` 
	 * method of by `Submit()` method in second position.
	 * There is no custom filter form by default.
	 * @return \MvcCore\Ext\Controllers\DataGrids\Forms\IFilterForm|\MvcCore\Ext\IForm|null
	 */
	public function GetControlFilterForm ();

	/**
	 * Set cache instance or `FALSE` to disable cache.
	 * @param  \MvcCore\Ext\ICache|FALSE|null $cache
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetCache ($cache);
	
	/**
	 * Get cache instance or `FALSE` if cache is disabled.
	 * If cache is `NULL`, cache is automatically initialized.
	 * @return \MvcCore\Ext\ICache|FALSE|null
	 */
	public function GetCache ();
	
	/**
	 * Set translator instance. Any callable accepting first argument
	 * as string translation key and second argument as array with replacements.
	 * There is no translator by default.
	 * @param  callable|\Closure $translator
	 * @return \MvcCore\Ext\Controllers\IDataGrid
	 */
	public function SetTranslator ($translator);
	
	/**
	 * Get translator instance. Any callable accepting first argument
	 * as string translation key and second argument as array with replacements.
	 * There is no translator by default.
	 * @return ?callable
	 */
	public function GetTranslator ();
	
	/**
	 * Set `TRUE` to translate also columns names in URL adresses.
	 * `FALSE` by default to not translate those values.
	 * To translate those values, you have also to provide translator.
	 * @param  bool $translateUrlNames
	 * @return \MvcCore\Ext\Controllers\IDataGrid
	 */
	public function SetTranslateUrlNames ($translateUrlNames);
	
	/**
	 * Get `TRUE` to translate also columns names in URL adresses.
	 * `FALSE` by default to not translate those values.
	 * To translate those values, you have also to provide translator.
	 * @return bool
	 */
	public function GetTranslateUrlNames ();

	/**
	 * Set route instance for easy datagrid internal URL parsing 
	 * and datagrid internal URL compilation.
	 * This route is created internally by default.
	 * Define this route by your own externally only for your own risk.
	 * @param  ?\MvcCore\IRoute $route
	 * @return \MvcCore\Ext\Controllers\IDataGrid
	 */
	public function SetRoute (\MvcCore\IRoute $route);

	/**
	 * Get route instance for easy datagrid internal URL parsing 
	 * and datagrid internal URL compilation.
	 * This route is created internally by default.
	 * Define this route by your own externally only for your own risk.
	 * @return ?\MvcCore\IRoute
	 */
	public function GetRoute ();

	/**
	 * Set application route name used to build 
	 * application url adresses, `self` by default.
	 * @param  string $appRouteName 
	 * @return \MvcCore\Ext\Controllers\IDataGrid
	 */
	public function SetAppRouteName ($appRouteName);
	
	/**
	 * Get application route name used to build 
	 * application url adresses, `self` by default.
	 * @return string
	 */
	public function GetAppRouteName ();

	/**
	 * Set URL params parsed automatically from URL inside datagrid component.
	 * Define those values by your own externally only for your own risk.
	 * @param  array $urlParams
	 * @return \MvcCore\Ext\Controllers\IDataGrid
	 */
	public function SetUrlParams (array $urlParams);

	/**
	 * Get URL params parsed automatically from URL inside datagrid component.
	 * Define those values by your own externally only for your own risk.
	 * @return array
	 */
	public function GetUrlParams ();

	/**
	 * Set configuration object for datagrid URL segments.
	 * You can easily configure datagrid component URL 
	 * parts by providing this object custom instance.
	 * This object is created automatically by default if not provided.
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments $configUrlSegments
	 * @return \MvcCore\Ext\Controllers\IDataGrid
	 */
	public function SetConfigUrlSegments (\MvcCore\Ext\Controllers\DataGrids\Configs\IUrlSegments $configUrlSegments);

	/**
	 * Get configuration object for datagrid URL segments.
	 * You can easily configure datagrid component URL 
	 * parts by providing this object custom instance.
	 * This object is created automatically by default if not provided.
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function GetConfigUrlSegments ();
	
	/**
	 * Set configuration object for datagrid parts, style and controls rendering.
	 * You can easily configure datagrid component parts, style 
	 * and controls by providing this object custom instance.
	 * This object is created automatically by default if not provided.
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $configRendering
	 * @return \MvcCore\Ext\Controllers\IDataGrid
	 */
	public function SetConfigRendering (\MvcCore\Ext\Controllers\DataGrids\Configs\IRendering $configRendering);

	/**
	 * Get configuration object for datagrid parts, style and controls rendering.
	 * You have to easily configure datagrid component parts, style 
	 * and controls by providing this object custom instance.
	 * This object is created automatically by default if not provided.
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function GetConfigRendering ();
	
	/**
	 * Set configuration array/iterator to define datagrid columns.
	 * You have to define datagrid columns by this columns array declaration or by 
	 * model class properties decoration. Model has to implementing interface
	 * `\MvcCore\Ext\Controllers\DataGrids\Models\IGridColumns`
	 * to create this iterator automatically from decorated properties.
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\IColumn[]|\MvcCore\Ext\Controllers\DataGrids\Iterators\Columns $configRendering
	 * @return \MvcCore\Ext\Controllers\IDataGrid
	 */
	public function SetConfigColumns ($configColumns);

	/**
	 * Get configuration iterator to define datagrid columns.
	 * You can define datagrid columns by this columns array declaration or by 
	 * model class properties decoration. Model has to implementing interface
	 * `\MvcCore\Ext\Controllers\DataGrids\Models\IGridColumns`
	 * to create this iterator automatically from decorated properties.
	 * @param  bool $activeOnly `TRUE` by default.
	 * @return ?\MvcCore\Ext\Controllers\DataGrids\Iterators\Columns
	 */
	public function GetConfigColumns ($activeOnly = TRUE);
	
	

	/**
	 * Set grid controls visible texts.
	 * Keys are used as pointers, values could be configured 
	 * into any text values. This array is translated 
	 * automatically by provided translator.
	 * @param  array $controlsTexts
	 * @return \MvcCore\Ext\Controllers\IDataGrid
	 */
	public function SetControlsTexts ($controlsTexts);
	
	/**
	 * Get grid controls visible texts.
	 * Keys are used as pointers, values could be configured 
	 * into any text values. This array is translated 
	 * automatically by provided translator.
	 * @return array
	 */
	public function GetControlsTexts ();
	
	/**
	 * Get grid control visible text value.
	 * Key is pointer into texts array, result
	 * is configured text or translated text
	 * if there is provided translator.
	 * @return ?string
	 */
	public function GetControlText ($textKey);

	/**
	 * Set internal table heading filter form instance in grid table type.
	 * This object is created automatically by default if not provided.
	 * Define this form instance by your own externally only for your own risk.
	 * @param  \MvcCore\Ext\IForm $tableHeadFilterForm
	 * @return \MvcCore\Ext\Controllers\IDataGrid
	 */
	public function SetTableHeadFilterForm ($tableHeadFilterForm);
	
	/**
	 * Get internal table heading filter form instance in grid table type.
	 * This object is created automatically by default if not provided.
	 * Define this form instance by your own externally only for your own risk.
	 * @return \MvcCore\Ext\IForm
	 */
	public function GetTableHeadFilterForm ();

	/**
	 * Get filter Form input delimiter between multiple values.
	 * Default value is `;`.
	 * @return string
	 */
	public function GetFilterFormValuesDelimiter ();
	
	/**
	 * Set filter Form input delimiter between multiple values.
	 * Default value is `;`.
	 * @param  string $filterFormValuesDelimiter
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetFilterFormValuesDelimiter ($filterFormValuesDelimiter);

	/**
	 * Get custom handler to define client TS row model generating.
	 * @return \MvcCore\Ext\Controllers\DataGrids\IClientRowModelDefinitionHandler|callable|null
	 */
	public function GetHandlerClientRowModelDefinition ();

	/**
	 * Set custom handler to define client TS row model generating.
	 * @param  \MvcCore\Ext\Controllers\DataGrids\IClientRowModelDefinitionHandler|callable|null $handlerClientRowModelDefinition
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetHandlerClientRowModelDefinition ($handlerClientRowModelDefinition);

	/**
	 * Get column PHP code types and technicaly possible 
	 * filtering modes against database.
	 * @return array<string, int[]>
	 */
	public function GetTypesPossibleFilterFlags ();
	
	/**
	 * Set column PHP code types and technicaly possible 
	 * filtering modes against database.
	 * @param  array<string, int[]> $typesPossibleFilterFlags
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetTypesPossibleFilterFlags ($typesPossibleFilterFlags);

	/**
	 * Add datagrid html wrapper element css class.
	 * There is defined css class `grid` by default and in `PreDispatch()` 
	 * method, there is automatically added css class `grid-type-(table|grid)`.
	 * @param  string|\string[] $cssClasses
	 * @return \MvcCore\Ext\Controllers\IDataGrid
	 */
	public function AddCssClasses ($cssClasses);
	
	/**
	 * Set datagrid html wrapper element css classes.
	 * There is defined css class `grid` by default and in `PreDispatch()` 
	 * method, there is automatically added css class `grid-type-(table|grid)`.
	 * @param  string|\string[] $cssClasses
	 * @return \MvcCore\Ext\Controllers\IDataGrid
	 */
	public function SetCssClasses ($cssClasses);
	
	/**
	 * Get datagrid html wrapper element css classes.
	 * There is defined css class `grid` by default and in `PreDispatch()` 
	 * method, there is automatically added css class `grid-type-(table|grid)`.
	 * @return \string[]
	 */
	public function GetCssClasses ();

	/**
	 * Add datagrid html wrapper element html attributes.
	 * @param  array|array<string,string> $containerAttrs
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function AddContainerAttrs ($containerAttrs);
	
	/**
	 * Set datagrid html wrapper element html attributes.
	 * @param  array|array<string,string> $containerAttrs
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetContainerAttrs ($containerAttrs);

	/**
	 * Get datagrid html wrapper element html attributes.
	 * @return array|array<string,string>
	 */
	public function GetContainerAttrs ();

}