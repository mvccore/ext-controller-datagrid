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
			$this->gridRequest = \MvcCore\Request::CreateInstance();
			$this->gridRequest->SetPath($gridParam);
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
		$defaultParams = array_merge([], $this->GetUrlParams());
		$defaultPage = $defaultParams['page'];
		$pageDefaultChange = isset($gridParams['count']) && $gridParams['count'] != $defaultParams['count'];
		if ($pageDefaultChange) {
			$defaultParams['page'] = NULL;
			$route->SetDefaults($defaultParams);
		}
		foreach ($gridParams as $paramName => $paramValue) 
			if (isset($defaultParams[$paramName]))
				unset($defaultParams[$paramName]);
		list ($gridParam) = $route->Url(
			$this->GetGridRequest(),
			$gridParams,
			$defaultParams,
			$this->queryStringParamsSepatator,
			FALSE
		);
		if ($pageDefaultChange) {
			$defaultParams['page'] = $defaultPage;
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