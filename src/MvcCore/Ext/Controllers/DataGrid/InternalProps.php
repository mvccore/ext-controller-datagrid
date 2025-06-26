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

use \MvcCore\Ext\Controllers\DataGrid\IConstants;

/**
 * @mixin \MvcCore\Ext\Controllers\DataGrid
 */
trait InternalProps {
	
	/**
	 * Grid internal action default key.
	 * @internal
	 * @var string
	 */
	protected static $gridInitActionDefaultKey	= 'default';

	/**
	 * Grid model class interface.
	 * @var string
	 */
	protected static $modelInterface			= "\\MvcCore\\Ext\\Controllers\\DataGrids\\Models\\IGridModel";

	/**
	 * Row model class interface.
	 * @var string
	 */
	protected static $rowModelInterface			= "\\MvcCore\\Ext\\Controllers\\DataGrids\\Models\\IGridRow";

	/**
	 * Cache class to cache parsed columns configurations.
	 * Class has to implement `\MvcCore\Ext\ICache` or class
	 * has to implement static method `GetStore()` and instance 
	 * methods `Load()` and `Save()`.
	 * @var string
	 */
	protected static $cacheClass				= "\\MvcCore\\Ext\\Cache";

	/**
	 * Extended model class name.
	 * @var string
	 */
	protected static $extendedModelInterface	= "\\MvcCore\\Ext\\Models\\Db\\IModel";

	/**
	 * Tools class to generate development TS row model definitions.
	 * @var string
	 */
	protected static $toolsTsGeneratorClass		= "\\MvcCore\\Ext\\Tools\\TsGenerator";

	/**
	 * Internal datagrid actions.
	 * Keys are url values, values are local method names.
	 * @internal
	 * @var array
	 */
	protected static $gridInitActions = [
		IConstants::GRID_ACTION_FILTER_TABLE	=> 'TableFilterInit',
		IConstants::GRID_ACTION_FILTER_FORM		=> 'FormFilterInit',
		IConstants::GRID_ACTION_DEFAULT			=> 'DefaultInit',
	];
	
	/**
	 * Form extensions names and used class full names.
	 * @internal
	 * @var array
	 */
	protected static $formExtensionsClasses = [
		'mvccore/ext-form'						=> 'MvcCore\\Ext\\Form',
		'mvccore/ext-form-field-text'			=> 'MvcCore\\Ext\\Forms\Fields\\Text',
		'mvccore/ext-form-field-button'			=> 'MvcCore\\Ext\\Forms\Fields\\SubmitButton',
	];

	/**
	 * Regular expressions to validate raw client filter values against configured types.
	 * @var array
	 */
	protected static $filterValuesTypeValidators = [
		'string'	=> "#.*#",
		'int'		=> "#^([0-9\-\+]+)$#",
		'float'		=> "#^([0-9\.\-\+]+)$#",
		'bool'		=> "#^[01]{1}$#",
		'\Date'		=> "#^\d{4}\-\d{2}\-\d{2}$#",
		'\DateTime'	=> "#^\d{4}\-\d{2}\-\d{2} \d{2}\:\d{2}\:\d{2}(\.[\d]{1,6})?$#",
	];

	/**
	 * Custom form result state base value for grid 
	 * with table type with heading filter form.
	 * @internal
	 * @var int
	 */
	protected static $tableHeadingFilterFormClearResultBase = 10;
	
	/**
	 * Datagrid model property attribute full class name.
	 * @var string|\MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	protected static $attrClassFullName = '\\MvcCore\\Ext\\Controllers\\DataGrids\\Configs\\Column';

	/**
	 * Filter form field value prefixes to define operator.
	 * LIKE operators are used automatically only if filter value 
	 * contains not escaped percentage or underscore.
	 * @var array
	 */
	protected $filterOperatorPrefixes = [
		'NOT LIKE'	=> '!',
		'!='		=> '!',
		'>='		=> '>=',
		'<='		=> '<=',
		'>'			=> '>',
		'<'			=> '<',
		// empty prefixes has to be last
		'LIKE'		=> '',
		'='			=> '',
	];

	/**
	 * Grid instance creation place imprint from `debug_backtrace()`.
	 * @var string|null
	 */
	protected $creationPlaceImprint = NULL;

	/**
	 * Datagrid page, always initialized into integer value by URL.
	 * @internal
	 * @var int|NULL
	 */
	protected $page = NULL;

	/**
	 * Datagrid current rows count, always initialized into integer value by URL.
	 * @internal
	 * @var int|NULL
	 */
	protected $count = NULL;

	/**
	 * Database table offset, always initialized into integer value by URL.
	 * @internal
	 * @var int|NULL
	 */
	protected $offset = NULL;

	/**
	 * Database table select limit, initialized into integer or `NULL` value by URL.
	 * @internal
	 * @var int|NULL
	 */
	protected $limit = NULL;

	/**
	 * Calculated pages count by items per page and total count in database.
	 * @internal
	 * @var int|NULL
	 */
	protected $pagesCount = NULL;
	
	/**
	 * Paging items, completed after model total count has been loaded.
	 * @internal
	 * @var \MvcCore\Ext\Controllers\DataGrids\Iterators\Paging|NULL
	 */
	protected $paging = NULL;

	/**
	 * Initialized into `TRUE` if any translator callable defined.
	 * @internal
	 * @var bool|NULL
	 */
	protected $translate = NULL;

	/**
	 * Total items count in databse table, loaded from configured model.
	 * Value is initialized in `PreDispatch()` method automatically.
	 * @internal
	 * @var int|NULL
	 */
	protected $totalCount = NULL;

	/**
	 * Loaded current page data or page iterator.
	 * Value is initialized in template rendering by first call automatically.
	 * @internal
	 * @var array|\MvcCore\Ext\Models\Db\Readers\Streams\Iterator|NULL
	 */
	protected $pageData = NULL;
	
	/**
	 * Empty request instance only with `path` value, 
	 * used to route `<grid>` param internally and to build 
	 * `<grid>` param for `self` url back again.
	 * Define this object by your own externally only for your own risk.
	 * @internal
	 * @var \MvcCore\Request|NULL
	 */
	protected $gridRequest = NULL;

	/**
	 * All columns default allowed SQL operators and url segments by filtering mode configuration.
	 * Keys are (translated) url segments, values are `\stdClass`es with keys:
	 * - `operator` - string SQL operator to use
	 * - `multiple` - boolean if operatoc could have multiple values
	 * - `regex`    - NULL or string with regular expression applied to match the value(s).
	 * This collection is initialized internally.
	 * @internal
	 * @var array
	 */
	protected $defaultAllowedOperators = [];

	/**
	 * Eech column allowed SQL operators and url segments by filtering mode configuration.
	 * Keys are column properties names and values are array with keys 
	 * as (translated) url segments and values as `\stdClass`es with keys:
	 * - `operator` - string SQL operator to use
	 * - `multiple` - boolean if operatoc could have multiple values
	 * - `regex`    - NULL or string with regular expression applied to match the value(s).
	 * This collection is initialized internally.
	 * @internal
	 * @var array
	 */
	protected $columnsAllowedOperators = [];

	/**
	 * Internal grid init action method name.
	 * @internal
	 * @var string|NULL
	 */
	protected $gridInitAction = NULL;

	/**
	 * Application URL initialization processed boolean.
	 * @internal
	 * @var bool
	 */
	protected $appUrlCompletionInit = FALSE;

	/**
	 * Internal cache for view helpers to format 
	 * filter values from or into filter form fields.
	 * Keys are view helper names, values are view helper 
	 * instances or `\Closure` functions.
	 * @internal
	 * @var array
	 */
	protected $filteringViewHelpersCache = [];

	/**
	 * If `TRUE`, some column config has been chagned durring request
	 * and it's necessary to write into database or session.
	 * @internal
	 * @var bool
	 */
	protected $writeChangedColumnsConfigs = FALSE;

	/**
	 * If `TRUE`, row class implements extended model interface.
	 * @internal
	 * @var bool|NULL
	 */
	protected $rowClassIsExtendedModel = NULL;

}