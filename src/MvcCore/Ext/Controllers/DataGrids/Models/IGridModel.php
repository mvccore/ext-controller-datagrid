<?php

namespace MvcCore\Ext\Controllers\DataGrids\Models;

interface IGridModel {

	/**
	 * Set database table offset, always initialized into integer.
	 * This offset is always initialized by datagrid component automatically.
	 * @param  int $offset 
	 * @return \MvcCore\Ext\Controllers\DataGrids\Models\IGridModel
	 */
	public function SetOffset ($offset);
	
	/**
	 * Set database table select limit, it could be initialized into integer or `NULL`.
	 * This limit is always initialized by datagrid component automatically.
	 * @param  int|NULL $limit 
	 * @return \MvcCore\Ext\Controllers\DataGrids\Models\IGridModel
	 */
	public function SetLimit ($limit);

	/**
	 * Set database table filtering, keys are database table column names
	 * and values are arrays. Each key in value array is condition 
	 * operator and values are raw user input values to use in column condition.
	 * This filtering is always initialized by datagrid component automatically.
	 * @param  array $filtering 
	 * @return \MvcCore\Ext\Controllers\DataGrids\Models\IGridModel
	 */
	public function SetFiltering (array $filtering);

	/**
	 * Set database table sorting, keys are database table column names 
	 * and values are sorting direction strings - `ASC | DESC`.
	 * This sorting is always initialized by datagrid component automatically.
	 * @param  array $sorting
	 * @return \MvcCore\Ext\Controllers\DataGrids\Models\IGridModel
	 */
	public function SetSorting (array $sorting);
	
	/**
	 * Get total count of database table rows by initialized fitering.
	 * You have to implement this method usually by your own.
	 * @return int
	 */
	public function GetTotalCount ();

	/**
	 * Get page data rows or database result iterator.
	 * You have to implement this method usually by your own.
	 * @return array|\MvcCore\Ext\Models\Db\Readers\Streams\Iterator
	 */
	public function GetPageData ();

	/**
	 * Render value in datagrid tabe cell as scalar value (convertable into string).
	 * @param  mixed                                             $row
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\Column $column 
	 * @param  \MvcCore\IView                                    $view
	 * @return string
	 */
	public function RenderCell ($row, \MvcCore\Ext\Controllers\DataGrids\Configs\IColumn $configColumn, \MvcCore\IView $view);

}