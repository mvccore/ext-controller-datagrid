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
	public function GetSorting () {
		return $this->sorting;
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
	 * @return bool
	 */
	public function GetTranslate () {
		return $this->translate;
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
			$gridParam = $this->GetParam(static::URL_PARAM_GRID, FALSE); // get param from application request object
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
		$requestedParams = $this->GetUrlParams();
		
		$gridReq = $this->GetGridRequest();
		list ($gridParam) = $route->Url(
			$gridReq,
			$gridParams,
			$requestedParams,
			$this->queryStringParamsSepatator,
			FALSE
		);

		$selfParams = [static::URL_PARAM_GRID => $gridParam];
		if (array_key_exists(static::URL_PARAM_ACTION, $gridParams) && $gridParams[static::URL_PARAM_ACTION] === NULL)
			$selfParams[static::URL_PARAM_ACTION] = NULL;

		return $this->Url('self', $selfParams);
	}
	
	/**
	 * 
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\Column $column 
	 * @param  mixed                                             $cellValue 
	 * @param  string                                            $operator
	 * @return string
	 */
	public function GridFilterUrl (\MvcCore\Ext\Controllers\DataGrids\Configs\IColumn $column, $cellValue, $operator = '=') {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		$configUrlSegments = $this->configUrlSegments;
		$subjValueDelim = $configUrlSegments->GetUrlDelimiterSubjectValue();
		$valuesDelim = $configUrlSegments->GetUrlDelimiterValues();
		$subjsDelim = $configUrlSegments->GetUrlDelimiterSubjects();
		$urlFilterOperators = $configUrlSegments->GetUrlFilterOperators();
		
		$currentColumnUrlName = $column->GetUrlName();
		$currentColumnDbName = $column->GetDbColumnName();
		$currentFilterDbNames = array_merge([], $this->filtering);
		if (isset($currentFilterDbNames[$currentColumnDbName])) {
			$currentFilterOperatorsAndValues = $currentFilterDbNames[$currentColumnDbName];
			if (!isset($currentFilterOperatorsAndValues[$operator]))
				$currentFilterOperatorsAndValues[$operator] = [];
			if (!in_array($cellValue, $currentFilterOperatorsAndValues[$operator], FALSE))
				$currentFilterOperatorsAndValues[$operator][] = $cellValue;
		} else {
			$currentFilterOperatorsAndValues = [$operator => [$cellValue]];
		}
		foreach ($currentFilterOperatorsAndValues as $operator => $filterValues) {
			$filterUrlValues = implode($valuesDelim, $filterValues);
			$operatorUrlValue = $urlFilterOperators[$operator];
			$filterParams[] = "{$currentColumnUrlName}{$subjValueDelim}{$operatorUrlValue}{$subjValueDelim}{$filterUrlValues}";
		}
		unset($currentFilterDbNames[$currentColumnDbName]);
		
		$multiFiltering = ($this->filteringMode & static::FILTER_MULTIPLE_COLUMNS) != 0;
		if ($multiFiltering) {
			foreach ($this->configColumns->GetArray() as $columnUrlName => $columnConfig) {
				$columnDbName = $columnConfig->GetDbColumnName();
				if (!isset($currentFilterDbNames[$columnDbName])) continue;
				$filterOperatorsAndValues = $currentFilterDbNames[$columnDbName];
				foreach ($filterOperatorsAndValues as $operator => $filterValues) {
					$filterUrlValues = implode($valuesDelim, $filterValues);
					$operatorUrlValue = $urlFilterOperators[$operator];
					$filterParams[] = "{$columnUrlName}{$subjValueDelim}{$operatorUrlValue}{$subjValueDelim}{$filterUrlValues}";
				}
				
			}
		}
		return $this->GridUrl([
			'filter'	=> implode($subjsDelim, $filterParams)
		]);
	}

	/**
	 * 
	 * @param  int $offset
	 * @return string
	 */
	public function GridPageUrl ($offset) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		$itemsPerPage = $this->itemsPerPage;
		if (
			$this->itemsPerPage === 0 && (
				$this->configRendering->GetRenderControlPaging() & static::CONTROL_DISPLAY_ALWAYS
			) != 0
		) $itemsPerPage = $this->totalCount;
		$page = intdiv($offset, $itemsPerPage) + 1;
		return $this->GridUrl(['page' => $page]);
	}
	
	/**
	 * 
	 * @param  int $count
	 * @return string
	 */
	public function GridCountUrl ($count) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		$page = $this->page;
		if ($count === 0) {
			// if count is unlimited - page will be always the first:
			$page = 1;
		} else {
			$firstItemInTargetCount = ($this->page - 1) * $count;
			if ($firstItemInTargetCount > $this->totalCount) {
				// page value is higher than total count, change page to last page by target count:
				$page = intval(ceil(floatval($this->totalCount) / floatval($count)));
			} else if ($count !== $this->itemsPerPage) {
				// target count is different than current count, choose page to display the same first item:
				$firstItemInCurrentCount = ($this->page - 1) * $this->itemsPerPage;
				$page = floor(floatval($firstItemInCurrentCount) / floatval($count)) + 1;
			}
		}
		return $this->GridUrl([
			'page'	=> $page,
			'count'	=> $count,
		]);
	}

	/**
	 * 
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\Column $column 
	 * @return string
	 */
	public function GridSortUrl (\MvcCore\Ext\Controllers\DataGrids\Configs\IColumn $column) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		$configUrlSegments = $this->configUrlSegments;
		$urlDirAsc = $configUrlSegments->GetUrlSuffixSortAsc();
		$urlDirDesc = $configUrlSegments->GetUrlSuffixSortDesc();
		$subjValueDelim = $configUrlSegments->GetUrlDelimiterSubjectValue();
		$subjsDelim = $configUrlSegments->GetUrlDelimiterSubjects();
		$currentColumnUrlDir = $urlDirAsc;
		$currentColumnUrlName = $column->GetUrlName();
		$currentColumnDbName = $column->GetDbColumnName();
		$currentSortDbNames = array_merge([], $this->sorting);
		$oppositeDirections = [
			'ASC'	=> $urlDirDesc,
			'DESC'	=> '',
		];
		if (isset($currentSortDbNames[$currentColumnDbName])) {
			$sortDirection = $currentSortDbNames[$currentColumnDbName];
			unset($currentSortDbNames[$currentColumnDbName]);
			if (isset($oppositeDirections[$sortDirection]))
				$currentColumnUrlDir = $oppositeDirections[$sortDirection];
		}
		$sortParams = [];
		if ($currentColumnUrlDir)
			$sortParams[] = "{$currentColumnUrlName}{$subjValueDelim}{$currentColumnUrlDir}";
		$multiSorting = ($this->sortingMode & static::SORT_MULTIPLE_COLUMNS) != 0;
		if ($multiSorting) {
			$urlDirections = [
				'ASC'	=> $urlDirAsc,
				'DESC'	=> $urlDirDesc,
			];
			foreach ($this->configColumns->GetArray() as $columnUrlName => $columnConfig) {
				$columnDbName = $columnConfig->GetDbColumnName();
				if (!isset($currentSortDbNames[$columnDbName])) continue;
				$sortDirection = $currentSortDbNames[$columnDbName];
				$columnUrlDir = $urlDirAsc;
				if (isset($oppositeDirections[$sortDirection]))
					$columnUrlDir = $urlDirections[$sortDirection];
				$sortParams[] = "{$columnUrlName}{$subjValueDelim}{$columnUrlDir}";
			}
		}
		return $this->GridUrl([
			'sort'	=> count($sortParams) > 0 
				? implode($subjsDelim, $sortParams) 
				: NULL
		]);
	}

	/**
	 * 
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\Column $column 
	 * @return bool|NULL
	 */
	public function GetColumnSortDirection (\MvcCore\Ext\Controllers\DataGrids\Configs\IColumn $column) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		$columnDbName = $column->GetDbColumnName();
		if (!isset($this->sorting[$columnDbName])) 
			return NULL;
		$sortingDirection = $this->sorting[$columnDbName];
		static $resultDirections = [
			'ASC'	=> TRUE,
			'DESC'	=> FALSE,
		];
		if (!isset($resultDirections[$sortingDirection])) 
			return TRUE;
		return $resultDirections[$sortingDirection];
	}

	/**
	 * 
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
	 * 
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\IColumn $column 
	 * @return int|NULL
	 */
	public function GetColumnSortIndex (\MvcCore\Ext\Controllers\DataGrids\Configs\IColumn $column) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		$columnDbName = $column->GetDbColumnName();
		if (!isset($this->sorting[$columnDbName])) 
			return NULL;
		return array_search($columnDbName, array_keys($this->sorting), TRUE);
	}

	/**
	 * 
	 * @return array
	 */
	public function __debugInfo () {
		$type = new \ReflectionClass($this);
		$props = $type->getProperties(
			\ReflectionProperty::IS_PRIVATE |
			\ReflectionProperty::IS_PROTECTED |
			\ReflectionProperty::IS_PUBLIC |
			\ReflectionProperty::IS_STATIC
		);
		$result = [];
		$phpWithTypes = PHP_VERSION_ID >= 70400;
		foreach ($props as $prop) {
			if (!$prop->isPublic()) $prop->setAccessible(TRUE);
			$value = NULL;
			if ($phpWithTypes) {
				if ($prop->isStatic() && $prop->isInitialized()) {
					$value = $prop->getValue();
				} else if ($prop->isInitialized($this)) {
					$value = $prop->getValue($this);
				}
			} else {
				$value = $prop->isStatic()
					? $prop->getValue()
					: $prop->getValue($this);
			}
			if ($value instanceof \Closure) 
				$value = '\\Closure';
			$result[$prop->name] = $value;
		}
		return $result;
	}

	/**
	 * 
	 * @return \string[]
	 */
	public function __sleep () {
		$type = new \ReflectionClass($this);
		$props = $type->getProperties(
			\ReflectionProperty::IS_PRIVATE |
			\ReflectionProperty::IS_PROTECTED |
			\ReflectionProperty::IS_PUBLIC
		);
		$result = [];
		foreach ($props as $prop) {
			if ($prop->isStatic()) continue;
			if ($prop->name === 'translator') continue;
			$result[] = $prop->name;
		}
		return $result;
	}
}