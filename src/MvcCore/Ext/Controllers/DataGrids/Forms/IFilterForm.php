<?php

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
