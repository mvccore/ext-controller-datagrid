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

interface IInternalGettersSetters {

	/**
	 * Get filter form field value prefixes to define operator.
	 * LIKE operators are used automatically only if filter value 
	 * contains not escaped percentage or underscore.
	 * @return array
	 */
	public function GetFilterOperatorPrefixes ();
	
	/**
	 * Get datagrid page, always initialized into integer value by URL.
	 * @return ?int
	 */
	public function GetPage ();
	
	/**
	 * Get datagrid current rows count, always initialized into integer value by URL.
	 * @return ?int
	 */
	public function GetCount ();

	/**
	 * Get database table offset, always initialized into integer value by URL.
	 * @return ?int
	 */
	public function GetOffset ();

	/**
	 * Get database table select limit, initialized into integer or `NULL` value by URL.
	 * @return ?int
	 */
	public function GetLimit ();
	
	/**
	 * Get calculated pages count by items per page and total count in database.
	 * @return ?int
	 */
	public function GetPagesCount ();

	/**
	 * Get paging items, completed after model total count has been loaded.
	 * @return ?\MvcCore\Ext\Controllers\DataGrids\Iterators\Paging
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
	 * @return ?int
	 */
	public function GetTotalCount ();
	
	/**
	 * Loaded current page data or page iterator.
	 * Value is initialized in template rendering by first call automatically.
	 * @return array|\MvcCore\Ext\Models\Db\Readers\Streams\Iterator|null
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
	 * @param  ?string $controllerActionOrRouteName Should be `"Controller:Action"` combination or just any route name as custom specific string.
	 * @param  array   $params                      Optional, array with params, key is param name, value is param value.
	 * @throws \InvalidArgumentException            Grid doesn't contain given column name, unknown sort direction, unknown filter format...
	 * @return string
	 */
	public function Url ($controllerActionOrRouteName = NULL, array $params = []);

	/**
	 * Return grid URL. Method uses current controller route
	 * with `<grid>` param automatically completed by given array:
	 * First argument array could have keys and values:
	 * - `page`        - int|string|null - page value.
	 * - `count`       - int|string|null - items per page value.
	 * - `sort`        - ?string         - completed sorting `<grid>` param part string.
	 * - `filter`      - ?string         - completed filtering `<grid>` param part string.
	 * - `grid-action` - ?string         - internal grid action for filter form submits.
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
	 * @param  ?string                                                   $direction
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
	 * @return ?bool
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
	 * Check if given value contains any LIKE/NOT LIKE special 
	 * character: `%` or `_` or escaped like this: `[%]` or `[_]`.
	 * Returns `0` if no special char `%` or `_` matched.
	 * Returns `1` if special char `%` or `_` matched in raw form only, not escaped.
	 * Returns `2` if special char `%` or `_` matched in escaped form only.
	 * Returns `1 | 2` if special char `%` or `_` matched in both forms.
	 * @param  string $rawValue 
	 * @param  string $specialLikeChar 
	 * @return int
	 */
	public function CheckFilterValueForSpecialLikeChar ($rawValue, $specialLikeChar);
	
	/**
	 * Return columns configs cache key and cache tags.
	 * @return array|[string, \string[]]
	 */
	public function GetGridCacheKeyAndTags ();

}