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

trait GridRow {
	
	/**
	 * Datagrid instance, always initialized by datagrid component automatically.
	 * @var \MvcCore\Ext\Controllers\DataGrid|NULL
	 */
	protected $grid = NULL;
	
	/**
	 * Set datagrid instance, always initialized by datagrid component automatically.
	 * @param  \MvcCore\Ext\Controllers\DataGrid|NULL $grid
	 * @return \MvcCore\Ext\Controllers\DataGrids\Models\GridRow
	 */
	public function SetGrid (\MvcCore\Ext\Controllers\IDataGrid $grid = NULL) {
		$this->grid = $grid;
		return $this;
	}

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
	) {
		$columnConfig = $grid->GetConfigColumns(FALSE)->GetByPropName($columnPropName);
		return $this->RenderCell(
			$columnConfig, $view
		);
	}

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
	) {
		$propName = $columnConfig->GetPropName();
		$value = $this->{'Get' . ucfirst($propName)}();
		if ($value === NULL) 
			return '';
		$viewHelperName = $columnConfig->GetViewHelper();
		if ($viewHelperName) {
			$format = $columnConfig->GetFormat() ?: [];
			$formatCount = count($format);
			// if there is viewHelper defined and if there are more formats, 
			// unset first format argument used for database value parsing
			if (
				$formatCount > 1 && 
				($value instanceof \DateTime || $value instanceof \DateTimeImmutable)
			) {
				$format = array_slice($format, 1, null, TRUE);
			}
			return call_user_func_array(
				[$view, $viewHelperName], 
				array_merge([$value], $format)
			);
		} else {
			return static::convertToScalar(
				$value, $columnConfig->GetFormat()
			);
		}
	}

}