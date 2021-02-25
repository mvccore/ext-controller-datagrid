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
	 * Detail grid content template name, with table heading and table body.
	 * @var string
	 */
	const TEMPLATE_CONTENT_DEFAULT = 'grid';
	
	/**
	 * Detail table heading template name.
	 * @var string
	 */
	const TEMPLATE_TABLE_HEAD_DEFAULT = 'table-head';
	
	/**
	 * Detail table body template name.
	 * @var string
	 */
	const TEMPLATE_TABLE_BODY_DEFAULT = 'table-body';
	
	/**
	 * Detail grid heading template name.
	 * @var string
	 */
	const TEMPLATE_GRID_HEAD_DEFAULT = 'grid-head';
	
	/**
	 * Detail grid body template name.
	 * @var string
	 */
	const TEMPLATE_GRID_BODY_DEFAULT = 'grid-body';
	
	/**
	 * Detail paging control template name.
	 * @var string
	 */
	const TEMPLATE_CONTROL_PAGING_DEFAULT = 'paging';
	
	/**
	 * Detail ordering control template name.
	 * @var string
	 */
	const TEMPLATE_CONTROL_ORDERING_DEFAULT = 'ordering';
	
	/**
	 * Detail count scales control template name.
	 * @var string
	 */
	const TEMPLATE_CONTROL_COUNT_SCALES_DEFAULT = 'count-scales';
	
	/**
	 * Detail filter form template name.
	 * @var string
	 */
	const TEMPLATE_FILTER_FORM_DEFAULT = 'filter';

}