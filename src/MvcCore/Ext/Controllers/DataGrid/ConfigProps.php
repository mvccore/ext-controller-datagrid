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

/**
 * @mixin \MvcCore\Ext\Controllers\DataGrid
 */
trait ConfigProps {
	
	/**
	 * Model class instance, required configuration property.
	 * @var \MvcCore\Ext\Controllers\DataGrids\Models\IGridModel
	 */
	protected $model = NULL;

	/**
	 * Row model class full name, not required, 
	 * if `NULL`, grid model class is used.
	 * @var string|NULL
	 */
	protected $rowClass = NULL;

	/**
	 * Row model class properties flags, not required, `0` by default.
	 * @var string|NULL
	 */
	protected $rowClassPropsFlags = 0;

	/**
	 * Items per page, `10` by default.
	 * @var int
	 */
	protected $itemsPerPage = self::ITEMS_PER_PAGE_DEFAULT;
	
	/**
	 * Count control scales, `[10,100,1000,0]` by default. 
	 * Zero value (usually the last) means unlimited items per page.
	 * @var \int[]|string
	 */
	protected $countScales = self::COUNTS_SCALE_DEFAULT;
	
	/**
	 * Enabled/disabled custom items paer page value 
	 * defined in URL. Disabled by default.
	 * @var bool
	 */
	protected $allowedCustomUrlCountScale = FALSE;

	/**
	 * Sorting mode to disable columns sorting or to enable 
	 * only single column sort or to enable multi columns sort.
	 * Single column sort enabled by default.
	 * @var int
	 */
	protected $sortingMode = self::SORT_SINGLE_COLUMN;

	/**
	 * Filtering mode flags to enable/disable columns filtering or to set filtering options:
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
	 * @var int
	 */
	protected $filteringMode = self::FILTER_ALLOW_DEFAULT;
	
	/**
	 * Datagrid table sorting, initialized by URL, keys are configured 
	 * database column names and values are sorting direction strings - `ASC | DESC`.
	 * @internal
	 * @var array
	 */
	protected $sorting = [];

	/**
	 * Datagrid table filtering, initialized by URL, keys are configured 
	 * database column names and values are arrays. Each key in value array is
	 * allowed operator and values are values to filter on defined column.
	 * @internal
	 * @var array
	 */
	protected $filtering = [];

	/**
	 * System property to sort or filter also by not visible columns.
	 * `FALSE` (by default) means sorting or filtering will be only by visible columns.
	 * `TRUE` means sorting or filtering will be by any column.
	 * @var bool
	 */
	protected $ignoreDisabledColumns = FALSE;

	/**
	 * Custom filter form instance, implementing interfaces:
	 * - `\MvcCore\Ext\Controllers\DataGrids\Forms\IFilterForm`
	 * - `\MvcCore\Ext\IForm`
	 * Form has to return filtering array configuration by `GetValues()` 
	 * method of by `Submit()` method in second position.
	 * There is no custom filter form by default.
	 * @var \MvcCore\Ext\Controllers\DataGrids\Forms\IFilterForm|\MvcCore\Ext\IForm|NULL
	 */
	protected $controlFilterForm = NULL;

	/**
	 * MvcCore cache extension instance or false for disabled or not installed cache.
	 * @var \MvcCore\Ext\ICache|FALSE|NULL
	 */
	protected $cache = NULL;

	/**
	 * Translator instance. Any callable accepting first argument
	 * as string translation key and second argument as array with replacements.
	 * There is no translator by default.
	 * @var callable|\Closure
	 */
	protected $translator = NULL;

	/**
	 * Boolean to translate also columns names in URL adresses.
	 * `FALSE` by default to not translate those values.
	 * To translate those values, you have also to provide translator.
	 * @var bool
	 */
	protected $translateUrlNames = FALSE;

	/**
	 * Route instance for easy datagrid internal URL parsing 
	 * and datagrid internal URL compilation.
	 * This route is created internally by default.
	 * Define this route by your own externally only for your own risk.
	 * @var \MvcCore\Route|NULL
	 */
	protected $route = NULL;

	/**
	 * Application route name used to build application 
	 * url adresses, `self` by default.
	 * @var string
	 */
	protected $appRouteName = 'self';

	/**
	 * URL params parsed automatically from URL inside datagrid component.
	 * Define those values by your own externally only for your own risk.
	 * @var array|NULL
	 */
	protected $urlParams = NULL;

	/**
	 * Configuration object for datagrid URL segments.
	 * You can easily configure datagrid component URL 
	 * parts by providing this object custom instance.
	 * This object is created automatically by default if not provided.
	 * @var \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments|NULL
	 */
	protected $configUrlSegments = NULL;

	/**
	 * Configuration object for datagrid parts, style and controls rendering.
	 * You can easily configure datagrid component parts, style 
	 * and controls by providing this object custom instance.
	 * This object is created automatically by default if not provided.
	 * @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering|NULL
	 */
	protected $configRendering = NULL;

	/**
	 * Configuration iterator to define datagrid columns.
	 * You have to define datagrid columns by this iterator declaration or by 
	 * model class properties decoration. Model has to implementing interface
	 * `\MvcCore\Ext\Controllers\DataGrids\Models\IGridColumns`.
	 * @var \MvcCore\Ext\Controllers\DataGrids\Iterators\Columns|NULL
	 */
	protected $configColumns = NULL;
	
	/**
	 * Datagrid html wrapper element css class attribute value.
	 * There is defined css class `grid` by default and in `PreDispatch()` 
	 * method, there is automatically added css class `grid-type-(table|grid)`.
	 * @var \string[]
	 */
	protected $cssClasses = ['grid'];

	/**
	 * Datagrid html wrapper element html attributes.
	 * @var array|array<string, string>
	 */
	protected $containerAttrs = [];
	
	/**
	 * Internal table heading filter form instance in grid table type.
	 * This object is created automatically by default if not provided.
	 * Define this form instance by your own externally only for your own risk.
	 * @var \MvcCore\Ext\Form|NULL
	 */
	protected $tableHeadFilterForm = NULL;
	
	/**
	 * Filter Form input delimiter between multiple values.
	 * Default value is `;`.
	 * @var string
	 */
	protected $filterFormValuesDelimiter = ';';

	/**
	 * Custom handler to define client TS row model generating.
	 * @var \MvcCore\Ext\Controllers\DataGrids\IClientRowModelDefinitionHandler|callable|NULL
	 */
	protected $handlerClientRowModelDefinition = NULL;
	
	/**
	 * Column PHP code types and technicaly possible 
	 * filtering modes against database.
	 * @var array<string, int[]>
	 */
	protected $typesPossibleFilterFlags = [
		'string'		=> [
			self::FILTER_ALLOW_EQUALS, 
			self::FILTER_ALLOW_RANGES,
			self::FILTER_ALLOW_LIKE_RIGHT_SIDE,
			self::FILTER_ALLOW_LIKE_LEFT_SIDE,
			self::FILTER_ALLOW_LIKE_ANYWHERE,
		],
		'bool'			=> [
			self::FILTER_ALLOW_EQUALS,
		],
		'int'			=> [
			self::FILTER_ALLOW_EQUALS, 
			self::FILTER_ALLOW_RANGES,
			self::FILTER_ALLOW_LIKE_RIGHT_SIDE,
			self::FILTER_ALLOW_LIKE_LEFT_SIDE,
			self::FILTER_ALLOW_LIKE_ANYWHERE,
		],
		'float'			=> [
			self::FILTER_ALLOW_EQUALS, 
			self::FILTER_ALLOW_RANGES,
			self::FILTER_ALLOW_LIKE_RIGHT_SIDE,
			self::FILTER_ALLOW_LIKE_LEFT_SIDE,
			self::FILTER_ALLOW_LIKE_ANYWHERE,
		],
		'DateTime'	=> [
			self::FILTER_ALLOW_EQUALS, 
			self::FILTER_ALLOW_RANGES,
			self::FILTER_ALLOW_LIKE_RIGHT_SIDE,
			self::FILTER_ALLOW_LIKE_LEFT_SIDE,
			self::FILTER_ALLOW_LIKE_ANYWHERE,
		],
		'DateTimeImmutable'	=> [
			self::FILTER_ALLOW_EQUALS, 
			self::FILTER_ALLOW_RANGES,
			self::FILTER_ALLOW_LIKE_RIGHT_SIDE,
			self::FILTER_ALLOW_LIKE_LEFT_SIDE,
			self::FILTER_ALLOW_LIKE_ANYWHERE,
		],
	];

	/**
	 * Grid controls visible texts.
	 * Keys are used as pointers, values could be configured 
	 * into any text values. This array is translated 
	 * automatically by provided translator.
	 * @var array
	 */
	protected $controlsTexts = [
		'pagingControlPrevious'	=> 'Previous',
		'pagingControlNext'		=> 'Next',
		'pagingControlFirst'	=> 'First',
		'pagingControlLast'		=> 'Last ({0})',
		'scalesControlAll'		=> 'All',
		'filterFormPlaceHolder'	=> 'Enter to search',
		'filterFormFilter'		=> 'Filter',
		'filterFormClear'		=> 'Clear',
		'statusPages'			=> 'page {0}/{1}',
		'statusRowsAll'			=> 'total rows: {0}',
		'statusRowsFiltered'	=> 'filtered rows: {0}',
		'titlePage'				=> 'page: {0}',
		'titleScale'			=> 'scale: {0}',
		'titleSort'				=> 'sort: {0}',
		'titleFilter'			=> 'filter: {0}',
		'refresh'				=> 'refresh',
	];
}