<?php

namespace MvcCore\Ext\Controllers\DataGrid;

trait GettersSetters {

	/**
	 * @param  \MvcCore\Ext\Controllers\DataGrids\IModel $model 
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

	/**
	 * @param  int $offset
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetOffset ($offset) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		$this->offset = $offset;
		return $this;
	}
	/**
	 * @return int
	 */
	public function GetOffset () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		return $this->offset;
	}

	/**
	 * @param  int $limit
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetLimit ($limit) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		$this->limit = $limit;
		return $this;
	}
	/**
	 * @return int
	 */
	public function GetLimit () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		return $this->limit;
	}

	/**
	 * @param  int $itemsPerPage
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetItemsPerPage ($itemsPerPage) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		$this->itemsPerPage = $itemsPerPage;
		return $this;
	}
	/**
	 * @return int
	 */
	public function GetItemsPerPage () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		return $this->itemsPerPage;
	}

	/**
	 * @param  \int[] $countsScale
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetCountsScale (array $countsScale) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		$this->countsScale = $countsScale;
		return $this;
	}
	/**
	 * @return \int[]
	 */
	public function GetCountsScale () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		return $this->countsScale;
	}



	/**
	 * @param  \MvcCore\Ext\Controllers\DataGrids\UrlConfig $urlConfig
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetUrlConfig (\MvcCore\Ext\Controllers\DataGrids\UrlConfig $urlConfig) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		$this->urlConfig = $urlConfig;
		return $this;
	}
	/**
	 * @return \MvcCore\Ext\Controllers\DataGrids\UrlConfig
	 */
	public function GetUrlConfig () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		return $this->urlConfig;
	}

	/**
	 * @param  \MvcCore\Ext\Controllers\DataGrids\RenderConfig $renderConfig
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetRenderConfig (\MvcCore\Ext\Controllers\DataGrids\RenderConfig $renderConfig) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		$this->renderConfig = $renderConfig;
		return $this;
	}
	/**
	 * @return \MvcCore\Ext\Controllers\DataGrids\RenderConfig
	 */
	public function GetRenderConfig () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		return $this->renderConfig;
	}
}