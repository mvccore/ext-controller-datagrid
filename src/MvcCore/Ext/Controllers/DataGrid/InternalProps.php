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
	protected static $gridActionDefaultKey = 'default';

	/**
	 * Internal datagrid actions.
	 * Keys are url values, values are local method names.
	 * @internal
	 * @var array
	 */
	protected static $gridActions = [
		IConstants::GRID_ACTION_FILTER_TABLE	=> 'ActionTableFilter',
		IConstants::GRID_ACTION_FILTER_FORM		=> 'ActionFormFilter',
		IConstants::GRID_ACTION_DEFAULT			=> 'ActionDefault',
	];
	
	/**
	 * Form extensions names and used class full names.
	 * @internal
	 * @var array
	 */
	protected static $formExtensionsClasses = [
		'mvccore/ext-form'				=> 'MvcCore\\Ext\\Form',
		'mvccore/ext-form-field-text'	=> 'MvcCore\\Ext\\Forms\Fields\\Text',
		'mvccore/ext-form-field-button'	=> 'MvcCore\\Ext\\Forms\Fields\\SubmitButton',
	];

	/**
	 * Base ASCII chars to remove from filtering or sorting.
	 * Be carefull, this filtering doesn't prevent SQL injects!
	 * @internal
	 * @var array
	 */
	protected static $baseAsciiChars = [
		"\x00" => '', "\x08" => '', "\x10" => '', "\x18" => '',
		"\x01" => '', "\x09" => '', "\x11" => '', "\x19" => '',
		"\x02" => '', "\x0A" => '', "\x12" => '', "\x1A" => '',
		"\x03" => '', "\x0B" => '', "\x13" => '', "\x1B" => '',
		"\x04" => '', "\x0C" => '', "\x14" => '', "\x1C" => '',
		"\x05" => '', "\x0D" => '', "\x15" => '', "\x1D" => '',
		"\x06" => '', "\x0E" => '', "\x16" => '', "\x1E" => '',
		"\x07" => '', "\x0F" => '', "\x17" => '', "\x1F" => '',
	];

	/**
	 * Filter form field value prefixes to define operator.
	 * LIKE operators are used automatically only if filter value 
	 * contains not escaped percentage or underscore.
	 * @var array
	 */
	protected static $filterFormFieldValueOperatorPrefixes = [
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
	 * Originally configured grid value items per page.
	 * @internal
	 * @var int|NULL
	 */
	protected $itemsPerPageRouteConfig = NULL;

	/**
	 * Datagrid page, always initialized into integer value by URL.
	 * @internal
	 * @var int|NULL
	 */
	protected $page = NULL;

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
	 * Internal grid action method name.
	 * @internal
	 * @var string|NULL
	 */
	protected $gridAction = NULL;
	
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
	 * Query string param separator - used from application router, used only
	 * when route `<grid>` param is in query string.
	 * @internal
	 * @var string|NULL
	 */
	protected $queryStringParamsSepatator = NULL;

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
}