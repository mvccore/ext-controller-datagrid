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

namespace MvcCore\Ext\Controllers\DataGrids\Forms;

interface IFilterForm {
	
	/**
	 * Set datagrid columns configuration.
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Iterators\Columns $configColumns
	 * @return \MvcCore\Ext\Controllers\DataGrids\Forms\IFilterForm
	 */
	public function SetConfigColumns ($configColumns);
	
	/**
	 * Set parsed filtering.
	 * Keys are database column names, values are arrays 
	 * with operator as key and raw filtering values as values.
	 * @param  array $filtering
	 * @return \MvcCore\Ext\Controllers\DataGrids\Forms\IFilterForm
	 */
	public function SetFiltering ($filtering);

}
