<?php

/**
 * MvcCore
 *
 * This source file is subject to the BSD 3 License
 * For the full copyright and license information, please view
 * the LICENSE.md file that are distributed with this source code.
 *
 * @copyright	Copyright (c) 2016 Tom Flidr (https://github.com/mvccore)
 * @license		https://mvccore.github.io/docs/mvccore/5.0.0/LICENSE.md
 */

namespace MvcCore\Ext\Controllers\DataGrids\Models;

interface IGridModel {

	/**
	 * Set datagrid instance, always initialized by datagrid component automatically.
	 * @param  \MvcCore\Ext\Controllers\IDataGrid|NULL $grid
	 * @return \MvcCore\Ext\Controllers\DataGrids\Models\IGridModel
	 */
	public function SetGrid (\MvcCore\Ext\Controllers\IDataGrid $grid);

	/**
	 * Set database table offset, always initialized into integer.
	 * This offset is always initialized by datagrid component automatically.
	 * @param  int $offset 
	 * @return \MvcCore\Ext\Controllers\DataGrids\Models\IGridModel
	 */
	public function SetOffset ($offset);

	/**
	 * Get database table offset, always initialized into integer.
	 * This offset is always initialized by datagrid component automatically.
	 * @return int|NULL
	 */
	public function GetOffset ();
	
	/**
	 * Set database table select limit, it could be initialized into integer or `NULL`.
	 * This limit is always initialized by datagrid component automatically.
	 * @param  int|NULL $limit 
	 * @return \MvcCore\Ext\Controllers\DataGrids\Models\IGridModel
	 */
	public function SetLimit ($limit);

	/**
	 * Get database table select limit, it could be initialized into integer or `NULL`.
	 * This limit is always initialized by datagrid component automatically.
	 * @return int|NULL
	 */
	public function GetLimit ();

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
	 * Get database table filtering, keys are database table column names
	 * and values are arrays. Each key in value array is condition 
	 * operator and values are raw user input values to use in column condition.
	 * This filtering is always initialized by datagrid component automatically.
	 * @return array
	 */
	public function GetFiltering ();

	/**
	 * Set database table sorting, keys are database table column names 
	 * and values are sorting direction strings - `ASC | DESC`.
	 * This sorting is always initialized by datagrid component automatically.
	 * @param  array $sorting
	 * @return \MvcCore\Ext\Controllers\DataGrids\Models\IGridModel
	 */
	public function SetSorting (array $sorting);

	/**
	 * Get database table sorting, keys are database table column names 
	 * and values are sorting direction strings - `ASC | DESC`.
	 * This sorting is always initialized by datagrid component automatically.
	 * @return array
	 */
	public function GetSorting ();
	
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
	 * Render value with by possible view helper as scalar value 
	 * into datagrid table cell (convertable into string).
	 * @param  mixed                                                     $row
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\IColumn|string $columnNameOrConfig 
	 * @param  \MvcCore\IView                                            $view
	 * @return string
	 */
	public function RenderCell ($row, $columnNameOrConfig, \MvcCore\IView $view);

	/**
	 * Get scalar value used in URL for filtering (convertable into string).
	 * @param  mixed                                                     $row
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\IColumn|string $columnPropNameOrConfig 
	 * @return string
	 */
	public function GetFilterUrlValue ($row, $columnPropNameOrConfig);
}