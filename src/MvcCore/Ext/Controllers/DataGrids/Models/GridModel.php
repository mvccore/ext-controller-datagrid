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

namespace MvcCore\Ext\Controllers\DataGrids\Models;

/**
 * @mixin \MvcCore\Model|\MvcCore\Ext\Models\Db\Model
 */
trait GridModel {

	/**
	 * Datagrid instance, always initialized by datagrid component automatically.
	 * @var \MvcCore\Ext\Controllers\DataGrid|NULL
	 */
	protected $grid = NULL;

	/**
	 * Database table offset, always initialized into integer.
	 * This offset is always initialized by datagrid component automatically.
	 * @var int|NULL
	 */
	protected $offset = NULL;
	
	/**
	 * Database table select limit, it could be initialized into integer or `NULL`.
	 * This limit is always initialized by datagrid component automatically.
	 * @var int|NULL
	 */
	protected $limit = NULL;
	
	/**
	 * Total count of database table rows by initialized fitering.
	 * @var int|NULL
	 */
	protected $totalCount = NULL;
	
	/**
	 * Page data rows or database result iterator.
	 * @var \MvcCore\Ext\Models\Db\Readers\Streams\Iterator|array|NULL
	 */
	protected $pageData = NULL;

	/**
	 * Database table filtering, keys are database table column names
	 * and values are arrays. Each key in value array is condition 
	 * operator and values are raw user input values to use in column condition.
	 * This filtering is always initialized by datagrid component automatically.
	 * @var array|NULL
	 */
	protected $filtering = NULL;
	
	/**
	 * Database table sorting, keys are database table column names 
	 * and values are sorting direction strings - `ASC | DESC`.
	 * This sorting is always initialized by datagrid component automatically.
	 * @var array|NULL
	 */
	protected $sorting = NULL;

	
	/**
	 * Set datagrid instance, always initialized by datagrid component automatically.
	 * @param  \MvcCore\Ext\Controllers\DataGrid|NULL $grid
	 * @return \MvcCore\Ext\Controllers\DataGrids\Models\GridModel
	 */
	public function SetGrid (\MvcCore\Ext\Controllers\IDataGrid $grid) {
		$this->grid = $grid;
		return $this;
	}

	/**
	 * Set database table offset, always initialized into integer.
	 * This offset is always initialized by datagrid component automatically.
	 * @param  int|NULL $offset 
	 * @return \MvcCore\Ext\Controllers\DataGrids\Models\GridModel
	 */
	public function SetOffset ($offset) {
		$this->offset = $offset;
		return $this;
	}

	/**
	 * Set database table select limit, it could be initialized into integer or `NULL`.
	 * This limit is always initialized by datagrid component automatically.
	 * @param  int|NULL $limit 
	 * @return \MvcCore\Ext\Controllers\DataGrids\Models\GridModel
	 */
	public function SetLimit ($limit) {
		$this->limit = $limit;
		return $this;
	}

	/**
	 * Set database table filtering, keys are database table column names
	 * and values are arrays. Each key in value array is condition 
	 * operator and values are raw user input values to use in column condition.
	 * This filtering is always initialized by datagrid component automatically.
	 * @param  array $filtering 
	 * @return \MvcCore\Ext\Controllers\DataGrids\Models\GridModel
	 */
	public function SetFiltering (array $filtering) {
		$this->filtering = $filtering;
		return $this;
	}

	/**
	 * Set database table sorting, keys are database table column names 
	 * and values are sorting direction strings - `ASC | DESC`.
	 * This sorting is always initialized by datagrid component automatically.
	 * @param  array $sorting
	 * @return \MvcCore\Ext\Controllers\DataGrids\Models\GridModel
	 */
	public function SetSorting (array $sorting) {
		$this->sorting = $sorting;
		return $this;
	}
	
	/**
	 * Get total count of database table rows by initialized fitering.
	 * You have to implement this method usually by your own.
	 * @return int
	 */
	public function GetTotalCount () {
		if ($this->totalCount === NULL) $this->load();
		return $this->totalCount;
	}

	/**
	 * Get page data rows or database result iterator.
	 * You have to implement this method usually by your own.
	 * @return \MvcCore\Ext\Models\Db\Readers\Streams\Iterator|array|NULL
	 */
	public function GetPageData () {
		if ($this->pageData === NULL) $this->load();
		return $this->pageData;
	}

	/**
	 * Render value with by possible view helper as scalar value 
	 * into datagrid table cell (convertable into string).
	 * @param  mixed                                                    $row
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\Column|string $columnNameOrConfig 
	 * @param  \MvcCore\View                                            $view
	 * @return string
	 */
	public function RenderCell ($row, $columnPropNameOrConfig, \MvcCore\IView $view) {
		if ($columnPropNameOrConfig instanceof \MvcCore\Ext\Controllers\DataGrids\Configs\IColumn) {
			$column = $columnPropNameOrConfig;
			$propName = $column->GetPropName();
		} else {
			$column = $this->grid->GetConfigColumns()->GetByPropName($columnPropNameOrConfig);
			$propName = $columnPropNameOrConfig;
		}
		$value = $row->{'Get' . ucfirst($propName)}();
		if ($value !== NULL) {
			$viewHelperName = $column->GetViewHelper();
			if ($viewHelperName) {
				return call_user_func_array(
					[$view, $viewHelperName], 
					array_merge([$value], $column->GetFormat() ?: [])
				);
			} else {
				return static::convertToScalar(
					$value, $column->GetFormat()
				);
			}
		}
		return NULL;
	}
	
	/**
	 * Get scalar value used in URL for filtering (convertable into string).
	 * @param  mixed                                                    $row
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\Column|string $columnPropNameOrConfig 
	 * @return string
	 */
	public function GetFilterUrlValue ($row, $columnPropNameOrConfig) {
		if ($columnPropNameOrConfig instanceof \MvcCore\Ext\Controllers\DataGrids\Configs\IColumn) {
			$column = $columnPropNameOrConfig;
			$propName = $column->GetPropName();
		} else {
			$column = $this->grid->GetConfigColumns()->GetByPropName($columnPropNameOrConfig);
			$propName = $columnPropNameOrConfig;
		}
		$value = $row->{'Get' . ucfirst($propName)}();
		if ($value === NULL) {
			$columnFilter = $column->GetFilter();
			if (is_int($columnFilter) && ($columnFilter & \MvcCore\Ext\Controllers\IDataGrid::FILTER_ALLOW_NULL) != 0)
				return 'null';
			return '';
		}
		return static::convertToScalar(
			$value, $column->GetFormat()
		);
	}
	
	/**
	 * Complete ORDER BY condition SQL part by `$this->sorting` array given from datagrid.
	 * @param  bool        $includeOrderBy Include ` WHERE ` keyword.
	 * @param  string|NULL $columnsAlias   Optional SQL alias for each column.
	 * @param  string      $quoteChars     One or two characters to quote column identifiers from left and right.
	 * @return string
	 */
	protected function getSortingSql ($includeOrderBy = TRUE, $columnsAlias = NULL, $quoteChars = '""') {
		$sortSqlItems = [];
		$alias = $columnsAlias === NULL ? '' : $columnsAlias . '.';
		$quotes = static::prepareQuotes($quoteChars);
		foreach ($this->sorting as $columnName => $direction) {
			$columnNameQuoted = static::quoteColumn($columnName, $quotes);
			$sortSqlItems[] = "{$alias}{$columnNameQuoted} {$direction}";
		}
		$sortSql = '';
		if (count($sortSqlItems) > 0) {
			$sortSql = (
				($includeOrderBy ? " ORDER BY " : "") . 
				implode(", ", $sortSqlItems) . " "
			);
		}
		return $sortSql;
	}

	/**
	 * Complete WHERE condition SQL part by `$this->filtering` array given from datagrid.
	 * @param  bool        $includeWhere  Include ` WHERE ` keyword.
	 * @param  string|NULL $columnsAlias  Optional SQL alias for each column.
	 * @param  array       $params        Optional query params array, empty or with any initialized value from before.
	 * @param  string      $paramBaseName Base name for every created param inside this method.
	 * @param  string      $quoteChars    One or two characters to quote column identifiers from left and right.
	 * @return array [string $conditionsSql, array $params]
	 */
	protected function getConditionSqlAndParams ($includeWhere = TRUE, $columnsAlias = NULL, $params = [], $paramBaseName = ':p', $quoteChars = '""') {
		static $inOperators = [
			'='		=> 'IN',
			'!='	=> 'NOT IN',
		];
		$conditionSqlItems = [];
		$alias = $columnsAlias === NULL ? '' : $columnsAlias . '.';
		if ($params === NULL) $params = [];
		if ($paramBaseName === NULL) $paramBaseName = ':p';
		$quotes = static::prepareQuotes($quoteChars);
		$index = 0;
		foreach ($this->filtering as $columnName => $operatorAndRawValues) {
			$columnNameQuoted = static::quoteColumn($columnName, $quotes);
			foreach ($operatorAndRawValues as $operator => $rawValues) {
				$multipleValues = count($rawValues) > 1;
				$nullOperator = $operator === '=' ? 'IS' : 'IS NOT';
				if ($multipleValues) {
					$valuesContainsNull = FALSE;
					foreach ($rawValues as $rawValue) 
						if ($valuesContainsNull = (mb_strtolower($rawValue) === 'null')) 
							break;
					if (isset($inOperators[$operator]) && !$valuesContainsNull) {
						$inOperator = $inOperators[$operator];
						$paramsNames = [];
						foreach ($rawValues as $rawValue) {
							$paramName = "{$paramBaseName}{$index}";
							$params[$paramName] = $rawValue;
							$paramsNames[] = $paramName;
							$index++;
						}
						$paramsNamesStr = implode(", ", $paramsNames);
						$conditionSqlItems[] = "{$alias}{$columnNameQuoted} {$inOperator} ({$paramsNamesStr})";
					} else {
						$conditionSqlSubItems = [];
						foreach ($rawValues as $rawValue) {
							if (mb_strtolower($rawValue) === 'null') {
								$conditionSqlSubItems[] = "{$alias}{$columnNameQuoted} {$nullOperator} NULL";
							} else {
								$paramName = "{$paramBaseName}{$index}";
								$params[$paramName] = $rawValue;
								$conditionSqlSubItems[] = "{$alias}{$columnNameQuoted} {$operator} {$paramName}";
								$index++;
							}
						}
						$implodeOperator = $operator === 'LIKE' || $operator === '=' ? " OR " : " AND ";
						$conditionSqlItems[] = "(" . implode($implodeOperator, $conditionSqlSubItems) . ")";
					}
				} else {
					$rawValue = $rawValues[0];
					if (mb_strtolower($rawValue) === 'null') {
						$conditionSqlItems[] = "{$alias}{$columnNameQuoted} {$nullOperator} NULL";
					} else {
						$paramName = "{$paramBaseName}{$index}";
						$params[$paramName] = $rawValues[0];
						$conditionSqlItems[] = "{$alias}{$columnNameQuoted} {$operator} {$paramName}";
						$index++;
					}
				}
			}
		}
		$conditionsSql = '';
		if (count($conditionSqlItems) > 0) {
			$conditionsSql = (
				($includeWhere ? " WHERE " : "") . 
				implode(" AND ", $conditionSqlItems) . " "
			);
		}
		return [$conditionsSql, $params];
	}

	
	/**
	 * Prepare `string $quoteChars` param usually called from functions 
	 * `getSortingSql()` and from `getConditionSqlAndParams()`
	 * into `\string[] $quoteChars` param for function `quoteColumn()`.
	 * @param  string $quoteChars 
	 * @return string[]
	 */
	protected static function prepareQuotes ($quoteChars) {
		$quotes = ['',''];
		if ($quoteChars === NULL) 
			return $quotes;
		$quoteCharsLength = mb_strlen($quoteChars);
		if ($quoteCharsLength === 0) 
			return $quotes;
		$firstChar = mb_substr($quoteChars, 0, 1);
		return $quoteCharsLength === 1
			? [$firstChar, $firstChar]
			: [$firstChar, mb_substr($quoteChars, 1, 1)];
	}
	
	/**
	 * Quote column identifier by given quote chars,
	 * always caleld from functions `getSortingSql()` 
	 * and from `getConditionSqlAndParams()`.
	 * @param  string    $columnName 
	 * @param  \string[] $quoteChars 
	 * @return string
	 */
	protected static function quoteColumn ($columnName, $quoteChars = ['"', '"']) {
		return $quoteChars[0].$columnName.$quoteChars[1];
	}


	/**
	 * You have to implement this method usually by your own.
	 * This method is called automatically from local getters `GetTotalCount()` 
	 * and `GetPageData()` for first time. There is necesary to complete 
	 * local properties `$this->totalCount` and `$this->pageData`.
	 * @return void
	 */
	protected abstract function load ();
}
