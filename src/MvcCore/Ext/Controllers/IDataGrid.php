<?php

namespace MvcCore\Ext\Controllers;

interface IDataGrid extends \MvcCore\Ext\Controllers\DataGrid\IConstants {

	/**
	 * 
	 * @param  array $controlsTexts
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetControlsTexts ($controlsTexts);
	
	/**
	 * 
	 * @return array
	 */
	public function GetControlsTexts ($controlsTexts);
	
	/**
	 * 
	 * @return string|NULL
	 */
	public function GetControlText ($textKey);
	
	/**
	 * 
	 * @param  string|\string[] $cssClasses
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function AddCssClasses ($cssClasses);
	
	/**
	 * 
	 * @param  string|\string[] $cssClasses
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetCssClasses ($cssClasses);
	
	/**
	 * 
	 * @return \string[]
	 */
	public function GetCssClasses ();

	/**
	 * 
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Models\IGridModel $model 
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetModel (\MvcCore\Ext\Controllers\DataGrids\Models\IGridModel $model);

	/**
	 * 
	 * @throws \InvalidArgumentException
	 * @return \MvcCore\Ext\Controllers\DataGrids\Models\IGridModel|NULL
	 */
	public function GetModel ($throwExceptionIfNull = FALSE);

	/**
	 * 
	 * @param  int $itemsPerPage
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetItemsPerPage ($itemsPerPage);

	/**
	 * 
	 * @return int
	 */
	public function GetItemsPerPage ();

	/**
	 * 
	 * @param  \int[] $countScales
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetCountScales (array $countScales);

	/**
	 * 
	 * @return \int[]
	 */
	public function GetCountScales ();
	
	/**
	 * 
	 * @param  bool $allowedCustomUrlCountScale
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetAllowedCustomUrlCountScale ($allowedCustomUrlCountScale);

	/**
	 * 
	 * @return bool
	 */
	public function GetAllowedCustomUrlCountScale ();

	/**
	 * 
	 * @param  bool $multiSorting
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetMultiSorting ($multiSorting);

	/**
	 * 
	 * @return bool
	 */
	public function GetMultiSorting ();
	
	/**
	 * 
	 * @param  bool $multiFiltering
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetMultiFiltering ($multiFiltering);

	/**
	 * 
	 * @return bool
	 */
	public function GetMultiFiltering ();
	
	/**
	 * 
	 * @param  callable $translator
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetTranslator ($translator);
	
	/**
	 * 
	 * @return callable|NULL
	 */
	public function GetTranslator ($translator);
	
	/**
	 * 
	 * @param  bool $translateUrlNames
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetTranslateUrlNames ($translateUrlNames);
	
	/**
	 * 
	 * @return bool
	 */
	public function GetTranslateUrlNames ();

	/**
	 * 
	 * @param  \MvcCore\Route|NULL $route
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetRoute (\MvcCore\IRoute $route);

	/**
	 * 
	 * @return \MvcCore\Route|NULL
	 */
	public function GetRoute ();

	/**
	 * 
	 * @param  array $urlParams
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetUrlParams (array $urlParams);

	/**
	 * 
	 * @return array
	 */
	public function GetUrlParams ();

	/**
	 * 
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments $configUrlSegments
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetConfigUrlSegments (\MvcCore\Ext\Controllers\DataGrids\Configs\IUrlSegments $configUrlSegments);

	/**
	 * 
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function GetConfigUrlSegments ();

	/**
	 * 
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\Column[]|\MvcCore\Ext\Controllers\DataGrids\Iterators\Columns $configRendering
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetConfigColumns ($configColumns);

	/**
	 * 
	 * @return \MvcCore\Ext\Controllers\DataGrids\Iterators\Columns|NULL
	 */
	public function GetConfigColumns ();

	/**
	 * 
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $configRendering
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetConfigRendering (\MvcCore\Ext\Controllers\DataGrids\Configs\IRendering $configRendering);

	/**
	 * 
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function GetConfigRendering ();

	/**
	 * 
	 * @return int|NULL
	 */
	public function GetPage ();

	/**
	 * 
	 * @return int|NULL
	 */
	public function GetOffset ();

	/**
	 * 
	 * @return int|NULL
	 */
	public function GetLimit ();

	/**
	 * 
	 * @return array
	 */
	public function GetOrdering ();
	
	/**
	 * 
	 * @return array
	 */
	public function GetFiltering ();
	
	/**
	 * 
	 * @return \MvcCore\Ext\Controllers\DataGrids\Iterators\Paging|NULL
	 */
	public function GetPaging ();
	
	/**
	 * 
	 * @return bool
	 */
	public function GetTranslate ();
	
	/**
	 * 
	 * @return int|NULL
	 */
	public function GetTotalCount ();
	
	/**
	 * 
	 * @return array|\MvcCore\Ext\Models\Db\Readers\Streams\Iterator|NULL
	 */
	public function GetPageData ();

	/**
	 * 
	 * @return \MvcCore\Request
	 */
	public function GetGridRequest ();

	/**
	 * 
	 * @return string
	 */
	public function GridUrl (array $gridParams = []);
	
	/**
	 * 
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\Column $column 
	 * @param  mixed                                             $cellValue 
	 * @return string
	 */
	public function GridFilterUrl (\MvcCore\Ext\Controllers\DataGrids\Configs\IColumn $column, $cellValue);

	/**
	 * 
	 * @param  int $offset
	 * @return string
	 */
	public function GridPageUrl ($offset);
	
	/**
	 * 
	 * @param  int $count
	 * @return string
	 */
	public function GridCountUrl ($count);

	/**
	 * 
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\Column $column 
	 * @return string
	 */
	public function GridOrderUrl (\MvcCore\Ext\Controllers\DataGrids\Configs\IColumn $column);

	/**
	 * 
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\Column $column 
	 * @return bool|NULL
	 */
	public function GetColumnOrderDirection (\MvcCore\Ext\Controllers\DataGrids\Configs\IColumn $column);

	/**
	 * 
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\IColumn $column 
	 * @return int|NULL
	 */
	public function GetColumnFiltered (\MvcCore\Ext\Controllers\DataGrids\Configs\IColumn $column);

	/**
	 * 
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\IColumn $column 
	 * @return int|NULL
	 */
	public function GetColumnOrderIndex (\MvcCore\Ext\Controllers\DataGrids\Configs\IColumn $column);

}
