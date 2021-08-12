<?php

/**
 * MvcCore
 *
 * This source file is subject to the BSD 3 License
 * For the full copyright and license information, please view
 * the LICENSE.md file that are distributed with this source code.
 *
 * @copyright	Copyright (c) 2016 Tom Flidr (https://github.com/mvccore)
 * @license		https://mvccore.github.io/docs/mvccore/5.0.0/LICENSE.md
 */

namespace MvcCore\Ext\Controllers\DataGrid;

/**
 * @mixin \MvcCore\Ext\Controllers\DataGrid
 */
trait InternalGettersSetters {
	
	/**
	 * @inheritDocs
	 * @return int|NULL
	 */
	public function GetPage () {
		return $this->page;
	}

	/**
	 * @inheritDocs
	 * @return int|NULL
	 */
	public function GetOffset () {
		return $this->offset;
	}

	/**
	 * @inheritDocs
	 * @return int|NULL
	 */
	public function GetLimit () {
		return $this->limit;
	}

	/**
	 * @inheritDocs
	 * @return int|NULL
	 */
	public function GetPagesCount () {
		return $this->pagesCount;
	}

	/**
	 * @inheritDocs
	 * @return \MvcCore\Ext\Controllers\DataGrids\Iterators\Paging|NULL
	 */
	public function GetPaging () {
		return $this->paging;
	}
	
	/**
	 * @inheritDocs
	 * @return bool
	 */
	public function GetTranslate () {
		return $this->translate;
	}
	
	/**
	 * @inheritDocs
	 * @return int|NULL
	 */
	public function GetTotalCount () {
		return $this->totalCount;
	}
	
	/**
	 * @inheritDocs
	 * @return array|\MvcCore\Ext\Models\Db\Readers\Streams\Iterator|NULL
	 */
	public function GetPageData () {
		return $this->pageData;
	}

	/**
	 * @inheritDocs
	 * @return \MvcCore\Request
	 */
	public function GetGridRequest () {
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
	 * @inheritDocs
	 * @return string
	 */
	public function GridUrl (array $gridParams = []) {
		list ($gridParam) = $this->route->Url(
			$this->gridRequest,
			$gridParams,
			$this->urlParams,
			$this->queryStringParamsSepatator,
			FALSE
		);
		$gridParam = rtrim(rawurldecode($gridParam), '/');
		$selfParams = [static::URL_PARAM_GRID => $gridParam];
		if (array_key_exists(static::URL_PARAM_ACTION, $gridParams) && $gridParams[static::URL_PARAM_ACTION] === NULL)
			$selfParams[static::URL_PARAM_ACTION] = NULL;
		return $this->Url('self', $selfParams);
	}
	
	/**
	 * @inheritDocs
	 * @param  int $offset
	 * @return string
	 */
	public function GridPageUrl ($offset) {
		$itemsPerPage = $this->itemsPerPage;
		if (
			$this->itemsPerPage === 0 && (
				$this->configRendering->GetRenderControlPaging() & static::CONTROL_DISPLAY_ALWAYS
			) != 0
		) $itemsPerPage = $this->totalCount;
		$page = $this->intdiv($offset, $itemsPerPage) + 1;
		$params = ['page' => $page];
		if ($this->itemsPerPage === $this->itemsPerPageRouteConfig) {
			$params['count'] = NULL;
			if ($page === 1) $params['page'] = NULL;
		}
		return $this->GridUrl($params);
	}
	
	/**
	 * @inheritDocs
	 * @param  int $count
	 * @return string
	 */
	public function GridCountUrl ($count) {
		$page = $this->page;
		if ($count === 0) {
			// if count is unlimited - page will be always the first:
			$page = 1;
		} else if ($count !== $this->itemsPerPage) {
			// target count is different than current count, choose page to display the same first item:
			$firstItemInCurrentCount = ($this->page - 1) * $this->itemsPerPage;
			$page = intval(floor(floatval($firstItemInCurrentCount) / floatval($count)) + 1);
		}
		if ($count === $this->itemsPerPageRouteConfig) {
			$count = NULL;
			if ($page === 1)
				$page = NULL;
		}
		return $this->GridUrl([
			'page'	=> $page,
			'count'	=> $count,
		]);
	}

	/**
	 * @inheritDocs
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\Column $column 
	 * @param  string|NULL                                       $direction
	 * @return string
	 */
	public function GridSortUrl (\MvcCore\Ext\Controllers\DataGrids\Configs\IColumn $column, $direction = NULL) {
		$configUrlSegments = $this->configUrlSegments;
		$urlDirAsc = $configUrlSegments->GetUrlSuffixSortAsc();
		$urlDirDesc = $configUrlSegments->GetUrlSuffixSortDesc();
		$subjValueDelim = $configUrlSegments->GetUrlDelimiterSubjectValue();
		$subjsDelim = $configUrlSegments->GetUrlDelimiterSubjects();
		$currentColumnUrlDir = $urlDirAsc;
		$currentColumnUrlName = $column->GetUrlName();
		$currentColumnDbName = $column->GetDbColumnName();
		$currentSortDbNames = array_merge([], $this->sorting);
		$urlDirections = [
			'ASC'	=> $urlDirAsc,
			'DESC'	=> $urlDirDesc,
		];
		$oppositeDirections = [
			'ASC'	=> $urlDirDesc,
			'DESC'	=> '',
		];
		if (isset($currentSortDbNames[$currentColumnDbName])) {
			if ($direction === NULL) {
				$direction = $currentSortDbNames[$currentColumnDbName];
				unset($currentSortDbNames[$currentColumnDbName]);
				if (isset($oppositeDirections[$direction]))
					$currentColumnUrlDir = $oppositeDirections[$direction];
			} else if ($direction === '') {
				$currentColumnUrlDir = '';
			} else {
				$currentColumnUrlDir = $urlDirections[strtoupper($direction)];
			}
		}
		$sortParams = [];
		if ($currentColumnUrlDir)
			$sortParams[] = "{$currentColumnUrlName}{$subjValueDelim}{$currentColumnUrlDir}";
		$multiSorting = ($this->sortingMode & static::SORT_MULTIPLE_COLUMNS) != 0;
		if ($multiSorting) {
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
		$page = $this->page;
		$count = $this->itemsPerPage;
		if ($count === $this->itemsPerPageRouteConfig) {
			$count = NULL;
			if ($page === 1) $page = NULL;
		}
		return $this->GridUrl([
			'page'	=> $page,
			'count'	=> $count,
			'sort'	=> count($sortParams) > 0 
				? implode($subjsDelim, $sortParams) 
				: NULL
		]);
	}
	
	/**
	 * @inheritDocs
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\Column $column 
	 * @param  mixed                                             $cellValue 
	 * @param  string                                            $operator
	 * @return string
	 */
	public function GridFilterUrl (\MvcCore\Ext\Controllers\DataGrids\Configs\IColumn $column, $cellValue, $operator = '=') {
		$configUrlSegments = $this->configUrlSegments;
		$subjValueDelim = $configUrlSegments->GetUrlDelimiterSubjectValue();
		$valuesDelim = $configUrlSegments->GetUrlDelimiterValues();
		$subjsDelim = $configUrlSegments->GetUrlDelimiterSubjects();
		$urlFilterOperators = $configUrlSegments->GetUrlFilterOperators();
		
		$currentColumnUrlName = $column->GetUrlName();
		$currentColumnDbName = $column->GetDbColumnName();
		$currentFilterDbNames = array_merge([], $this->filtering);
		$filterParams = [];

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
			if ($filterValues === NULL) continue;
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
		$page = $this->page;
		$count = $this->itemsPerPage;
		if ($count === $this->itemsPerPageRouteConfig) {
			$count = NULL;
			if ($page === 1) $page = NULL;
		}
		return $this->GridUrl([
			'page'		=> $page,
			'count'		=> $count,
			'filter'	=> count($filterParams) > 0
				? implode($subjsDelim, $filterParams)
				: NULL
		]);
	}

	/**
	 * @inheritDocs
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\Column $column 
	 * @return bool|NULL
	 */
	public function GetColumnSortDirection (\MvcCore\Ext\Controllers\DataGrids\Configs\IColumn $column) {
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
	 * @inheritDocs
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\IColumn $column 
	 * @return int|FALSE
	 */
	public function GetColumnSortIndex (\MvcCore\Ext\Controllers\DataGrids\Configs\IColumn $column) {
		$columnDbName = $column->GetDbColumnName();
		return array_search($columnDbName, array_keys($this->sorting), TRUE);
	}

	/**
	 * @inheritDocs
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\IColumn $column 
	 * @return int|FALSE
	 */
	public function GetColumnFilterIndex (\MvcCore\Ext\Controllers\DataGrids\Configs\IColumn $column) {
		$columnDbName = $column->GetDbColumnName();
		return array_search($columnDbName, array_keys($this->filtering), TRUE);
	}

	/**
	 * Return serializable properties and values for debug dump.
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
	 * Return array of properties to serialize.
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
			if ($prop->name === 'translator') continue;
			$result[] = $prop->name;
		}
		return $result;
	}

	/**
	 * Function `intdiv()` for older PHP versions.
	 * @param  int|float $a 
	 * @param  int|float $b 
	 * @return int
	 */
	protected function intdiv ($a, $b){
		$aInt = intval(floor($a));
		$bInt = intval(floor($b));
		return ($aInt - ($a % $b)) / $bInt;
	}
}