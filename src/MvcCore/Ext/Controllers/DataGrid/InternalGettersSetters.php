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
	 * @return array
	 */
	public function GetFilterOperatorPrefixes () {
		return $this->filterOperatorPrefixes;
	}

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
	public function GetCount () {
		return $this->count;
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
	 * @param  string|NULL $controllerActionOrRouteName Should be `"Controller:Action"` combination or just any route name as custom specific string.
	 * @param  array       $params                      Optional, array with params, key is param name, value is param value.
	 * @throws \InvalidArgumentException                Grid doesn't contain given column name, unknown sort direction, unknown filter format...
	 * @return string
	 */
	public function Url ($controllerActionOrRouteName = NULL, array $params = []) {
		if (!$this->appUrlCompletionInit)
			$this->initAppUrlCompletion();
		if (isset($params[static::URL_PARAM_GRID])) {
			$rawGridParams = $params[static::URL_PARAM_GRID];
			$page = isset($this->urlParams[static::URL_PARAM_PAGE])
				? $this->urlParams[static::URL_PARAM_PAGE]
				: $this->page;
			$count = isset($this->urlParams[static::URL_PARAM_COUNT])
				? $this->count
				: $this->itemsPerPage;
			if (isset($rawGridParams[static::URL_PARAM_PAGE])) 
				$page = $rawGridParams[static::URL_PARAM_PAGE];
			if (isset($rawGridParams[static::URL_PARAM_COUNT])) 
				$count = $rawGridParams[static::URL_PARAM_COUNT];
			if ($count === $this->itemsPerPage) {
				$count = NULL;
				if ($page === 1) $page = NULL;
			}
			$gridParams = [
				static::URL_PARAM_PAGE	=> $page,
				static::URL_PARAM_COUNT	=> $count,
			];
			$clear = isset($rawGridParams[static::URL_PARAM_CLEAR]) && $rawGridParams[static::URL_PARAM_CLEAR];
			if (isset($rawGridParams[static::URL_PARAM_SORT])) 
				$gridParams[static::URL_PARAM_SORT] = $this->urlCompleteSortParam($rawGridParams[static::URL_PARAM_SORT], $clear);
			if (isset($rawGridParams[static::URL_PARAM_FILTER])) 
				$gridParams[static::URL_PARAM_FILTER] = $this->urlCompleteFilterParam($rawGridParams[static::URL_PARAM_FILTER], $clear);
			list ($gridParam) = $this->route->Url(
				$this->gridRequest,
				$gridParams,
				$this->urlParams,
				$this->queryStringParamsSepatator,
				FALSE
			);
			$params[static::URL_PARAM_GRID] = rtrim(rawurldecode($gridParam), '/');
		}
		return parent::Url(
			$controllerActionOrRouteName !== NULL
				? $controllerActionOrRouteName
				: $this->appRouteName, // `self` by default
			$params
		);
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
		$displayingCount = $this->count;
		if (
			$displayingCount === 0 && (
				$this->configRendering->GetRenderControlPaging() & static::CONTROL_DISPLAY_ALWAYS
			) != 0
		) $displayingCount = $this->totalCount;
		$page = $this->intdiv($offset, $displayingCount) + 1;
		$params = [static::URL_PARAM_PAGE => $page];
		if ($this->count === $this->itemsPerPage) {
			$params[static::URL_PARAM_COUNT] = NULL;
			if ($page === 1) $params[static::URL_PARAM_PAGE] = NULL;
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
		} else if ($count !== $this->count) {
			// target count is different than current count, choose page to display the same first item:
			$firstItemInCurrentCount = ($this->page - 1) * $this->count;
			$page = intval(floor(floatval($firstItemInCurrentCount) / floatval($count)) + 1);
		}
		if ($count === $this->itemsPerPage) {
			$count = NULL;
			if ($page === 1)
				$page = NULL;
		}
		return $this->GridUrl([
			static::URL_PARAM_PAGE	=> $page,
			static::URL_PARAM_COUNT	=> $count,
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
		$count = $this->count;
		if ($count === $this->itemsPerPage) {
			$count = NULL;
			if ($page === 1) $page = NULL;
		}
		return $this->GridUrl([
			static::URL_PARAM_PAGE	=> $page,
			static::URL_PARAM_COUNT	=> $count,
			static::URL_PARAM_SORT	=> count($sortParams) > 0 
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
	public function GridFilterUrl ($columnConfigOrPropName, $cellValue, $operator = NULL) {
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

		// special behaviour for datetimes displayed as dates:
		if (
			$operator === NULL &&
			$column->GetIsDateTime() &&
			substr($cellValue, strlen($cellValue) - 1, 1) !== '%' // not ending with `...%` already
		) {
			$types = $column->GetTypes();
			if (count($types) > 1) {
				$secondType = $types[1];
				if ($secondType === 'Date') {
					$cellValue .= '%';
					$operator = 'LIKE';
				} else if ($secondType === 'Time') {
					$cellValue = '%' . $cellValue;
					$operator = 'LIKE';
				}
			}
		}

		if ($operator === NULL) {
			$colCfgFilter = $column->GetFilter();
			if (is_bool($colCfgFilter)) {
				$operator = '=';
			} else if (is_int($colCfgFilter)) {
				if (($colCfgFilter & \MvcCore\Ext\Controllers\IDataGrid::FILTER_ALLOW_EQUALS) != 0) {
					$operator = '=';
				} else if (
					($colCfgFilter & \MvcCore\Ext\Controllers\IDataGrid::FILTER_ALLOW_LIKE_ANYWHERE) != 0 ||
					($colCfgFilter & \MvcCore\Ext\Controllers\IDataGrid::FILTER_ALLOW_LIKE_RIGHT_SIDE) != 0 ||
					($colCfgFilter & \MvcCore\Ext\Controllers\IDataGrid::FILTER_ALLOW_LIKE_LEFT_SIDE) != 0
				) {
					$operator = 'LIKE';
				} else {
					throw new \InvalidArgumentException(
						"Unknown filter configuration for column `{$currentColumnUrlName}` to automatically create filter link."
					);
				}
			}
		}

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
		$count = $this->count;
		if ($count === $this->itemsPerPage) {
			$count = NULL;
			if ($page === 1) $page = NULL;
		}
		return $this->GridUrl([
			static::URL_PARAM_PAGE		=> $page,
			static::URL_PARAM_COUNT		=> $count,
			static::URL_PARAM_FILTER	=> count($filterParams) > 0
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
		$propsNotToPrint = [
			'application'				=> FALSE,
			'parentController'			=> FALSE,
			'controller'				=> FALSE,
			'childControllers'			=> FALSE,
			'router'					=> FALSE,
			'route'						=> FALSE,
			'request'					=> FALSE,
			'response'					=> FALSE,
			'environment'				=> FALSE,
			'user'						=> FALSE,
			'layout'					=> FALSE,
			'view'						=> FALSE,
			'pageData'					=> FALSE,
			'model'						=> FALSE,
			'filteringViewHelpersCache'	=> FALSE,
			'tableHeadFilterForm'		=> FALSE,
		];
		$phpWithTypes = PHP_VERSION_ID >= 70400;
		foreach ($props as $prop) {
			if ($prop->isStatic()) 
				continue;
			if (!$prop->isPublic()) 
				$prop->setAccessible(TRUE);
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
			if (isset($propsNotToPrint[$prop->name])) {
				$value = is_object($value)
					? get_class($value)
					: gettype($value);
			} else {
				if ($value instanceof \Closure) 
					$value = '\\Closure';
				if (is_resource($value)) 
					$value = 'resource (' . get_resource_id($value) . ', ' . get_resource_type($value) . ')';
			}
			$result[$prop->name] = $value;
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
	 * @param  array|string $sortGridParams 
	 * @param  bool         $clear
	 * @return string|NULL
	 */
	protected function urlCompleteSortParam ($sortGridParams, $clear = FALSE) {
		$invalidSortParamMsg = implode("\n", [
			"Datagrid unknown `sort` URL param. ".
			"Sort param has to be array with keys as column config properties names ".
			"and with values to be strings `ASC` or `DESC`."
		]);
		if (is_string($sortGridParams))
			return $sortGridParams;
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
		if ($multiSorting && !$clear) {
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
			$columnSortCfg = $columnConfig->GetSort();
			$columnHasAllowedSorting = ((
				$this->ignoreDisabledColumns || (
					!$this->ignoreDisabledColumns && !$columnConfig->GetDisabled()
				)
			) && (
				$columnSortCfg !== FALSE && $columnSortCfg !== 0
			));
			if (!$columnHasAllowedSorting) 
				throw new \InvalidArgumentException("Datagrid doesn't allow to sort by column `$columnPropName`.");
			$sortDirection = strtoupper($sortDirection);
			if (!isset($urlDirections[$sortDirection]))
				throw new \InvalidArgumentException($invalidSortParamMsg);
			$columnUrlName = $columnConfig->GetUrlName();
			$columnUrlDir = $urlDirections[$sortDirection];
			$sortParams[] = "{$columnUrlName}{$subjValueDelim}{$columnUrlDir}";
			if (!$multiSorting) break;
		}
		if (count($sortParams) === 0) return NULL;
		return implode($subjsDelim, $sortParams);
	}

	/**
	 * Complete grid `filter` URL param for standard application `Url()` method.
	 * @param  array|string $filterGridParams 
	 * @param  bool         $clear
	 * @return string|NULL
	 */
	protected function urlCompleteFilterParam ($filterGridParams, $clear = FALSE) {
		$invalidFilterParamMsg = implode("\n", [
			"Datagrid unknown `filter` URL param. ".
			"Filter param has to be array with keys as column config properties names ".
			"and with values to be array with keys as allowed operator(s) ".
			"and values as column filtered values."
		]);
		if (is_string($filterGridParams))
			return $filterGridParams;
		if (!is_array($filterGridParams)) 
			throw new \InvalidArgumentException($invalidFilterParamMsg);
		$configUrlSegments = $this->configUrlSegments;
		$subjValueDelim = $configUrlSegments->GetUrlDelimiterSubjectValue();
		$valuesDelim = $configUrlSegments->GetUrlDelimiterValues();
		$subjsDelim = $configUrlSegments->GetUrlDelimiterSubjects();
		$urlFilterOperators = $configUrlSegments->GetUrlFilterOperators();
		$multiFiltering = ($this->filteringMode & static::FILTER_MULTIPLE_COLUMNS) != 0;
		$filterParams = [];
		if ($multiFiltering && !$clear) {
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
			$columnHasAllowedFiltering = ((
				$this->ignoreDisabledColumns || (
					!$this->ignoreDisabledColumns && !$columnConfig->GetDisabled()
				)
			) && (
				$columnFilterCfg !== FALSE && $columnFilterCfg !== 0
			));
			if (!$columnHasAllowedFiltering) 
				throw new \InvalidArgumentException("Datagrid doesn't allow to filter by column `$columnPropName`.");
			$allowedOperators = is_integer($columnFilterCfg)
				? $this->columnsAllowedOperators[$columnConfig->GetPropName()]
				: $this->defaultAllowedOperators;
			// complete filter URL segment for this column
			$columnUrlName = $columnConfig->GetUrlName();
			foreach ($filterOperatorsAndValues as $operator => $filterValues) {
				if (!is_array($filterValues)) {
					if (is_scalar($filterValues)) {
						$filterValues = [$filterValues];
					} else if ($filterValues === NULL) {
						$filterValues = [$filterValues];
					} else {
						$filterValuesType = gettype($filterValues);
						$filterValuesType = $filterValuesType === 'object' ? get_class($filterValues) : $filterValuesType;
						throw new \InvalidArgumentException("Datagrid doesn't allow to filter by value type `{$filterValuesType}`, column `$columnPropName`.");
					}
				}
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
				// TODO: vyřešit povolení null hodnoty
				$filterUrlValues = is_array($filterValues)
					? implode($valuesDelim, $filterValues)
					: (string) $filterValues;
				$operatorUrlValue = $urlFilterOperators[$operator];
				$filterParams[] = "{$columnUrlName}{$subjValueDelim}{$operatorUrlValue}{$subjValueDelim}{$filterUrlValues}";
				if (!$multiFiltering) break;
			}
			if (!$multiFiltering) break;
		}
		if (count($filterParams) === 0) return NULL;
		return implode($subjsDelim, $filterParams);
	}
}