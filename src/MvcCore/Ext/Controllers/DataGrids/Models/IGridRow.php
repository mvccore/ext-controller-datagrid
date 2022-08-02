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

interface IGridRow {
	
	/**
	 * Set datagrid instance, always initialized by datagrid component automatically.
	 * @param  \MvcCore\Ext\Controllers\IDataGrid|NULL $grid
	 * @return \MvcCore\Ext\Controllers\DataGrids\Models\IGridRow
	 */
	public function SetGrid (\MvcCore\Ext\Controllers\IDataGrid $grid = NULL);

	/**
	 * Render value with by possible view helper as scalar value 
	 * into datagrid table cell (convertable into string).
	 * @param  \MvcCore\Ext\Controllers\DataGrid $grid
	 * @param  string                            $columnPropName 
	 * @param  \MvcCore\View|NULL                $view
	 * @return string
	 */
	public function RenderCellByPropName (
		\MvcCore\Ext\Controllers\IDataGrid $grid,
		$columnPropName,
		\MvcCore\IView $view = NULL, 
	);

	/**
	 * Render value with by possible view helper as scalar value 
	 * into datagrid table cell (convertable into string).
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\Column $columnConfig 
	 * @param  \MvcCore\View|NULL                                $view
	 * @return string
	 */
	public function RenderCell (
		\MvcCore\Ext\Controllers\DataGrids\Configs\IColumn $columnConfig,
		\MvcCore\IView $view = NULL
	);

}