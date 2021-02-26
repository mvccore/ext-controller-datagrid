<?php

namespace MvcCore\Ext\Controllers\DataGrid;

trait ConfigProps {
	
	/**
	 * Datagrid html wrapper element css class attribute value.
	 * @var \string[]
	 */
	protected $cssClasses = [];

	/**
	 * @var \MvcCore\Ext\Controllers\DataGrids\Models\IGridModel
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
	protected $countScales = self::COUNTS_SCALE_DEFAULT;
	
	/**
	 * 
	 * @var bool
	 */
	protected $allowedCustomUrlCountScale = FALSE;

	/**
	 * 
	 * @var bool
	 */
	protected $multiSorting = FALSE;

	/**
	 * 
	 * @var bool
	 */
	protected $multiFiltering = FALSE;

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
	 * @var \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments|NULL
	 */
	protected $configUrlSegments = NULL;

	/**
	 * 
	 * @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering|NULL
	 */
	protected $configRendering = NULL;

	/**
	 * 
	 * @var \MvcCore\Ext\Controllers\DataGrids\Iterators\Columns|NULL
	 */
	protected $configColumns = NULL;
}