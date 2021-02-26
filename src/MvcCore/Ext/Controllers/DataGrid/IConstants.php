<?php

namespace MvcCore\Ext\Controllers\DataGrid;

interface IConstants {
	
	/**
	 * Current route param name. It could be used in rewrite 
	 * section or in query string like:
	 * `/any/path[/<grid>]` or `/any/path?grid=...`
	 * @var string
	 */
	const PARAM_GRID = 'grid';
	
	/**
	 * Default items per page.
	 * @var int
	 */
	const ITEMS_PER_PAGE_DEFAULT = 10;

	/**
	 * Default counts control scale. 
	 * Last zero value means unlimited items option.
	 * @var \int[]
	 */
	const COUNTS_SCALE_DEFAULT = [10,100,1000,0];


	/**
	 * Standard table datagrid type.
	 * @var int
	 */
	const TYPE_TABLE	= 1;
	
	/**
	 * Griw with variable columns count datagrid type.
	 * @var int
	 */
	const TYPE_GRID		= 2;


	/**
	 * Never display this control.
	 * @var int
	 */
	const CONTROL_DISPLAY_NEVER			= 0;
	
	/**
	 * Display control if necessary.
	 * @var int
	 */
	const CONTROL_DISPLAY_IF_NECESSARY	= 1;

	/**
	 * Display control every time.
	 * @var int
	 */
	const CONTROL_DISPLAY_ALWAYS		= 2;
}