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
	 * Detail grid content template name, with table heading and table body.
	 * @var string
	 */
	const TEMPLATE_CONTENT_DEFAULT = 'grid';
	
	/**
	 * Detail grid pages control template name.
	 * @var string
	 */
	const TEMPLATE_CONTROL_PAGE_DEFAULT = 'page';
	
	/**
	 * Detail grid ordering control template name.
	 * @var string
	 */
	const TEMPLATE_CONTROL_ORDER_DEFAULT = 'order';
	
	/**
	 * Detail grid count scales control template name.
	 * @var string
	 */
	const TEMPLATE_CONTROL_COUNT_DEFAULT = 'count';
	
	/**
	 * Detail grid filter form template name.
	 * @var string
	 */
	const TEMPLATE_FILTER_FORM_DEFAULT = 'filter';

}