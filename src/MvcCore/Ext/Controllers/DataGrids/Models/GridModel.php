<?php

namespace MvcCore\Ext\Controllers\DataGrids\Models;

trait GridModel {

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
	public function SetFiltering ($filtering) {
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
	public function SetSorting ($sorting) {
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
	 * Render value in datagrid tabe cell as scalar value (convertable into string).
	 * @param  mixed                                             $row
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\Column $column 
	 * @param  \MvcCore\IView                                    $view
	 * @return string
	 */
	public function RenderCell ($row, \MvcCore\Ext\Controllers\DataGrids\Configs\IColumn $column, \MvcCore\IView $view) {
		$propName = $column->GetPropName();
		$value = $row->{'Get' . ucfirst($propName)}();
		if ($value !== NULL) {
			$viewHelper = $column->GetViewHelper();
			if ($viewHelper) {
				return call_user_func_array(
					[$view, $viewHelper], 
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
	 * Complete ORDER BY condition SQL part by `$this->sorting` array given from datagrid.
	 * @param  bool        $includeOrderBy Include ` WHERE ` keyword.
	 * @param  string|NULL $columnsAlias   Optional SQL alias for each column.
	 * @return string
	 */
	protected function getSortingSql ($includeOrderBy = TRUE, $columnsAlias = NULL) {
		$sortSqlItems = [];
		$alias = $columnsAlias === NULL
			? ''
			: '.' . $columnsAlias;
		foreach ($this->sorting as $columnName => $direction)
			$sortSqlItems[] = "{$alias}{$columnName} {$direction}";
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
	 * @return array [string $conditionsSql, array $params]
	 */
	protected function getConditionSqlAndParams ($includeWhere = TRUE, $columnsAlias = NULL, $params = [], $paramBaseName = ':p') {
		static $inOperators = [
			'='		=> 'IN',
			'!='	=> 'NOT IN',
		];
		$conditionSqlItems = [];
		$alias = $columnsAlias === NULL
			? ''
			: $columnsAlias . '.';
		$index = 0;
		foreach ($this->filtering as $columnName => $operatorAndRawValues) {
			foreach ($operatorAndRawValues as $operator => $rawValues) {
				$multipleValues = count($rawValues) > 1;
				if ($multipleValues) {
					if (isset($inOperators[$operator])) {
						$inOperator = $inOperators[$operator];
						$paramsNames = [];
						foreach ($rawValues as $rawValue) {
							$paramName = "{$paramBaseName}{$index}";
							$params[$paramName] = $rawValue;
							$paramsNames[] = $paramName;
							$index++;
						}
						$paramsNamesStr = implode(", ", $paramsNames);
						$conditionSqlItems[] = "{$alias}{$columnName} {$inOperator} ({$paramsNamesStr})";
					} else {
						$conditionSqlSubItems = [];
						foreach ($rawValues as $rawValue) {
							$paramName = "{$paramBaseName}{$index}";
							$params[$paramName] = $rawValue;
							$conditionSqlSubItems[] = "{$alias}{$columnName} {$operator} {$paramName}";
							$index++;
						}
						$conditionSqlItems[] = "(" . implode(" OR ", $conditionSqlSubItems) . ")";
					}
				} else {
					$paramName = "{$paramBaseName}{$index}";
					$params[$paramName] = $rawValues[0];
					$conditionSqlItems[] = "{$alias}{$columnName} {$operator} {$paramName}";
					$index++;
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
	 * You have to implement this method usually by your own.
	 * This method is called automatically from local getters `GetTotalCount()` 
	 * and `GetPageData()` for first time. There is necesary to complete 
	 * local properties `$this->totalCount` and `$this->pageData`.
	 * @return void
	 */
	protected abstract function load ();
}
