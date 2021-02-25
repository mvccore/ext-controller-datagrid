<?php

namespace MvcCore\Ext\Controllers\DataGrid;

trait ConfigGettersSetters {

	/**
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Models\IGridModel $model 
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetModel (\MvcCore\Ext\Controllers\DataGrids\Models\IGridModel $model) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		$this->model = $model;
		$this->GetModel(TRUE);// validate
		return $this;
	}
	/**
	 * @throws \InvalidArgumentException
	 * @return \MvcCore\Ext\Controllers\DataGrids\Models\IGridModel|NULL
	 */
	public function GetModel ($throwExceptionIfNull = FALSE) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		if (
			($this->model === NULL && $throwExceptionIfNull) || 
			($this->model !== NULL && !($this->model instanceof \MvcCore\Ext\Controllers\DataGrids\Models\IGridModel))
		) throw new \InvalidArgumentException("No model defined or model doesn't implement `\\MvcCore\\Ext\\Controllers\\DataGrids\\Models\\IGridModel`.");
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
	 * @param  \int[] $countScales
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetCountScales (array $countScales) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		$this->countScales = $countScales;
		return $this;
	}
	/**
	 * @return \int[]
	 */
	public function GetCountScales () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		return $this->countScales;
	}

	/**
	 * @param  \MvcCore\Route|NULL $route
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
				'pattern'		=> $this->GetConfigUrlSegments()->GetRoutePattern(),
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
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments $configUrlSegments
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetConfigUrlSegments (\MvcCore\Ext\Controllers\DataGrids\Configs\IUrlSegments $configUrlSegments) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		$this->configUrlSegments = $configUrlSegments;
		return $this;
	}
	/**
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function GetConfigUrlSegments () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		if ($this->configUrlSegments === NULL)
			$this->configUrlSegments = new \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments;
		return $this->configUrlSegments;
	}

	/**
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\Column[]|\MvcCore\Ext\Controllers\DataGrids\Iterators\Columns $configRendering
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetConfigColumns ($configColumns) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		$throwInvalidTypeError = FALSE;
		if ($configColumns instanceof \MvcCore\Ext\Controllers\DataGrids\Iterators\Columns) {
			$this->configColumns = $configColumns;	
		} else if (is_array($configColumns)) {
			/** @var $configColumn \MvcCore\Ext\Controllers\DataGrids\Configs\Column */
			$configColumnsByUrlNames = [];
			foreach ($configColumns as $index => $configColumn) {
				if ($configColumn instanceof \MvcCore\Ext\Controllers\DataGrids\Configs\IColumn) {
					$propName = $configColumn->GetPropName();
					if ($propName === NULL) throw new \InvalidArgumentException(
						"Datagrid column configuration item requires `propName` (index: {$index})."
					);
					$urlName = $configColumn->GetUrlName();
					if ($urlName === NULL) 
						$configColumn->SetUrlName($propName);
					if ($dbColumnName === NULL) 
					$dbColumnName = $configColumn->GetDbColumnName();
						$configColumn->SetDbColumnName($propName);
					$humanName = $configColumn->GetHumanName();
					if ($humanName === NULL) 
						$configColumn->SetHumanName($propName);
					$configColumnsByUrlNames[$urlName] = $configColumn;
				} else {
					$throwInvalidTypeError = TRUE;
					break;
				}
			}
			if (!$throwInvalidTypeError)
				$this->configColumns = new \MvcCore\Ext\Controllers\DataGrids\Iterators\Columns(
					$configColumnsByUrlNames
				);
		} else {
			$throwInvalidTypeError = TRUE;
		}
		if ($throwInvalidTypeError) throw new \InvalidArgumentException(
			"Datagrid column configuration has to be array of types ".
			"`\\MvcCore\\Ext\\Controllers\\DataGrids\\Configs\\Column` or iterator ".
			"`\\MvcCore\\Ext\\Controllers\\DataGrids\\Iterators\\Columns`."
		);
		return $this;
	}
	/**
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column[]|NULL
	 */
	public function GetConfigColumns () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		if ($this->configColumns === NULL) {
			$model = $this->GetModel(TRUE);
			if ($model instanceof \MvcCore\Ext\Controllers\DataGrids\Models\IGridColumns) {
				/** @var $model \MvcCore\Ext\Controllers\DataGrids\Models\GridColumns */
				$configColumnsArr = $model->GetConfigColumns();
			} else {
				$context = $this;
				$configColumnsArr = $context::ParseConfigColumns($this->model);
			}
			if (is_array($configColumnsArr) && $configColumnsArr > 0) {
				$this->configColumns = new \MvcCore\Ext\Controllers\DataGrids\Iterators\Columns(
					$configColumnsArr
				);
			} else {
				throw new \InvalidArgumentException(
					"There was not possible to complete datagrid columns from given model. ".
					"Please decorate model properties with class ".
					"`\\MvcCore\\Ext\\Controllers\\DataGrids\\Configs\\Column` ".
					"or with equivalent PHPDocs tag name."
				);
			}
		}
		return $this->configColumns;
	}

	/**
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $configRendering
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetConfigRendering (\MvcCore\Ext\Controllers\DataGrids\Configs\IRendering $configRendering) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		$this->configRendering = $configRendering;
		return $this;
	}
	/**
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function GetConfigRendering () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		if ($this->configRendering === NULL)
			$this->configRendering = new \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering;
		return $this->configRendering;
	}
}