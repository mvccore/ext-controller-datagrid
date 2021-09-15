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

namespace MvcCore\Ext\Controllers;

interface IDataGrid extends \MvcCore\Ext\Controllers\DataGrid\IConstants {

	/**
	 * Set model class instance.
	 * @requires
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Models\IGridModel $model 
	 * @return \MvcCore\Ext\Controllers\IDataGrid
	 */
	public function SetModel (\MvcCore\Ext\Controllers\DataGrids\Models\IGridModel $model);

	/**
	 * Get model class instance.
	 * @throws \InvalidArgumentException
	 * @return \MvcCore\Ext\Controllers\DataGrids\Models\IGridModel|NULL
	 */
	public function GetModel ($throwExceptionIfNull = FALSE);

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
	 * Set filtering mode to enable/disable columns filtering or to set filtering options:
	 * - Enable/disable filtering completelly:
	 *   - `\MvcCore\Ext\Controllers\IDataGrid::FILTER_DISABLED`
	 * - Enable single column filtering only:
	 *   - `\MvcCore\Ext\Controllers\IDataGrid::FILTER_SINGLE_COLUMN`
	 * - Enable multi columns filtering:
	 *   - `\MvcCore\Ext\Controllers\IDataGrid::FILTER_MULTIPLE_COLUMNS`
	 * - Enable columns filtering by range:
	 *   - `\MvcCore\Ext\Controllers\IDataGrid::FILTER_ALLOW_RANGES`
	 * - Enable columns filtering with like operator 
	 *   on right only, left only or anywhere:
	 *   - `\MvcCore\Ext\Controllers\IDataGrid::FILTER_ALLOW_LIKE_RIGHT_SIDE`
	 *   - `\MvcCore\Ext\Controllers\IDataGrid::FILTER_ALLOW_LIKE_LEFT_SIDE`
	 *   - `\MvcCore\Ext\Controllers\IDataGrid::FILTER_ALLOW_LIKE_ANYWHERE`
	 * By default, there is enabled to filter single column only.
	 * @param  int $filteringMode 
	 * @return \MvcCore\Ext\Controllers\IDataGrid
	 */
	public function SetFilteringMode ($filteringMode = \MvcCore\Ext\Controllers\IDataGrid::FILTER_SINGLE_COLUMN);

	/**
	 * Get filtering mode to enable/disable columns filtering or to set filtering options:
	 * - Enable/disable filtering completelly:
	 *   - `\MvcCore\Ext\Controllers\IDataGrid::FILTER_DISABLED`
	 * - Enable single column filtering only:
	 *   - `\MvcCore\Ext\Controllers\IDataGrid::FILTER_SINGLE_COLUMN`
	 * - Enable multi columns filtering:
	 *   - `\MvcCore\Ext\Controllers\IDataGrid::FILTER_MULTIPLE_COLUMNS`
	 * - Enable columns filtering by range:
	 *   - `\MvcCore\Ext\Controllers\IDataGrid::FILTER_ALLOW_RANGES`
	 * - Enable columns filtering with like operator 
	 *   on right only, left only or anywhere:
	 *   - `\MvcCore\Ext\Controllers\IDataGrid::FILTER_ALLOW_LIKE_RIGHT_SIDE`
	 *   - `\MvcCore\Ext\Controllers\IDataGrid::FILTER_ALLOW_LIKE_LEFT_SIDE`
	 *   - `\MvcCore\Ext\Controllers\IDataGrid::FILTER_ALLOW_LIKE_ANYWHERE`
	 * By default, there is enabled to filter single column only.
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
	 * Set system property to sort or filter also by not visible columns.
	 * `FALSE` (by default) means sorting or filtering will be only by visible columns.
	 * `TRUE` means sorting or filtering will be by any column.
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
	 * @return \MvcCore\Ext\Controllers\DataGrids\Forms\IFilterForm|\MvcCore\Ext\IForm|NULL
	 */
	public function GetControlFilterForm ();
	

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
	 * @return callable|NULL
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
	 * @param  \MvcCore\IRoute|NULL $route
	 * @return \MvcCore\Ext\Controllers\IDataGrid
	 */
	public function SetRoute (\MvcCore\IRoute $route);

	/**
	 * Get route instance for easy datagrid internal URL parsing 
	 * and datagrid internal URL compilation.
	 * This route is created internally by default.
	 * Define this route by your own externally only for your own risk.
	 * @return \MvcCore\IRoute|NULL
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
	 * @return \MvcCore\Ext\Controllers\DataGrids\Iterators\Columns|NULL
	 */
	public function GetConfigColumns ($activeOnly = TRUE);
	
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
	 * @return string|NULL
	 */
	public function GetControlText ($textKey);
	
	/**
	 * Get datagrid page, always initialized into integer value by URL.
	 * @return int|NULL
	 */
	public function GetPage ();

	/**
	 * Get database table offset, always initialized into integer value by URL.
	 * @return int|NULL
	 */
	public function GetOffset ();

	/**
	 * Get database table select limit, initialized into integer or `NULL` value by URL.
	 * @return int|NULL
	 */
	public function GetLimit ();
	
	/**
	 * Get calculated pages count by items per page and total count in database.
	 * @return int|NULL
	 */
	public function GetPagesCount ();

	/**
	 * Get paging items, completed after model total count has been loaded.
	 * @return \MvcCore\Ext\Controllers\DataGrids\Iterators\Paging|NULL
	 */
	public function GetPaging ();
	
	/**
	 * Get `TRUE` if any translator `callable|\Closure` defined.
	 * @return bool
	 */
	public function GetTranslate ();
	
	/**
	 * Get total items count in databse table, loaded from configured model.
	 * Value is initialized in `PreDispatch()` method automatically.
	 * @return int|NULL
	 */
	public function GetTotalCount ();
	
	/**
	 * Loaded current page data or page iterator.
	 * Value is initialized in template rendering by first call automatically.
	 * @return array|\MvcCore\Ext\Models\Db\Readers\Streams\Iterator|NULL
	 */
	public function GetPageData ();

	/**
	 * Get empty request instance only with `path` value, 
	 * used to route `<grid>` param internally and to build 
	 * `<grid>` param for `self` url back again.
	 * Define this object by your own externally only for your own risk.
	 * @return \MvcCore\IRequest
	 */
	public function GetGridRequest ();

	/**
	 * Generates url inside datagrid on target route.
	 * Params argument could contain `grid` key with array to define grid options
	 * and there could be key `page`, `count`, `sort` and `filter`.
	 * Page and count grid options are always integers.
	 * Sort option is array with keys as grid column properties names and with values as `ASC` or `DESC`.
	 * Filter option is array with keys as grid column properties names and with values as array
	 * with keys as allowed operator(s) and values as column filtering values.
	 * Page and count params are not checked for max. values in this URL completion.
	 * - By `"Controller:Action"` name and params array
	 *   (for routes configuration when routes array has keys with `"Controller:Action"` strings
	 *   and routes has not controller name and action name defined inside).
	 * - By route name and params array
	 *   (route name is key in routes configuration array, should be any string
	 *   but routes must have information about controller name and action name inside).
	 * Result address (url string) should have two forms:
	 * - Nice rewritten URL by routes configuration
	 *   (for apps with URL rewrite support (Apache `.htaccess` or IIS URL rewrite module)
	 *   and when first param is key in routes configuration array).
	 * - For all other cases is URL form like: `"index.php?controller=ctrlName&amp;action=actionName"`
	 *   (when first param is not founded in routes configuration array).
	 * 
	 * @param  string|NULL $controllerActionOrRouteName Should be `"Controller:Action"` combination or just any route name as custom specific string.
	 * @param  array       $params                      Optional, array with params, key is param name, value is param value.
	 * @throws \InvalidArgumentException                Grid doesn't contain given column name, unknown sort direction, unknown filter format...
	 * @return string
	 */
	public function Url ($controllerActionOrRouteName = NULL, array $params = []);

	/**
	 * Return grid URL. Method uses current controller route
	 * with `<grid>` param automatically completed by given array:
	 * First argument array could have keys and values:
	 * - `page`        - int|string|NULL - page value.
	 * - `count`       - int|string|NULL - items per page value.
	 * - `sort`        - string|NULL     - completed sorting `<grid>` param part string.
	 * - `filter`      - string|NULL     - completed filtering `<grid>` param part string.
	 * - `grid-action` - string|NULL     - internal grid action for filter form submits.
	 * @internal
	 * @return string
	 */
	public function GridUrl (array $gridParams = []);
	
	/**
	 * Complete datagrid URL with given database offset.
	 * Offset value is counted from zero.
	 * @param  int $offset
	 * @return string
	 */
	public function GridPageUrl ($offset);
	
	/**
	 * Complete datagrid URL with given items per page value.
	 * Of `$count` is zero, there is returned URL for unlimited items per page.
	 * @param  int $count
	 * @return string
	 */
	public function GridCountUrl ($count);

	/**
	 * Complete datagrid URL to sort datagrid by column config (or by column config 
	 * property name) and optional direction. Direction could be defined as string 
	 * `ASC` or `DESC`. If no direction is provided (`NULL`), there is used next 
	 * direction for given column. If there is provided direction as an empty string, 
	 * there is used no sorting for given column.
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\IColumn|string $columnConfigOrPropName 
	 * @param  string|NULL                                               $direction
	 * @return string
	 */
	public function GridSortUrl ($columnConfigOrPropName, $direction = NULL);
	
	/**
	 * Complete datagrid URL to filter datagrid by column config, operator 
	 * and single or multiple values. If given value is `NULL`, filtering is removed.
	 * @internal
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\IColumn|string $columnConfigOrPropName 
	 * @param  mixed                                                     $cellValue 
	 * @param  string                                                    $operator
	 * @return string
	 */
	public function GridFilterUrl ($columnConfigOrPropName, $cellValue, $operator = '=');

	/**
	 * Get column current sort direction as boolean. `TRUE` for `ASC` direction,
	 * `FALSE` for `DESC` direction and `NULL` for not sorted column.
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\IColumn|string $columnConfigOrPropName 
	 * @return bool|NULL
	 */
	public function GetColumnSortDirection ($columnConfigOrPropName);

	/**
	 * Get column sorting index (in multiple columns sorting).
	 * If column is not sorted, return `FALSE`.
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\IColumn|string $columnConfigOrPropName  
	 * @return int|FALSE
	 */
	public function GetColumnSortIndex ($columnConfigOrPropName);

	/**
	 * Get column filtering index (in multiple columns filtering).
	 * If column is not filtered, return `FALSE`.
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\IColumn|string $columnConfigOrPropName 
	 * @return int|FALSE
	 */
	public function GetColumnFilterIndex ($columnConfigOrPropName);

	/**
	 * Get new filtering from filter form submit values array.
	 * @param  array $formSubmitValues 
	 * @param  array $filtering 
	 * @return array
	 */
	public function GetFilteringFromFilterFormValues (array $formSubmitValues, array $filtering = []);
}
