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

use \MvcCore\Ext\Controllers\DataGrids\Configs\Type;

/**
 * @mixin \MvcCore\Ext\Controllers\DataGrid
 */
trait InternalGettersSetters {
	
	/**
	 * @inheritDoc
	 * @return array
	 */
	public function GetFilterOperatorPrefixes () {
		return $this->filterOperatorPrefixes;
	}

	/**
	 * @inheritDoc
	 * @return ?int
	 */
	public function GetPage () {
		return $this->page;
	}
	
	/**
	 * @inheritDoc
	 * @return ?int
	 */
	public function GetCount () {
		return $this->count;
	}

	/**
	 * @inheritDoc
	 * @return ?int
	 */
	public function GetOffset () {
		return $this->offset;
	}

	/**
	 * @inheritDoc
	 * @return ?int
	 */
	public function GetLimit () {
		return $this->limit;
	}

	/**
	 * @inheritDoc
	 * @return ?int
	 */
	public function GetPagesCount () {
		return $this->pagesCount;
	}

	/**
	 * @inheritDoc
	 * @return ?\MvcCore\Ext\Controllers\DataGrids\Iterators\Paging
	 */
	public function GetPaging () {
		return $this->paging;
	}
	
	/**
	 * @inheritDoc
	 * @return bool
	 */
	public function GetTranslate () {
		return $this->translate;
	}
	
	/**
	 * @inheritDoc
	 * @return ?int
	 */
	public function GetTotalCount () {
		return $this->totalCount;
	}
	
	/**
	 * @inheritDoc
	 * @return array|\MvcCore\Ext\Models\Db\Readers\Streams\Iterator|null
	 */
	public function GetPageData () {
		return $this->pageData;
	}

	/**
	 * @inheritDoc
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
	 * @inheritDoc
	 * @param  ?string $controllerActionOrRouteName Should be `"Controller:Action"` combination or just any route name as custom specific string.
	 * @param  array   $params                      Optional, array with params, key is param name, value is param value.
	 * @throws \InvalidArgumentException            Grid doesn't contain given column name, unknown sort direction, unknown filter format...
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
				$this->urlParams
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
	 * @inheritDoc
	 * @return string
	 */
	public function GridUrl (array $gridParams = []) {
		list ($gridParam) = $this->route->Url(
			$this->gridRequest,
			$gridParams,
			$this->urlParams
		);
		$gridParam = rtrim(rawurldecode($gridParam), '/');
		$urlParams = [static::URL_PARAM_GRID => $gridParam];
		if (array_key_exists(static::URL_PARAM_ACTION, $gridParams) && $gridParams[static::URL_PARAM_ACTION] === NULL)
			$urlParams[static::URL_PARAM_ACTION] = NULL;
		return parent::Url($this->appRouteName, $urlParams);
	}
	
	/**
	 * @inheritDoc
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
	 * @inheritDoc
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
	 * @inheritDoc
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\Column|string $columnConfigOrPropName 
	 * @param  ?string                                                  $direction
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
	 * @inheritDoc
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
				$displayingType = $types[1];
				if ($displayingType === Type::CLIENT_DATE) {
					$cellValue .= '%';
					$operator = 'LIKE';
				} else if ($displayingType === Type::CLIENT_TIME) {
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
	 * @inheritDoc
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\Column|string $columnConfigOrPropName 
	 * @return ?bool
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
	 * @inheritDoc
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
	 * @inheritDoc
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
	 * @inheritDoc
	 * @param  string $rawValue 
	 * @param  string $specialLikeChar 
	 * @return int
	 */
	public function CheckFilterValueForSpecialLikeChar ($rawValue, $specialLikeChar) {
		$containsSpecialChar = 0;
		$index = 0;
		$length = mb_strlen($rawValue);
		$matchedEscapedChar = 0;
		while ($index < $length) {
			$specialCharPos = mb_strpos($rawValue, $specialLikeChar, $index);
			if ($specialCharPos === FALSE) break;
			$escapedSpecialCharPos = mb_strpos($rawValue, '['.$specialLikeChar.']', max(0, $index - 1));
			if ($escapedSpecialCharPos !== FALSE && $specialCharPos - 1 === $escapedSpecialCharPos) {
				$index = $specialCharPos + mb_strlen($specialLikeChar) + 1;
				$matchedEscapedChar = 2;
				continue;
			}
			$index = $specialCharPos + 1;
			$containsSpecialChar = 1;
			break;
		}
		return $containsSpecialChar | $matchedEscapedChar;
	}
	
	/**
	 * @inheritDoc
	 * @return array|[string, \string[]]
	 */
	public function GetGridCacheKeyAndTags () {
		$cacheKey = "grid_{$this->creationPlaceImprint}";
		return [$cacheKey, ['grid']];
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
	 * Complete missing values where necessary for configuration input.
	 * @param  array|array<string,\MvcCore\Ext\Controllers\DataGrids\Configs\Column> $configColumn
	 * @param  bool                                                                  $throwInvalidTypeError
	 * @return array|array<string, \MvcCore\Ext\Controllers\DataGrids\Configs\IColumn>
	 */
	protected function configColumnsCompleteMissing (array $configColumnsArr, & $throwInvalidTypeError) {
		$result = [];
		foreach ($configColumnsArr as $index => $configColumn) {
			if ($configColumn instanceof \MvcCore\Ext\Controllers\DataGrids\Configs\IColumn) {
				$propName = $configColumn->GetPropName();
				if ($propName === NULL) throw new \InvalidArgumentException(
					"Datagrid column configuration item requires `propName` (index: {$index})."
				);
				$urlName = $configColumn->GetUrlName();
				if ($urlName === NULL) {
					$urlName = $propName;
					$configColumn->SetUrlName($urlName);
				}
				$dbColumnName = $configColumn->GetDbColumnName();
				if ($dbColumnName === NULL) 
					$configColumn->SetDbColumnName($propName);
				$headingName = $configColumn->GetHeadingName();
				if ($headingName === NULL) 
					$configColumn->SetHeadingName($propName);
				$result[$urlName] = $configColumn;
			} else {
				$throwInvalidTypeError = TRUE;
				break;
			}
		}
		return $result;
	}

	/**
	 * Complete config columns for first time, if there was nothing in cache.
	 * @throws \InvalidArgumentException
	 * @return void
	 */
	protected function configColumnsParseTranslateValidate () {
		$model = $this->GetModel();
		if ($model instanceof \MvcCore\Ext\Controllers\DataGrids\Models\IGridColumns) {
			/** @var \MvcCore\Ext\Controllers\DataGrids\Models\TGridColumns $model */
			$configColumnsArr = $model->SetGrid($this)->GetConfigColumns();
		} else {
			$configColumnsArr = $this->ParseConfigColumns();
		}
		if (is_array($configColumnsArr) && count($configColumnsArr) > 0) {
			$this->configColumnsReindex($configColumnsArr);
			if ($this->translate)
				$configColumnsArr = $this->configColumnsTranslate($configColumnsArr);
			$this->configColumnsValidateNames($configColumnsArr);
			$this->configColumns = new \MvcCore\Ext\Controllers\DataGrids\Iterators\Columns(
				$configColumnsArr
			);
		} else {
			throw new \InvalidArgumentException(implode("\n", array_map('rtrim', [
				'There was not possible to complete datagrid columns from given model instance.									',
				'																												',
				'You can choose one of followng options:																		',
				'1. Set columns directly by `$grid->SetConfigColumns(\MvcCore\Ext\Controllers\DataGrids\Configs\Column[])`,		',
				'2. Implement interface `\\MvcCore\\Ext\\Controllers\\DataGrids\\Models\\IGridColumns`							',
				'   on `$model` class given by setter `$grid->SetModel($model)`													',
				'   and there you can define or parse columns in implemented method `GetConfigColumns()`,						',
				'3. Set row class name by `$this->SetRowClass(...)` with implemented interface:									',
				'   `\MvcCore\Ext\Controllers\DataGrids\AgGrids\Models\IGridRow`,												',
				'   and there you can define columns by decorating properties with attribute class								',
				'   `\\MvcCore\\Ext\\Controllers\\DataGrids\\Configs\\Column`													',
				'   or with equivalent PHPDocs tag names `@var` and `@datagrid Column({})`.										',
				'4. You can use point 2. and 3. together and parse columns from defined row model class							',
				'   in method `GetConfigColumns()` in your grid model by call `$columns = $this->grid->ParseConfigColumns()`,	',
				'   than you can add any more columns dynamicly.																',
			])));
		}
	}

	/**
	 * Reindex all columns into sequential index from `0` to `n` without configuration holes.
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	protected function configColumnsReindex (array & $configColumns) {
		$index = 0;
		$columnsWithIndexes = [];
		$columnsWithoutIndexes = [];
		foreach ($configColumns as $urlName => $configColumn) {
			$columnIndex = $configColumn->GetColumnIndex();
			if ($columnIndex === NULL) {
				$columnsWithoutIndexes[] = $urlName;
			} else if (isset($columnsWithIndexes[$columnIndex])) {
				$columnsWithIndexes[$columnIndex][] = $urlName;
			} else {
				$columnsWithIndexes[$columnIndex] = [$urlName];
			}
		}
		ksort($columnsWithIndexes, SORT_NUMERIC);
		foreach ($columnsWithIndexes as $columnIndex => $urlNames) {
			foreach ($urlNames as $urlName) {
				$configColumn = $configColumns[$urlName];
				$configColumn->SetColumnIndex($index++);
			}
		}
		$index = -1;
		$columnsWithoutIndexes = array_reverse($columnsWithoutIndexes);
		foreach ($columnsWithoutIndexes as $urlName) {
			$configColumn = $configColumns[$urlName];
			$configColumn->SetColumnIndex($index--);
		}
		return $this;
	}
	
	/**
	 * Validate config columns url names for invalid characters.
	 * @throws \InvalidArgumentException
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	protected function configColumnsValidateNames (array & $configColumns) {
		$configColumnsUrlNames = array_keys($configColumns);
		$configColumnsUrlNamesStr = implode("\n", $configColumnsUrlNames);
		$configUrlSegments = $this->GetConfigUrlSegments();
		$notAllowedCharsInUrlNames = [
			$configUrlSegments->GetUrlDelimiterSubjectValue(),
			$configUrlSegments->GetUrlDelimiterSubjects(),
		];
		foreach ($notAllowedCharsInUrlNames as $notAllowedCharInUrlNames) {
			if (mb_strpos($configColumnsUrlNamesStr, $notAllowedCharInUrlNames) !== FALSE) {
				foreach ($configColumnsUrlNames as $configColumnsUrlName) {
					if (mb_strpos($configColumnsUrlName, $notAllowedCharInUrlNames) !== FALSE) {
						throw new \InvalidArgumentException(
							"Datagrid column configuration url name `{$configColumnsUrlName}` ".
							"contains not allowed grid url segment character `{$notAllowedCharInUrlNames}`. ".
							"Try to configure different grid url segment or different property url name."
						);
					}
				}
			}
		}
		if (isset($configColumns[static::URL_PARAM_GRID]))
			throw new \InvalidArgumentException(
				"Datagrid column can't have url name with system value `{".static::URL_PARAM_GRID."}`."
			);
		return $this;
	}
	
	/**
	 * If following property is not `NULL`, translate properties: `urlName`, `headingName`, and `title`.
	 * @param  array|array<string,\MvcCore\Ext\Controllers\DataGrids\Configs\Column> $configColumn
	 * @return array|array<string,\MvcCore\Ext\Controllers\DataGrids\Configs\Column>
	 */
	protected function configColumnsTranslate (array $configColumns) {
		$result = [];
		foreach ($configColumns as $urlName => $columnConfig) {
			$propName = $columnConfig->GetPropName();
			$headingName = $columnConfig->GetHeadingName();
			if ($propName === $headingName) {
				$result[$propName] = $columnConfig;
				continue;
			}
			$title = $columnConfig->GetTitle();
			if ($headingName !== NULL) {
				if (is_array($headingName)) {
					$headingName = call_user_func_array($this->translator, [$headingName[0], $headingName[1]]);	
				} else {
					$headingName = call_user_func_array($this->translator, [$headingName]);	
				}
				$columnConfig->SetHeadingName($headingName);
			}
			if ($this->translateUrlNames) {
				if (is_array($headingName)) {
					$urlName = call_user_func_array($this->translator, [$urlName[0], $urlName[1]]);
				} else {
					$urlName = call_user_func_array($this->translator, [$urlName]);
				}
				$columnConfig->SetUrlName($urlName);
			}
			if ($title !== NULL) {
				if (is_array($title)) {
					$title = call_user_func_array($this->translator, [$title[0], $title[1]]);
				} else {
					$title = call_user_func_array($this->translator, [$title]);
				}
				$columnConfig->SetTitle($title);
			}
			$result[$urlName] = $columnConfig;
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
	 * @return ?string
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
	 * @return ?string
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