<?php

namespace MvcCore\Ext\Controllers\DataGrid;

trait ConfigProps {
	
	/**
	 * @var \MvcCore\Ext\Controllers\DataGrids\IModel
	 */
	protected $model = NULL;

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

	/**
	 * 
	 * @var \MvcCore\Route|NULL
	 */
	protected $route = NULL;

	/**
	 * 
	 * @var array|NULL
	 */
	protected $urlParams = NULL;

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