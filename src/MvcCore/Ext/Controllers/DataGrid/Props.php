<?php

namespace MvcCore\Ext\Controllers\DataGrid;

trait Props {
	
	/**
	 * @var \MvcCore\Ext\Controllers\DataGrids\IModel
	 */
	protected $model = NULL;

	/**
	 * 
	 * @var int
	 */
	protected $offset = 0;
	
	/**
	 * 
	 * @var int
	 */
	protected $limit = 0;
	
	/**
	 * 
	 * @var int
	 */
	protected $itemsPerPage = self::ITEMS_PER_PAGE_DEFAULT;
	
	/**
	 * 
	 * @var \int[]
	 */
	protected $countsScale = self::COUNTS_SCALE_DEFAULT;


	protected $paramGrid = NULL;

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