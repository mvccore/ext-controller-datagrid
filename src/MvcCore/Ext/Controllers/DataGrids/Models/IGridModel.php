<?php

namespace MvcCore\Ext\Controllers\DataGrids\Models;

interface IGridModel {

	/**
	 * @return int
	 */
	public function GetTotalCount ();

	/**
	 * @return array|\MvcCore\Ext\Models\Db\Readers\Streams\Iterator
	 */
	public function GetPageData ();

	/**
	 * @param  int $offset 
	 * @return \MvcCore\Ext\Controllers\DataGrids\Models\IGridModel
	 */
	public function SetOffset ($offset);
	
	/**
	 * @param  int|NULL $limit 
	 * @return \MvcCore\Ext\Controllers\DataGrids\Models\IGridModel
	 */
	public function SetLimit ($limit);

	/**
	 * @param  array $filtering 
	 * @return \MvcCore\Ext\Controllers\DataGrids\Models\IGridModel
	 */
	public function SetFiltering (array $filtering);

	/**
	 * @param  array $sorting
	 * @return \MvcCore\Ext\Controllers\DataGrids\Models\IGridModel
	 */
	public function SetSorting (array $sorting);

	/**
	 * 
	 * @param  mixed                                             $row
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\Column $column 
	 * @param  \MvcCore\IView                                    $view
	 * @return string
	 */
	public function RenderCell ($row, \MvcCore\Ext\Controllers\DataGrids\Configs\IColumn $configColumn, \MvcCore\IView $view);

}