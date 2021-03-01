<?php

namespace MvcCore\Ext\Controllers\DataGrid;

trait InternalProps {
	
	/**
	 * Internal datagrid actions.
	 * Keys are url values, values are local method names.
	 * @var array
	 */
	protected static $gridActions = [
		'filter-table'	=> 'actionTableFilterSubmit',
		'filter-form'	=> 'actionFormFilterSubmit',
		'default'		=> 'actionDefault',
	];
	
	/**
	 * Form extensions names and used class full names.
	 * @var array
	 */
	protected static $formExtensionsClasses = [
		'mvccore/ext-form'				=> 'MvcCore\\Ext\\Form',
		'mvccore/ext-form-field-text'	=> 'MvcCore\\Ext\\Forms\Fields\\Text',
		'mvccore/ext-form-field-button'	=> 'MvcCore\\Ext\\Forms\Fields\\SubmitButton',
	];

	/**
	 * Base ASCII chars to remove from filtering or ordering.
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
	 * what could be dangerous user input.
	 * @see http://php.net/manual/en/function.htmlspecialchars.php
	 * @var \string[]
	 */
	protected static $specialMeaningChars = [
		// commented characters are cleaned by `htmlspecialchars()`
		//'&'	=> "&amp;",
		//'"'	=> "&quot;",
		//"'"	=> "&apos;",
		//'<'	=> "&lt;",
		//'>'	=> "&gt;",
		'|'	=> "&#124;",
		'='	=> "&#61;",
		'\\'=> "&#92;",
		'%'	=> "&#37;",
	];

	/**
	 * Custom form result state base value for grid 
	 * with table type with heading filter form.
	 * @var int
	 */
	protected static $tableHeadingFilterFormClearResultBase = 10;

	/**
	 * Datagrid page, always initialized into integer value.
	 * @internal
	 * @var int|NULL
	 */
	protected $page = NULL;

	/**
	 * Database table offset, always initialized into integer value.
	 * @internal
	 * @var int|NULL
	 */
	protected $offset = NULL;

	/**
	 * Database table select limit, initialized into integer or `NULL` value.
	 * @internal
	 * @var int|NULL
	 */
	protected $limit = NULL;
	
	/**
	 * Keys are configured database column names 
	 * and values are ordering direction strings - `ASC | DESC`.
	 * @internal
	 * @var array
	 */
	protected $ordering = [];

	/**
	 * Keys are configured database column names 
	 * and values are arrays of raw filtering values.
	 * @var array
	 */
	protected $filtering = [];
	
	/**
	 * Paging items, completed after model total count has been loaded.
	 * @internal
	 * @var \MvcCore\Ext\Controllers\DataGrids\Iterators\Paging|NULL
	 */
	protected $paging = NULL;

	/**
	 * Initialized into `TRUE` if any translator callable defined.
	 * @var bool
	 */
	protected $translate = FALSE;

	/**
	 * Total items count.
	 * @var int|NULL
	 */
	protected $totalCount = NULL;

	/**
	 * Loaded current page data or page iterator.
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