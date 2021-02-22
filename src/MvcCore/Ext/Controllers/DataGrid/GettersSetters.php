<?php

namespace MvcCore\Ext\Controllers\DataGrid;

trait GettersSetters {

	/**
	 * @param \MvcCore\Ext\Controllers\DataGrids\IModel $model 
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetModel (\MvcCore\Ext\Controllers\DataGrids\IModel $model) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		$this->model = $model;
		return $this;
	}

	/**
	 * @return \MvcCore\Ext\Controllers\DataGrids\IModel
	 */
	public function GetModel () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		return $this->model;
	}
}