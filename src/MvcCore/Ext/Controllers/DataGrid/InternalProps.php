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