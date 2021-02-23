<?php

namespace MvcCore\Ext\Controllers\DataGrid;

trait InternalProps {
	
	/**
	 * 
	 * @internal
	 * @var \MvcCore\Request|NULL
	 */
	protected $gridRequest = NULL;

	/**
	 * 
	 * @internal
	 * @var string|NULL
	 */
	protected $queryStringParamsSepatator = NULL;

	/**
	 * 
	 * @internal
	 * @var int|NULL
	 */
	protected $offset = NULL;

	/**
	 * 
	 * @internal
	 * @var int|NULL
	 */
	protected $limit = NULL;


	protected $columns = [];

	protected $filtering = [];

	protected $ordering = [];

}