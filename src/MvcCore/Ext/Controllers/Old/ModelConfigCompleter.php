<?php

class Grid_ModelConfigCompleter extends Grid_ChildsConstructor
{
	
	public function completeAll ()
	{
		// complete limit and offset
		$modelConfig = array(
			'limit'		=> $this->countPerPage,
			'offset'	=> $this->countPerPage * ($this->page - 1),
		);
		// do not set any limit and offset if we want to list everything
		if ($this->countPerPage === 0) $modelConfig = array();

		// add default values where necessary to complete sql right
		$this->addDefaultValuesToOrder();
		$this->addDefaultValuesToFilter();

		// complete order to tricky form - pimcore list config order key and order together
		if (count($this->order) == 1) {
			foreach ($this->order as $key => $value) {
				$modelConfig['orderKey'] = $key;
				$modelConfig['order'] = $value;
			}
		} else {
			$regularOrderStr = $this->completeOrders();
			$lastSpacePos = strrpos($regularOrderStr, ' ');
			$orderKey = substr($regularOrderStr, 0, $lastSpacePos - 1);
			if (substr($orderKey, 0, 1) === '`') $orderKey = substr($orderKey, 1);
			if (substr($orderKey,strlen($orderKey) - 1, 1) === '`') $orderKey = substr($orderKey, 0, strlen($orderKey) - 1);
			$orderValue = substr($regularOrderStr, $lastSpacePos + 1);
			$modelConfig['orderKey'] = $orderKey;
			$modelConfig['order'] = $orderValue;
		}

		// complete filter to pimcore list config condition
		$modelConfig['condition'] = $this->completeConditions();

		$this->grid->modelConfig = $modelConfig;
	}

	public function completeSingleConditionSqlByFilter ($filter)
	{
		$this->filter = $filter;
		return $this->completeConditions();
	}

	protected function addDefaultValuesToOrder ()
	{
		foreach ($this->orderDefault as $orderKey => $orderDefaultValue) {
			if (!isset($this->order[$orderKey])) {
				$this->order[$orderKey] = $orderDefaultValue;
				$this->grid->modelOrder[$orderKey] = $orderDefaultValue;
			}
		}
	}

	protected function addDefaultValuesToFilter ()
	{
		// complete missing filter values from default filter settings (not all we want to display to customer/visitor)
		foreach ($this->filterDefault as $filterKey => $filterDefaultValues) {
			if (!isset($this->filter[$filterKey])) {
				$this->filter[$filterKey] = $filterDefaultValues;
				$this->grid->modelFilter[$filterKey] = $filterDefaultValues;
			}
		}
	}

	protected function completeOrders ()
	{
		$result = '';

		$assignedKeys = array();
		$ordersArr = array();

		foreach ($this->orderOptions as $orderKey => $orderValue) {
			if (isset($orderValue['fixedIndex'])) {
				$fixedIndex = $orderValue['fixedIndex'];
				if (isset($orderValue['customSql'])) {
					$orderSql = str_replace(
						array('%key', '%value',),
						array($orderKey, $orderValue['value'],),
						$orderValue['customSql']
					);
				} else {
					$orderSql =  "`" . $orderKey . "` " . $orderValue['value'];
				}
				$ordersArr[$fixedIndex] = $orderSql;
				$assignedKeys[] = $orderKey;
			}
		}

		$index = 0;
		foreach ($this->order as $orderKey => $orderValue) {
			if (in_array($orderKey, $assignedKeys)) continue;
			$originOptions = $this->orderOptions[$orderKey];
			if (isset($originOptions['customSql'])) {
				$orderSql = str_replace(
					array('%key', '%value',),
					array($orderKey, $orderValue,),
					$originOptions['customSql']
				);
			} else {
				$orderSql =  "`" . $orderKey . "` " . $orderValue;
			}
			while (isset($ordersArr[$index])) {
				$index += 1;
			}
			$ordersArr[$index] = $orderSql;
		}

		$result = implode(", ", $ordersArr);

		return $result;
	}

	protected function completeConditions ()
	{
		$result = '';

		$conditions = array();
		foreach ($this->filter as $filterKey => $filterValues) {

			$conditionArr = array();
			$keyValueCarouselIterator = 0;
			$filterOptions = $this->filterOptions[$filterKey];

			foreach ($filterValues as $valueIterator => $value) {
				
				if (isset($filterOptions['logicOperators']['customSql'])) {

					$customSqlObject = $filterOptions['logicOperators']['customSql'];
					if (gettype($customSqlObject) == 'string') {
						$conditionArr[] = str_replace(
							array('%key', '%value'),
							array($filterKey, $value),
							$customSqlObject
						);
					} elseif (get_class($customSqlObject) == 'Closure') {
						$conditionArr[] = $customSqlObject($filterKey, $value);
					}

				} else {

					$keyStr = "`" . $filterKey . "`";

					preg_match("#[^0-9]#", $value, $stringTypeMatch);
					$valueStr = (count($stringTypeMatch) > 0) ? "'" . $value . "'" : $value ;

					// be carefull for logical error queries like this WHERE `something`<='' ...
					$currentLogicalKeyValueOperator = $filterOptions['logicOperators']['keyValue'][$keyValueCarouselIterator];
					if (!($value == '' && (strpos($currentLogicalKeyValueOperator, '<') !== FALSE || strpos($currentLogicalKeyValueOperator, '>') !== FALSE))) {
						$conditionArr[] = $keyStr . $currentLogicalKeyValueOperator . $valueStr;
					}
				}

				if (count($filterOptions['logicOperators']['keyValue']) > 1) {
					$keyValueCarouselIterator += 1;
					if ($keyValueCarouselIterator > count($filterOptions['logicOperators']['keyValue'])) {
						$keyValueCarouselIterator = 0;
					}
				}

			}

			$conditionStr = implode(" " . $filterOptions['logicOperators']['multipleValues'] . " ", $conditionArr);

			if (count($filterValues) > 1) {
				$conditionStr = "(" . $conditionStr . ")";
			}

			$conditions[] = $conditionStr;

		}

		$result = implode(" " . $this->defaultLogicOperators->multipleSubjects . " ", $conditions);

		return $result;
	}

}

