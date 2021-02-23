<?php

namespace MvcCore\Ext\Controllers\DataGrid;

trait ConfigGettersSetters {

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
	 * @param  \MvcCore\Route|NULL $urlConfig
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetRoute (\MvcCore\IRoute $route) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		/** @var $route \MvcCore\Route */
		$this->route = $route;
		return $this;
	}
	/**
	 * @return \MvcCore\Route|NULL
	 */
	public function GetRoute () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		if ($this->route === NULL) {
			$this->route = new \MvcCore\Route([
				'pattern'		=> $this->GetUrlConfig()->GetRoutePattern(),
				'defaults'		=> [
					'page'		=> 1,
					'count'		=> $this->GetItemsPerPage(),
				],
				'constraints'	=> [
					'page'		=> '[0-9]+',
					'count'		=> '[0-9]+',
					'order'		=> '[^/]+',
					'filter'	=> '[^/]+',
				]
			]);
			$this->route->SetRouter($this->router);
		}
		return $this->route;
	}

	/**
	 * @param  array $urlParams
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetUrlParams (array $urlParams) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		$this->urlParams = $urlParams;
		return $this;
	}
	/**
	 * @return array
	 */
	public function GetUrlParams () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		if ($this->urlParams === NULL) {
			$matches = $this->GetRoute()->Matches($this->GetGridRequest());
			if ($matches === NULL) {
				$this->urlParams = [];
			} else {
				if (isset($matches['page'])) 
					$matches['page'] = intval($matches['page']);
				if (isset($matches['count'])) 
					$matches['count'] = intval($matches['count']);
				$this->urlParams = $matches;
			}
		}
		return $this->urlParams;
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
		if ($this->urlConfig === NULL)
			$this->urlConfig = new \MvcCore\Ext\Controllers\DataGrids\UrlConfig;
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
		if ($this->renderConfig === NULL)
			$this->renderConfig = new \MvcCore\Ext\Controllers\DataGrids\RenderConfig;
		return $this->renderConfig;
	}
}