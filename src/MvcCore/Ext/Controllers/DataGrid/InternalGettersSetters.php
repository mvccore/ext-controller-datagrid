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
	 * @param  string $controllerActionOrRouteName Should be `"Controller:Action"` combination or just any route name as custom specific string.
	 * @param  array  $params                      Optional, array with params, key is param name, value is param value.
	 * @throws \InvalidArgumentException           Grid doesn't contain given column name, unknown sort direction, unknown filter format...
	 * @return string
	 */
	public function Url ($controllerActionOrRouteName = 'Index:Index', array $params = []) {
		if (!$this->appUrlCompletionInit)
			$this->initAppUrlCompletion();
		if (isset($params[static::URL_PARAM_GRID])) {
			$rawGridParams = $params[static::URL_PARAM_GRID];
			$page = $this->urlParams['page'];
			$count = $this->urlParams['count'];
			if (isset($rawGridParams['page'])) 
				$page = $rawGridParams['page'];
			if (isset($rawGridParams['count'])) 
				$count = $rawGridParams['count'];
			if ($count === $this->itemsPerPageRouteConfig) {
				$count = NULL;
				if ($page === 1) $page = NULL;
			}
			$gridParams = [
				'page'	=> $page,
				'count'	=> $count,
			];
			if (isset($rawGridParams['sort'])) 
				$gridParams['sort'] = $this->urlCompleteSortParam($rawGridParams['sort']);
			if (isset($rawGridParams['filter'])) 
				$gridParams['filter'] = $this->urlCompleteFilterParam($rawGridParams['filter']);
			list ($gridParam) = $this->route->Url(
				$this->gridRequest,
				$gridParams,
				$this->urlParams,
				$this->queryStringParamsSepatator,
				FALSE
			);
			$params[static::URL_PARAM_GRID] = rtrim(rawurldecode($gridParam), '/');
		}
		return parent::Url($controllerActionOrRouteName, $params);
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
		$urlParams = [static::URL_PARAM_GRID => $gridParam];
		if (array_key_exists(static::URL_PARAM_ACTION, $gridParams) && $gridParams[static::URL_PARAM_ACTION] === NULL)
			$urlParams[static::URL_PARAM_ACTION] = NULL;
		return parent::Url($this->appRouteName, $urlParams);
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
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\Column|string $columnConfigOrPropName 
	 * @param  string|NULL                                              $direction
	 * @return string
	 */
	public function GridSortUrl ($columnConfigOrPropName, $direction = NULL) {
		/** @var \MvcCore\Ext\Controllers\DataGrid $this */
		$column = $columnConfigOrPropName instanceof \MvcCore\Ext\Controllers\DataGrids\Configs\IColumn
			? $columnConfigOrPropName
			: $this->configColumns->GetByPropName($columnConfigOrPropName);
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
			foreach ($currentSortDbNames as $columnDbName => $sortDirection) {
				$columnUrlDir = $urlDirAsc;
				if (isset($oppositeDirections[$sortDirection]))
					$columnUrlDir = $urlDirections[$sortDirection];
				$columnConfig = $this->configColumns->GetByDbColumnName($columnDbName);
				$columnUrlName = $columnConfig->GetUrlName();
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
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\Column|string $columnConfigOrPropName 
	 * @param  mixed                                                    $cellValue 
	 * @param  string                                                   $operator
	 * @return string
	 */
	public function GridFilterUrl ($columnConfigOrPropName, $cellValue, $operator = '=') {
		/** @var \MvcCore\Ext\Controllers\DataGrid $this */
		$column = $columnConfigOrPropName instanceof \MvcCore\Ext\Controllers\DataGrids\Configs\IColumn
			? $columnConfigOrPropName
			: $this->configColumns->GetByPropName($columnConfigOrPropName);
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
			foreach ($currentFilterDbNames as $columnDbName => $filterOperatorsAndValues) {
				$columnConfig = $this->configColumns->GetByDbColumnName($columnDbName);
				$columnUrlName = $columnConfig->GetUrlName();
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
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\Column|string $columnConfigOrPropName 
	 * @return bool|NULL
	 */
	public function GetColumnSortDirection ($columnConfigOrPropName) {
		/** @var \MvcCore\Ext\Controllers\DataGrid $this */
		$column = $columnConfigOrPropName instanceof \MvcCore\Ext\Controllers\DataGrids\Configs\IColumn
			? $columnConfigOrPropName
			: $this->configColumns->GetByPropName($columnConfigOrPropName);
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
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\Column|string $columnConfigOrPropName 
	 * @return int|FALSE
	 */
	public function GetColumnSortIndex ($columnConfigOrPropName) {
		/** @var \MvcCore\Ext\Controllers\DataGrid $this */
		$column = $columnConfigOrPropName instanceof \MvcCore\Ext\Controllers\DataGrids\Configs\IColumn
			? $columnConfigOrPropName
			: $this->configColumns->GetByPropName($columnConfigOrPropName);
		$columnDbName = $column->GetDbColumnName();
		return array_search($columnDbName, array_keys($this->sorting), TRUE);
	}

	/**
	 * @inheritDocs
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\Column|string $columnConfigOrPropName 
	 * @return int|FALSE
	 */
	public function GetColumnFilterIndex ($columnConfigOrPropName) {
		/** @var \MvcCore\Ext\Controllers\DataGrid $this */
		$column = $columnConfigOrPropName instanceof \MvcCore\Ext\Controllers\DataGrids\Configs\IColumn
			? $columnConfigOrPropName
			: $this->configColumns->GetByPropName($columnConfigOrPropName);
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

	/**
	 * Complete grid `sort` URL param for standard application `Url()` method.
	 * @param  array $sortGridParams 
	 * @return string
	 */
	protected function urlCompleteSortParam ($sortGridParams) {
		$invalidSortParamMsg = implode("\n", [
			"Datagrid unknown `sort` URL param. ".
			"Sort param has to be array with keys as column config properties names ".
			"and with values to be strings `ASC` or `DESC`."
		]);
		if (!is_array($sortGridParams)) 
			throw new \InvalidArgumentException($invalidSortParamMsg);
		$configUrlSegments = $this->configUrlSegments;
		$urlDirAsc = $configUrlSegments->GetUrlSuffixSortAsc();
		$urlDirDesc = $configUrlSegments->GetUrlSuffixSortDesc();
		$subjValueDelim = $configUrlSegments->GetUrlDelimiterSubjectValue();
		$subjsDelim = $configUrlSegments->GetUrlDelimiterSubjects();
		$urlDirections = [
			'ASC'	=> $urlDirAsc,
			'DESC'	=> $urlDirDesc,
		];
		$multiSorting = ($this->sortingMode & static::SORT_MULTIPLE_COLUMNS) != 0;
		$sortParams = [];
		if ($multiSorting) {
			// accept initial grid sorting
			$currentSortDbNames = array_merge([], $this->sorting);
			foreach ($currentSortDbNames as $columnDbName => $sortDirection) {
				$columnConfig = $this->configColumns->GetByDbColumnName($columnDbName);
				$columnPropName = $columnConfig->GetPropName();
				if (isset($sortGridParams[$columnPropName])) continue;
				$columnUrlName = $columnConfig->GetUrlName();
				$columnUrlDir = $urlDirections[$sortDirection];
				$sortParams[] = "{$columnUrlName}{$subjValueDelim}{$columnUrlDir}";
			}
		}
		// complete sort param by given sorting array
		foreach ($sortGridParams as $columnPropName => $sortDirection) {
			$columnConfig = $this->configColumns->GetByPropName($columnPropName);
			$sortDirection = strtoupper($sortDirection);
			if (!isset($urlDirections[$sortDirection]))
				throw new \InvalidArgumentException($invalidSortParamMsg);
			$columnUrlName = $columnConfig->GetUrlName();
			$columnUrlDir = $urlDirections[$sortDirection];
			$sortParams[] = "{$columnUrlName}{$subjValueDelim}{$columnUrlDir}";
			if (!$multiSorting) break;
		}
		return implode($subjsDelim, $sortParams);
	}

	/**
	 * Complete grid `filter` URL param for standard application `Url()` method.
	 * @param  array $filterGridParams 
	 * @return string
	 */
	protected function urlCompleteFilterParam ($filterGridParams) {
		$invalidFilterParamMsg = implode("\n", [
			"Datagrid unknown `filter` URL param. ".
			"Filter param has to be array with keys as column config properties names ".
			"and with values to be array with keys as allowed operator(s) ".
			"and values as column filtered values."
		]);
		if (!is_array($filterGridParams)) 
			throw new \InvalidArgumentException($invalidFilterParamMsg);
		$configUrlSegments = $this->configUrlSegments;
		$subjValueDelim = $configUrlSegments->GetUrlDelimiterSubjectValue();
		$valuesDelim = $configUrlSegments->GetUrlDelimiterValues();
		$subjsDelim = $configUrlSegments->GetUrlDelimiterSubjects();
		$urlFilterOperators = $configUrlSegments->GetUrlFilterOperators();
		$multiFiltering = ($this->filteringMode & static::FILTER_MULTIPLE_COLUMNS) != 0;
		$filterParams = [];
		if ($multiFiltering) {
			// accept initial grid filtering
			$currentFilterDbNames = array_merge([], $this->filtering);
			foreach ($currentFilterDbNames as $columnDbName => $filterOperatorsAndValues) {
				$columnConfig = $this->configColumns->GetByDbColumnName($columnDbName);
				$columnPropName = $columnConfig->GetPropName();
				if (isset($filterGridParams[$columnPropName])) continue;
				$columnUrlName = $columnConfig->GetUrlName();
				foreach ($filterOperatorsAndValues as $operator => $filterValues) {
					$filterUrlValues = implode($valuesDelim, $filterValues);
					$operatorUrlValue = $urlFilterOperators[$operator];
					$filterParams[] = "{$columnUrlName}{$subjValueDelim}{$operatorUrlValue}{$subjValueDelim}{$filterUrlValues}";
				}
			}
		}
		// complete filter param by given filtering array
		foreach ($filterGridParams as $columnPropName => $filterOperatorsAndValues) {
			if (!is_array($filterOperatorsAndValues)) 
				throw new \InvalidArgumentException($invalidFilterParamMsg);
			$columnConfig = $this->configColumns->GetByPropName($columnPropName);
			// check if filtering is allowed for this column
			$columnFilterCfg = $columnConfig->GetFilter();
			$columnHasAllowedFiltering = (!$columnConfig->GetDisabled() && (
				is_bool($columnFilterCfg) || 
				(is_int($columnFilterCfg) && $columnFilterCfg !== 0)
			));
			if (!$columnHasAllowedFiltering) 
				throw new \InvalidArgumentException("Datagrid doesn't allow to filter by column `$columnPropName`.");
			$allowedOperators = is_integer($columnFilterCfg)
				? $this->columnsAllowedOperators[$columnConfig->GetPropName()]
				: $this->defaultAllowedOperators;
			// complete filter URL segment for this column
			$columnUrlName = $columnConfig->GetUrlName();
			foreach ($filterOperatorsAndValues as $operator => $filterValues) {
				$operatorUrlValue = $urlFilterOperators[$operator];
				if (!isset($allowedOperators[$operatorUrlValue])) 
					throw new \InvalidArgumentException("Datagrid doesn't allow to filter by operator `{$operator}`, column `$columnPropName`.");
				$operatorCfg = $allowedOperators[$operatorUrlValue];
				$multiple = $operatorCfg->multiple;
				$regex = $operatorCfg->regex;
				if (!$multiple && count($filterValues) > 1)
					throw new \InvalidArgumentException("Datagrid doesn't allow to filter by multiple values in column `$columnPropName`.");
				if ($regex !== NULL) {
					$newValues = [];
					foreach ($filterValues as $value)
						if (preg_match($regex, $value))
							$newValues[] = $value;
					if (count($newValues) === 0) 
						throw new \InvalidArgumentException("Datagrid doesn't allow given filter value in column `$columnPropName`.");
					$filterValues = $newValues;
				}
				$filterUrlValues = is_array($filterValues)
					? implode($valuesDelim, $filterValues)
					: (string) $filterValues;
				$filterUrlValues = implode($valuesDelim, $filterValues);
				$operatorUrlValue = $urlFilterOperators[$operator];
				$filterParams[] = "{$columnUrlName}{$subjValueDelim}{$operatorUrlValue}{$subjValueDelim}{$filterUrlValues}";
				if (!$multiFiltering) break;
			}
			if (!$multiFiltering) break;
		}
		return implode($subjsDelim, $filterParams);
	}
}