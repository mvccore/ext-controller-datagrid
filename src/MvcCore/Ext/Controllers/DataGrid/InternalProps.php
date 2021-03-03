<?php

namespace MvcCore\Ext\Controllers\DataGrid;

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
		'filter-table'	=> 'actionTableFilterSubmit',
		'filter-form'	=> 'actionFormFilterSubmit',
		'default'		=> 'actionDefault',
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
	 * Characters to prevent XSS attack and some other special chars
	 * what could be dangerous user input. Be carefull, this filtering
	 * doesn't prevent SQL injects!
	 * @internal
	 * @var \string[]
	 */
	protected static $specialMeaningChars = [
		'<'	=> "&#60;",
		'='	=> "&#61;",
		'>'	=> "&#62;",
		'['	=> "&#91;",
		'\\'=> "&#92;",
		']'	=> "&#93;",
		'`'	=> "&#96;",
		'{'	=> "&#123;",
		'|'	=> "&#124;",
		'}'	=> "&#125;",
	];

	/**
	 * Custom form result state base value for grid 
	 * with table type with heading filter form.
	 * @internal
	 * @var int
	 */
	protected static $tableHeadingFilterFormClearResultBase = 10;

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
	 * Allowed SQL operators and url segments by filtering mode configuration.
	 * Keys are (translated) url segments, values are `\stdClass`es with keys:
	 * - `operator` - string SQL operator to use
	 * - `multiple` - boolean if operatoc could have multiple values
	 * - `regex`    - NULL or string with regular expression applied to match the value(s).
	 * This collection is initialized internally.
	 * @internal
	 * @var array
	 */
	protected $allowedOperators = [];
	
	/**
	 * Paging items, completed after model total count has been loaded.
	 * @internal
	 * @var \MvcCore\Ext\Controllers\DataGrids\Iterators\Paging|NULL
	 */
	protected $paging = NULL;

	/**
	 * Initialized into `TRUE` if any translator callable defined.
	 * @internal
	 * @var bool
	 */
	protected $translate = FALSE;

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

}