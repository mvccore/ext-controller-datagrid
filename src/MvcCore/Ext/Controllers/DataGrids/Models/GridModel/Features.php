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

namespace MvcCore\Ext\Controllers\DataGrids\Models\GridModel;

/**
 * @mixin \MvcCore\Model|\MvcCore\Ext\Models\Db\Model
 */
trait Features {

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
	 * @return \MvcCore\Ext\Controllers\DataGrids\Models\TGridModel
	 */
	public function SetGrid (\MvcCore\Ext\Controllers\IDataGrid $grid = NULL) {
		$this->grid = $grid;
		return $this;
	}

	/**
	 * Set database table offset, always initialized into integer.
	 * This offset is always initialized by datagrid component automatically.
	 * @param  int|NULL $offset 
	 * @return \MvcCore\Ext\Controllers\DataGrids\Models\TGridModel
	 */
	public function SetOffset ($offset) {
		$this->offset = $offset;
		return $this;
	}

	/**
	 * Get database table offset, always initialized into integer.
	 * This offset is always initialized by datagrid component automatically.
	 * @return int|NULL
	 */
	public function GetOffset () {
		return $this->offset;
	}

	/**
	 * Set database table select limit, it could be initialized into integer or `NULL`.
	 * This limit is always initialized by datagrid component automatically.
	 * @param  int|NULL $limit 
	 * @return \MvcCore\Ext\Controllers\DataGrids\Models\TGridModel
	 */
	public function SetLimit ($limit) {
		$this->limit = $limit;
		return $this;
	}

	/**
	 * Get database table select limit, it could be initialized into integer or `NULL`.
	 * This limit is always initialized by datagrid component automatically.
	 * @return int|NULL
	 */
	public function GetLimit () {
		return $this->limit;
	}

	/**
	 * Set database table filtering, keys are database table column names
	 * and values are arrays. Each key in value array is condition 
	 * operator and values are raw user input values to use in column condition.
	 * This filtering is always initialized by datagrid component automatically.
	 * @param  array $filtering 
	 * @return \MvcCore\Ext\Controllers\DataGrids\Models\TGridModel
	 */
	public function SetFiltering (array $filtering) {
		$this->filtering = $filtering;
		return $this;
	}

	/**
	 * Get database table filtering, keys are database table column names
	 * and values are arrays. Each key in value array is condition 
	 * operator and values are raw user input values to use in column condition.
	 * This filtering is always initialized by datagrid component automatically.
	 * @return array
	 */
	public function GetFiltering () {
		return $this->filtering;
	}

	/**
	 * Set database table sorting, keys are database table column names 
	 * and values are sorting direction strings - `ASC | DESC`.
	 * This sorting is always initialized by datagrid component automatically.
	 * @param  array $sorting
	 * @return \MvcCore\Ext\Controllers\DataGrids\Models\TGridModel
	 */
	public function SetSorting (array $sorting) {
		$this->sorting = $sorting;
		return $this;
	}

	/**
	 * Get database table sorting, keys are database table column names 
	 * and values are sorting direction strings - `ASC | DESC`.
	 * This sorting is always initialized by datagrid component automatically.
	 * @return array
	 */
	public function GetSorting () {
		return $this->sorting;
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
	 * Complete ORDER BY condition SQL part by `$this->sorting` array given from datagrid.
	 * @param  bool        $includeOrderBy Include ` WHERE ` keyword.
	 * @param  string|NULL $columnsAlias   Optional SQL alias for each column.
	 * @param  string      $driverName     Driver specific name, optional.
	 * @return string
	 */
	protected function getSortingSql ($includeOrderBy = TRUE, $columnsAlias = NULL, $driverName = 'default') {
		$sortSqlItems = [];
		$alias = $columnsAlias === NULL ? '' : $columnsAlias . '.';
		$driverSpecifics = $this->getDriversSqlSpecs($driverName);
		foreach ($this->sorting as $columnName => $direction) {
			$columnNameQuoted = $this->quoteColumn($columnName, $driverSpecifics->quotes);
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
	 * @param  string      $driverName    Driver specific name, optional.
	 * @param  string      $paramBaseName Base name for every created param inside this method, optional.
	 * @return array [string $conditionsSql, array $params]
	 */
	protected function getConditionSqlAndParams ($includeWhere = TRUE, $columnsAlias = NULL, $params = [], $driverName = 'default', $paramBaseName = ':p') {
		static $inOperators = [
			'='		=> 'IN',
			'!='	=> 'NOT IN',
		];
		$allColumnsSqlItems = [];
		$alias = $columnsAlias === NULL ? '' : $columnsAlias . '.';
		if ($params === NULL) $params = [];
		if ($paramBaseName === NULL) $paramBaseName = ':p';
		$driverSpecifics = $this->getDriversSqlSpecs($driverName);
		$collateIsNotNull = $driverSpecifics->collate !== NULL;
		$grid = $this->grid;
		$nullStrVal = $grid::NULL_STRING_VALUE;
		$configColumns = $grid->GetConfigColumns(FALSE);
		$operatorMultiValues = " {$this->getConditionOperatorMultiValues()} ";
		$index = 0;
		foreach ($this->filtering as $columnName => $operatorAndRawValues) {
			$columnSqlItems = [];
			$columnNameQuoted = $this->quoteColumn($columnName, $driverSpecifics->quotes);
			$columnCfg = $configColumns->GetByDbColumnName($columnName, FALSE);
			$collateSqlStr = $collateIsNotNull && $columnCfg->GetIsString()
				? " COLLATE {$driverSpecifics->collate}"
				: "";
			foreach ($operatorAndRawValues as $operator => $rawValues) {
				$multipleValues = count($rawValues) > 1;
				$conditionLeftSide = $this->getSqlConditionLeftSide (
					$alias, $columnNameQuoted, $columnCfg, $driverSpecifics, $operator
				);
				if ($multipleValues) {
					list (
						$rawValuesContainsNull, $rawValuesIsNull
					) = $this->getRawValuesNullStates($rawValues, $nullStrVal);
					if (!$rawValuesContainsNull && isset($inOperators[$operator])) {
						$sqlOperator = $inOperators[$operator];
						$paramsNames = [];
						foreach ($rawValues as $rawValue) {
							$paramName = "{$paramBaseName}{$index}";
							$params[$paramName] = $rawValue;
							$paramsNames[] = $paramName;
							$index++;
						}
						$paramsNamesStr = implode(", ", $paramsNames);
						$columnSqlItems[] = "{$conditionLeftSide}{$collateSqlStr} {$sqlOperator} ({$paramsNamesStr})";
					} else {
						$conditionSqlSubItems = [];
						foreach ($rawValues as $rawValueIndex => $rawValue) {
							$isRawValueNull = $rawValuesIsNull[$rawValueIndex];
							$sqlOperator = $this->getSqlConditionOperator($isRawValueNull, $operator);
							$conditionRightSide = $this->getSqlConditionRightSide(
								$isRawValueNull, $paramBaseName, $index, 
								$params, $rawValue, $collateSqlStr,
								$columnCfg
							);
							$conditionSqlSubItems[] = "{$conditionLeftSide} {$sqlOperator} {$conditionRightSide}";
						}
						$implodeOperator = $operator === 'LIKE' || $operator === '=' ? " OR " : " AND ";
						$columnSqlItems[] = "(" . implode($implodeOperator, $conditionSqlSubItems) . ")";
					}
				} else {
					$isRawValueNull = mb_strtolower($rawValues[0]) === $nullStrVal;
					$sqlOperator = $this->getSqlConditionOperator($isRawValueNull, $operator);
					$conditionRightSide = $this->getSqlConditionRightSide(
						$isRawValueNull, $paramBaseName, $index, 
						$params, $rawValues[0], $collateSqlStr,
						$columnCfg
					);
					$columnSqlItems[] = "{$conditionLeftSide} {$sqlOperator} {$conditionRightSide}";
				}
			}
			if (count($columnSqlItems) > 0)
				$allColumnsSqlItems[] = "(" . implode($operatorMultiValues, $columnSqlItems) . ")";
		}
		$conditionsSql = '';
		if (count($allColumnsSqlItems) > 0) {
			$operatorMultiColumns = " {$this->getConditionOperatorMultiColumns()} ";
			$conditionsSql = (
				($includeWhere ? " WHERE " : "") . 
				implode($operatorMultiColumns, $allColumnsSqlItems) . " "
			);
		}
		return [$conditionsSql, $params];
	}

	/**
	 * Get array about raw values equal to `null`:
	 * - 0 - `TRUE` if any raw value is equal to `null`
	 * - 1 - `bool[]` array about each raw value if it is equal to `null`.
	 * @param  array $rawValues 
	 * @param  string $nullStrVal 
	 * @return array|[bool, bool[]]
	 */
	protected function getRawValuesNullStates (array $rawValues, $nullStrVal) {
		$rawValuesContainsNull = FALSE;
		$rawValuesIsNull = [];
		foreach ($rawValues as $rawIndex => $rawValue) {
			$rawValueIsNull = mb_strtolower($rawValue) === $nullStrVal;
			$rawValuesIsNull[$rawIndex] = $rawValueIsNull;
			if ($rawValueIsNull)
				$rawValuesContainsNull = TRUE;
		}
		return [$rawValuesContainsNull, $rawValuesIsNull];
	}

	/**
	 * Get SQL condition left side.
	 * @param  string                                            $alias 
	 * @param  string                                            $columnNameQuoted 
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\Column $columnCfg 
	 * @param  string                                            $dateFormatPattern 
	 * @param  \stdClass                                         $driverSpecifics 
	 * @param  string                                            $operator 
	 * @return array|string
	 */
	protected function getSqlConditionLeftSide (
		$alias, $columnNameQuoted, \MvcCore\Ext\Controllers\DataGrids\Configs\IColumn $columnCfg, \stdClass $driverSpecifics, $operator
	) {
		$conditionLeftSide = "{$alias}{$columnNameQuoted}";
		if (
			$columnCfg->GetIsDateTime() &&
			$driverSpecifics->likeDate !== NULL && 
			mb_strpos($operator, 'LIKE') !== FALSE
		) {
			$formatArgs = $columnCfg->GetFormatArgs() ?: [];
			if (count($formatArgs) > 1) {
				$dateFormatPattern = "'" . $formatArgs[1] . "'";
				$conditionLeftSide = str_replace(
					['<column>', '<pattern>'],
					[$conditionLeftSide, $dateFormatPattern],
					$driverSpecifics->likeDate
				);
			}
		}
		return $conditionLeftSide;
	}

	/**
	 * Get SQL condition center operator part 
	 * for standard cases (not for `IN` and `NOT IN`).
	 * @param  bool   $isRawValueNull 
	 * @param  string $operator 
	 * @return string
	 */
	protected function getSqlConditionOperator ($isRawValueNull, $operator) {
		return $isRawValueNull
			? $operator === '=' ? 'IS' : 'IS NOT'
			: $operator;
	}

	/**
	 * Get SQL condition right side part 
	 * for standard cases (not for `IN` and `NOT IN`).
	 * @param  bool                                               $isRawValueNull 
	 * @param  string                                             $paramBaseName 
	 * @param  int                                                $index 
	 * @param  array                                              $params 
	 * @param  mixed                                              $rawValue 
	 * @param  string                                             $collateSqlStr 
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\IColumn $columnCfg
	 * @return string
	 */
	protected function getSqlConditionRightSide (
		$isRawValueNull, $paramBaseName, & $index, 
		array & $params, $rawValue, $collateSqlStr, 
		\MvcCore\Ext\Controllers\DataGrids\Configs\IColumn $columnCfg
	) {
		if ($isRawValueNull) {
			return "NULL";
		} else {
			$paramName = "{$paramBaseName}{$index}";
			$index++;
			$params[$paramName] = $rawValue;
			return "{$paramName}{$collateSqlStr}";
		}
	}

	/**
	 * Quote column identifier by given quote chars,
	 * always caleld from functions `getSortingSql()` 
	 * and from `getConditionSqlAndParams()`.
	 * @param  string    $columnName 
	 * @param  \string[] $quoteChars 
	 * @return string
	 */
	protected function quoteColumn ($columnName, $quoteChars = ['"', '"']) {
		return $quoteChars[0].$columnName.$quoteChars[1];
	}
	
	/**
	 * Get driver SQL specifics as stdClass:
	 * - `quotes`   - column quotes
	 * - `likeDate` - date/time column converter for like condition if necessary.
	 * - `collate`	- different collation to ignore accents and case insensitive chars if necessary.
	 * @param  string $driver 
	 * @return \stdClass
	 */
	protected function getDriversSqlSpecs ($driver) {
		static $driversSpecifics = [
			'cubrid'	=> [
				'quotes'	=> ['"', '"'],
				'likeDate'	=> 'DATE_FORMAT(<column>, <pattern>)',
				'collate'	=> NULL,
			],
			'firebird'	=> [
				'quotes'	=> ['"', '"'],
				'likeDate'	=> NULL,
				'collate'	=> NULL,
			],
			'ibm'		=> [
				'quotes'	=> ['"', '"'],
				'likeDate'	=> 'VARCHAR_FORMAT(<column>, <pattern>)',
				'collate'	=> NULL,
			],
			'informix'	=> [
				'quotes'	=> ['"', '"'],
				'likeDate'	=> 'TO_CHAR(<column>, <pattern>)',
				'collate'	=> NULL,
			],
			'mysql'		=> [
				'quotes'	=> ['`', '`'],
				'likeDate'	=> '<column>',
				'collate'	=> NULL,
			],
			'sqlite'	=> [
				'quotes'	=> ['"', '"'],
				'likeDate'	=> '<column>',
				'collate'	=> NULL,
			],
			'pgsql'		=> [
				'quotes'	=> ['"', '"'],
				'likeDate'	=> 'TO_CHAR(<column>, <pattern>)',
				'collate'	=> NULL,
			],
			'sqlsrv'	=> [
				'quotes'	=> ['[', ']'],
				'likeDate'	=> 'FORMAT(<column>, <pattern>)',
				'collate'	=> 'Latin1_General_CI_AI',
			],
		];
		return isset($driversSpecifics[$driver])
			? (object) $driversSpecifics[$driver]
			: (object) [
				'quotes'	=> ['"', '"'], 
				'likeDate'	=> 'FORMAT(<column>, <pattern>)',
				'collate'	=> NULL,
			];
	}
	
	/**
	 * Get SQL operator for multiple columns, default is `AND`.
	 * @return string
	 */
	protected function getConditionOperatorMultiColumns () {
		return 'AND';
	}

	/**
	 * Get SQL operator for multiple values in single column, default is `OR`.
	 * @return string
	 */
	protected function getConditionOperatorMultiValues () {
		return 'OR';
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
