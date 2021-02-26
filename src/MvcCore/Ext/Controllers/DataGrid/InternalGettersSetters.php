<?php

namespace MvcCore\Ext\Controllers\DataGrid;

trait InternalGettersSetters {
	
	/**
	 * 
	 * @return int|NULL
	 */
	public function GetPage () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		return $this->page;
	}

	/**
	 * 
	 * @return int|NULL
	 */
	public function GetOffset () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		return $this->offset;
	}

	/**
	 * 
	 * @return int|NULL
	 */
	public function GetLimit () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		return $this->limit;
	}

	/**
	 * 
	 * @return array
	 */
	public function GetOrdering () {
		return $this->ordering;
	}
	
	/**
	 * 
	 * @return array
	 */
	public function GetFiltering () {
		return $this->filtering;
	}
	
	/**
	 * 
	 * @return \MvcCore\Ext\Controllers\DataGrids\Iterators\Paging|NULL
	 */
	public function GetPaging () {
		return $this->paging;
	}
	
	/**
	 * 
	 * @return int|NULL
	 */
	public function GetTotalCount () {
		return $this->totalCount;
	}
	
	/**
	 * 
	 * @return array|\MvcCore\Ext\Models\Db\Readers\Streams\Iterator|NULL
	 */
	public function GetPageData () {
		return $this->pageData;
	}

	/**
	 * 
	 * @return \MvcCore\Request
	 */
	public function GetGridRequest () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		if ($this->gridRequest === NULL) {
			$gridParam = $this->GetParam(static::PARAM_GRID, FALSE); // get param from application request object
			$gridParam = $gridParam !== NULL
				? '/' . ltrim($gridParam, '/')
				: '';
			$this->gridRequest = \MvcCore\Request::CreateInstance()
				->SetBasePath('')
				->SetPath($gridParam);
		}
		return $this->gridRequest;
	}

	/**
	 * 
	 * @return string
	 */
	public function GridUrl (array $gridParams = []) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		if ($this->queryStringParamsSepatator === NULL) {
			$routerType = new \ReflectionClass($this->router);
			$method = $routerType->getMethod('getQueryStringParamsSepatator');
			$method->setAccessible(TRUE);
			$this->queryStringParamsSepatator = $method->invoke($this->router);
		}

		$route = $this->GetRoute();
		$routeDefaults = $route->GetDefaults();
		$defaultParams = array_merge([], $this->GetUrlParams());
		$defaultPage = $defaultParams['page'];
		$defaultCount = $defaultParams['count'];
		$routeDefaultsChange = FALSE;

		if (
			array_key_exists('count', $gridParams) && 
			array_key_exists('count', $routeDefaults) &&
			$gridParams['count'] !== $routeDefaults['count']
		) {
			$routeDefaultsChange = TRUE;
			$route->SetDefaults([]);
		}
		
		if (
			array_key_exists('page', $gridParams) && 
			$gridParams['page'] === 1 &&
			array_key_exists('count', $defaultParams) && 
			array_key_exists('count', $routeDefaults) && 
			$defaultParams['count'] !== $routeDefaults['count']
		) {
			$routeDefaultsChange = TRUE;
			$gridParams['count'] = $defaultParams['count'];
			$route->SetDefaults([]);
		}

		foreach ($gridParams as $paramName => $paramValue) 
			if (array_key_exists($paramName, $defaultParams))
				unset($defaultParams[$paramName]);
		$gridReq = $this->GetGridRequest();
		list ($gridParam) = $route->Url(
			$gridReq,
			$gridParams,
			$defaultParams,
			$this->queryStringParamsSepatator,
			FALSE
		);
		if ($routeDefaultsChange) {
			$defaultParams['page'] = $defaultPage;
			$defaultParams['count'] = $defaultCount;
			$route->SetDefaults($defaultParams);
		}
		return $this->Url('self', [static::PARAM_GRID => $gridParam]);
	}
	
	/**
	 * 
	 * @internal
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\Column $column 
	 * @param  mixed                                             $cellValue 
	 * @return string
	 */
	public function GridFilterUrl (\MvcCore\Ext\Controllers\DataGrids\Configs\IColumn $column, $cellValue) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		$configUrlSegments = $this->configUrlSegments;
		$subjValueDelim = $configUrlSegments->GetUrlDelimiterSubjectValue();
		$valuesDelim = $configUrlSegments->GetUrlDelimiterValues();
		$subjsDelim = $configUrlSegments->GetUrlDelimiterSubjects();
		
		$currentColumnUrlName = $column->GetUrlName();
		$currentColumnDbName = $column->GetDbColumnName();
		$currentFilterDbNames = array_merge([], $this->filtering);

		if (isset($currentFilterDbNames[$currentColumnDbName])) {
			$currentFilterValues = $currentFilterDbNames[$currentColumnDbName];
			unset($currentFilterDbNames[$currentColumnDbName]);
			if (!in_array($cellValue, $currentFilterValues, FALSE))
				$currentFilterValues[] = $cellValue;
		} else {
			$currentFilterValues = [$cellValue];
		}
		$currentColumnUrlValues = implode($valuesDelim, $currentFilterValues);
		$filterParams[] = "{$currentColumnUrlName}{$subjValueDelim}{$currentColumnUrlValues}";

		if ($this->multiFiltering) {
			foreach ($this->configColumns->GetArray() as $columnUrlName => $columnConfig) {
				$columnDbName = $columnConfig->GetDbColumnName();
				if (!isset($currentFilterDbNames[$columnDbName])) continue;
				$filterValues = $currentFilterDbNames[$columnDbName];
				$filterUrlValues = implode($valuesDelim, $filterValues);
				$filterParams[] = "{$columnUrlName}{$subjValueDelim}{$filterUrlValues}";
			}
		}
		return $this->GridUrl([
			'filter'	=> implode($subjsDelim, $filterParams)
		]);
	}

	/**
	 * 
	 * @internal
	 * @param  int $offset
	 * @return string
	 */
	public function GridPageUrl ($offset) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		$itemsPerPage = $this->itemsPerPage;
		if (
			$this->itemsPerPage === 0 && (
				$this->configRendering->GetRenderControlPaging() & \MvcCore\Ext\Controllers\IDataGrid::CONTROL_DISPLAY_ALWAYS
			) != 0
		) $itemsPerPage = $this->totalCount;
		$page = intdiv($offset, $itemsPerPage) + 1;
		return $this->GridUrl(['page' => $page]);
	}
	
	/**
	 * 
	 * @internal
	 * @param  int $count
	 * @return string
	 */
	public function GridCountUrl ($count) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		return $this->GridUrl([
			'page'	=> $this->page,
			'count'	=> $count,
		]);
	}

	/**
	 * 
	 * @internal
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\Column $column 
	 * @return string
	 */
	public function GridOrderUrl (\MvcCore\Ext\Controllers\DataGrids\Configs\IColumn $column) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		$configUrlSegments = $this->configUrlSegments;
		$urlDirAsc = $configUrlSegments->GetUrlSuffixOrderAsc();
		$urlDirDesc = $configUrlSegments->GetUrlSuffixOrderDesc();
		$subjValueDelim = $configUrlSegments->GetUrlDelimiterSubjectValue();
		$subjsDelim = $configUrlSegments->GetUrlDelimiterSubjects();
		$currentColumnUrlDir = $urlDirAsc;
		$currentColumnUrlName = $column->GetUrlName();
		$currentColumnDbName = $column->GetDbColumnName();
		$currentOrderDbNames = array_merge([], $this->ordering);
		$oppositeDirections = [
			'ASC'	=> $urlDirDesc,
			'DESC'	=> '',
		];
		if (isset($currentOrderDbNames[$currentColumnDbName])) {
			$orderDirection = $currentOrderDbNames[$currentColumnDbName];
			unset($currentOrderDbNames[$currentColumnDbName]);
			if (isset($oppositeDirections[$orderDirection]))
				$currentColumnUrlDir = $oppositeDirections[$orderDirection];
		}
		$orderParams = [];
		if ($currentColumnUrlDir)
			$orderParams[] = "{$currentColumnUrlName}{$subjValueDelim}{$currentColumnUrlDir}";
		if ($this->multiSorting) {
			$urlDirections = [
				'ASC'	=> $urlDirAsc,
				'DESC'	=> $urlDirDesc,
			];
			foreach ($this->configColumns->GetArray() as $columnUrlName => $columnConfig) {
				$columnDbName = $columnConfig->GetDbColumnName();
				if (!isset($currentOrderDbNames[$columnDbName])) continue;
				$orderDirection = $currentOrderDbNames[$columnDbName];
				$columnUrlDir = $urlDirAsc;
				if (isset($oppositeDirections[$orderDirection]))
					$columnUrlDir = $urlDirections[$orderDirection];
				$orderParams[] = "{$columnUrlName}{$subjValueDelim}{$columnUrlDir}";
			}
		}
		return $this->GridUrl([
			'order'	=> implode($subjsDelim, $orderParams)
		]);
	}

	/**
	 * 
	 * @internal
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\Column $column 
	 * @return bool|NULL
	 */
	public function GetColumnOrderDirection (\MvcCore\Ext\Controllers\DataGrids\Configs\IColumn $column) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		$columnDbName = $column->GetDbColumnName();
		if (!isset($this->ordering[$columnDbName])) 
			return NULL;
		$orderingDirection = $this->ordering[$columnDbName];
		static $resultDirections = [
			'ASC'	=> TRUE,
			'DESC'	=> FALSE,
		];
		if (!isset($resultDirections[$orderingDirection])) 
			return TRUE;
		return $resultDirections[$orderingDirection];
	}

	/**
	 * @internal
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\IColumn $column 
	 * @return int|NULL
	 */
	public function GetColumnFiltered (\MvcCore\Ext\Controllers\DataGrids\Configs\IColumn $column) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		$columnDbName = $column->GetDbColumnName();
		if (!isset($this->filtering[$columnDbName])) 
			return NULL;
		return array_search($columnDbName, array_keys($this->filtering), TRUE);
	}

	/**
	 * @internal
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\IColumn $column 
	 * @return int|NULL
	 */
	public function GetColumnOrderIndex (\MvcCore\Ext\Controllers\DataGrids\Configs\IColumn $column) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		$columnDbName = $column->GetDbColumnName();
		if (!isset($this->ordering[$columnDbName])) 
			return NULL;
		return array_search($columnDbName, array_keys($this->ordering), TRUE);
	}
}