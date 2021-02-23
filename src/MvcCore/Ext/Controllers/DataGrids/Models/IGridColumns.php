<?php

namespace MvcCore\Ext\Controllers\DataGrids\Models;

interface IGridColumns {
	
	/**
	 * Return array of datagrid columns config.
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column[]
	 */
	public function GetConfigColumns ();

}
