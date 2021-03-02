<?php

namespace MvcCore\Ext\Controllers\DataGrid;

trait ConfigProps {
	
	/**
	 * Datagrid html wrapper element css class attribute value.
	 * @var \string[]
	 */
	protected $cssClasses = [];
	
	/**
	 * Grid table type, table heading filter form instance.
	 * @var \MvcCore\Ext\Form|NULL
	 */
	protected $tableHeadFilterForm = NULL;
	
	/**
	 * Grid controls visible texts.
	 * @var array
	 */
	protected $controlsTexts = [
		'previous'	=> 'Previous',
		'next'		=> 'Next',
		'first'		=> 'First',
		'last'		=> 'Last ({0})',
		'all'		=> 'All',
		'filter'	=> 'Filter',
		'clear'		=> 'Clear',
	];

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
	 * @var int
	 */
	protected $sortingMode = self::SORT_SINGLE_COLUMN;

	/**
	 * 
	 * @var int
	 */
	protected $filteringMode = self::FILTER_SINGLE_COLUMN;

	/**
	 * 
	 * @var \MvcCore\Ext\Controllers\DataGrids\Forms\IFilterForm|\MvcCore\Ext\IForm|NULL
	 */
	protected $controlFilterForm = NULL;

	/**
	 * 
	 * @var callable
	 */
	protected $translator = NULL;

	/**
	 * 
	 * @var bool
	 */
	protected $translateUrlNames = FALSE;

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