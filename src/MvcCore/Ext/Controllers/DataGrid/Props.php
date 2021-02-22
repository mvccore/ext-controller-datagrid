<?php

namespace MvcCore\Ext\Controllers\DataGrid;

trait Props {
	
	/**
	 * @var \MvcCore\Ext\Controllers\DataGrids\IModel
	 */
	protected $model = NULL;

	protected $paramGrid = NULL;

	protected $offset = 0;

	protected $limit = 0;

	protected $itemsPerPage = self::ITEMS_PER_PAGE_DEFAULT;

	protected $countsScale = self::COUNTS_SCALE_DEFAULT;

	protected $columns = [];

	protected $filtering = [];

	protected $ordering = [];

	/**
	 * 
	 * @var \MvcCore\Ext\Controllers\DataGrids\UrlConfig|NULL
	 */
	protected $urlConfig = NULL;

	/**
	 * 
	 * @var \MvcCore\Ext\Controllers\DataGrids\RenderConfig|NULL
	 */
	protected $renderConfig = NULL;
}