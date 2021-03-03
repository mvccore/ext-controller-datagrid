<?php

namespace MvcCore\Ext\Controllers\DataGrids\Models;

interface IGridColumns {
	
	/**
	 * Return array of datagrid columns config.
	 * This method is called by datagrid component to parse decorated 
	 * model properties to complete datagrid columns configuration automatically.
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column[]
	 */
	public function GetConfigColumns ();

}
