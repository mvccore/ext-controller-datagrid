<?php

namespace MvcCore\Ext\Controllers\DataGrid;

trait InternalProps {
	
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
	 * and values are arrays of raw filtering values.
	 * @var array
	 */
	protected $filtering = [];

	/**
	 * Keys are configured database column names 
	 * and values are ordering direction strings - `ASC | DESC`.
	 * @internal
	 * @var array
	 */
	protected $ordering = [];

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

}