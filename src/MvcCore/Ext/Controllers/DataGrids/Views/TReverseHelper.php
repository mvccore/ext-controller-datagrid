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

namespace MvcCore\Ext\Controllers\DataGrids\Views;

trait TReverseHelper {

	/**
	 * Currently used datagrid instance.
	 * @var ?\MvcCore\Ext\Controllers\DataGrid
	 */
	protected $grid = NULL;


	/**
	 * Set currently used datagrid instance.
	 * @param  \MvcCore\Ext\Controllers\DataGrid $grid 
	 * @return \MvcCore\Ext\Views\Helpers\AbstractHelper|\MvcCore\Ext\Controllers\DataGrids\Views\IReverseHelper
	 */
	public function SetGrid (\MvcCore\Ext\Controllers\IDataGrid $grid) {
		/** @var \MvcCore\Ext\Views\Helpers\AbstractHelper|\MvcCore\Ext\Controllers\DataGrids\Views\IReverseHelper $this */
		$this->grid = $this;
		return $this;
	}
}