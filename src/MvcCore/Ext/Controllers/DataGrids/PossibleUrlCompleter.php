<?php

class Grid_PossibleUrlCompleter extends Grid_ChildsConstructor
{
	protected $requestedUrlCollections = array();
	protected $collections;
	protected $databaseConnectionGetter;
	protected $databaseFilterCountsRequestTable;

	/* setters and initializators *********************************************/

	// use like this: $possibleUrlCompleter->setRequestedUrlCollections(array('order', 'count', 'page'));
	public function setRequestedUrlCollections()
	{
		$requestedCollectionNames = func_get_args();
		if (gettype($requestedCollectionNames[0]) == 'array') $requestedCollectionNames = $requestedCollectionNames[0];
		$uriCollectionsDefault = array(
			'filter'	=> 1,
			'order'		=> 1,
			'count'		=> 1,
			'page'		=> 1,
		);
		$uriCollections = array();
		if (is_array($requestedCollectionNames) && count($requestedCollectionNames) > 0) {
			foreach ($uriCollectionsDefault as $collectionName => $bool) {
				$uriCollections[$collectionName] = 0;
				if (in_array($collectionName, $requestedCollectionNames)) {
					$uriCollections[$collectionName] = 1;
				}
			}
		} else {
			$uriCollections = $uriCollectionsDefault;
		}
		$this->requestedUrlCollections = $uriCollections;
	}

	public function setDatabaseConnectionGetter ($dbConnectionGetter)
	{
		$this->databaseConnectionGetter = $dbConnectionGetter;
	}

	public function setDatabaseFilterCountsRequestTable($databaseFilterCountsRequestTable)
	{
		$this->databaseFilterCountsRequestTable = $databaseFilterCountsRequestTable;
	}

	protected function completeCountSqlSelect ($filterCondition)
	{
		$baseSqlStr = "SELECT COUNT(*) FROM `" . $this->databaseFilterCountsRequestTable . "`";
		if ($filterCondition) {
			return $baseSqlStr . " WHERE " . $filterCondition;
		} else {
			return $baseSqlStr;
		}
	}

	/* result completers ******************************************************/

	public function getResult()
	{
		$this->collections = new stdClass;
		if ($this->requestedUrlCollections['filter']) {
			$this->collections->filter	= $this->getAllFilterVariations();
		} else {
			if (is_null($this->grid->resultsListCount)) {
				$databaseConnectionClosureGetter = $this->databaseConnectionGetter;
				$sql = $this->completeCountSqlSelect($this->grid->modelConfig['condition']);
				$this->grid->resultsListCount = (int) $databaseConnectionClosureGetter()->fetchOne($sql);
			}
		}

		if ($this->requestedUrlCollections['order'])	$this->collections->order	= $this->getAllOrderPermutations();
		if ($this->requestedUrlCollections['count'])	$this->collections->count	= $this->getAllScales();
		if ($this->requestedUrlCollections['page'])		$this->collections->page	= $this->getAllPages($this->grid->resultsListCount, $this->grid->countPerPage);

		$this->urlBuilder = $this->grid->childs->urlBuilder;

		if (isset($this->collections->order) && $this->collections->order) {
			$this->collections->order = $this->completeAllOrderSectionStrings();
		}

		$result = array();
		if (isset($this->collections->filter) && $this->collections->filter) {
			if (!$this->databaseConnectionGetter) throw new Exception("To get all uri combination, you must set the database connection closure function, for example:\n \$possibleUrlCompleter->setDatabaseConnectionGetter(function(){ return Pimcore_Resource_Mysql::getConnection(); });");
			if (!$this->databaseFilterCountsRequestTable) throw new Exception("To get all uri combination, you must set the filter count request table, for example:\n \$possibleUrlCompleter->setDatabaseFilterCountsRequestTable('object_1');");
			$result = $this->completePossibleUrlFromFilterLevel();

		} elseif (isset($this->collections->order) && $this->collections->order) {
			$result = $this->completePossibleUrlFromOrderLevel($this->grid->resultsListCount);

		} elseif (isset($this->collections->count) && $this->collections->count) {
			$result = $this->completePossibleUrlFromCountLevel($this->grid->resultsListCount);

		} elseif (isset($this->collections->page) && $this->collections->page) {
			$result = $this->completePossibleUrlFromPageLevel($this->grid->resultsListCount);

		}

		return $result;
	}

	protected function completePossibleUrlFromFilterLevel ()
	{
		$result = array();

		$databaseConnectionClosureGetter = $this->databaseConnectionGetter;
		$modelCompleterClassName = $this->getChildInstanceName('ModelConfigCompleter');
		$modelConfigCompleter = new $modelCompleterClassName($this);

		foreach ($this->collections->filter as $filterValues) {

			$filterCondition = $modelConfigCompleter->completeSingleConditionSqlByFilter($filterValues);

			$sql = $this->completeCountSqlSelect($filterCondition);

			$count = (int) $databaseConnectionClosureGetter()->fetchOne($sql);

			if ($count > 0) {

				$filterSectionString = $this->urlBuilder->completeFilterSectionString($filterValues);

				$localResult = array();
				if (isset($this->collections->order) && $this->collections->order) {
					$localResult = $this->completePossibleUrlFromOrderLevel($count, $filterSectionString);
				} elseif (isset($this->collections->count) && $this->collections->count) {
					$localResult = $this->completePossibleUrlFromCountLevel($count, $filterSectionString);
				} else {
					$localResult = $this->completePossibleUrlFromPageLevel($count, $filterSectionString);
				}

				$result = array_merge($result, $localResult);

			}
		}

		return $result;
	}

	protected function completePossibleUrlFromOrderLevel ($resultsListCount, $filterSectionString = '')
	{
		$result = array();

		foreach ($this->collections->order as $orderSectionString) {

			$localResult = array();
			if (isset($this->collections->count) && $this->collections->count) {
				$localResult = $this->completePossibleUrlFromCountLevel($resultsListCount, $filterSectionString, $orderSectionString);
			} else {
				$localResult = $this->completePossibleUrlFromPageLevel($resultsListCount, $filterSectionString, $orderSectionString);
			}
			
			$result = array_merge($result, $localResult);

		}

		return $result;
	}

	protected function completePossibleUrlFromCountLevel ($resultsListCount, $filterSectionString = '', $orderSectionString = '')
	{
		$result = array();

		foreach ($this->collections->count as $countValue) {

			$countString = '';
			if ($countValue !== $this->grid->countPerPageDefault) {
				$countString = (string) $countValue;
			}

			$localResult = $this->completePossibleUrlFromPageLevel($resultsListCount, $filterSectionString, $orderSectionString, $countString);
			$result = array_merge($result, $localResult);

		}

		return $result;
	}

	protected function completePossibleUrlFromPageLevel ($resultsListCount, $filterSectionString = '', $orderSectionString = '', $countString = '')
	{
		$result = array();

		if (isset($this->collections->page) && $this->collections->page) {

			if ($countString === '') {
				$countInt = $this->grid->countPerPageDefault;
			} else {
				$countInt = (int) $countString;
			}
			if ($countInt === 0) {
				$pagesCount = 1;
			} else {
				$pagesCount = ceil($resultsListCount / $countInt);
			}

			for ($page = 1, $l = $pagesCount + 1; $page < $l; $page += 1) {

				$pageString = '';
				if ($page !== $this->grid->pageDefault) {
					$pageString = (string) $page;
				}
				$sectionParams = array(
					'filter'	=> $filterSectionString,
					'order'		=> $orderSectionString,
					'count'		=> $countString,
					'page'		=> $pageString,
				);

				$result[] = $this->urlBuilder->buildWholeUrlWithCustomSectionString($sectionParams);

			}
			
		} else {
			$sectionParams = array(
				'filter'	=> $filterSectionString,
				'order'		=> $orderSectionString,
				'count'		=> $countString,
				'page'		=> (string) $this->grid->pageDefault,
			);
			$result[] = $this->urlBuilder->buildWholeUrlWithCustomSectionString($sectionParams);
		}

		return $result;
	}

	protected function completeAllOrderSectionStrings ()
	{
		$ordersSections = array();
		foreach ($this->collections->order as $orderValues) {
			$orderSectionString = $this->urlBuilder->completeOrderSectionString($orderValues);
			if (!isset($ordersSections[$orderSectionString])) {
				$ordersSections[$orderSectionString] = 1;
			}
		}
		$ordersSections = array_keys($ordersSections);
		return $ordersSections;
	}

	/* variations and permutations completers *********************************/

	protected function getAllFilterVariations ()
	{
		$possibleFilters = array();
		foreach ($this->grid->filterOptions as $filterKey => $filterOptions) {
			if (isset($filterOptions['name'])) {
				foreach ($this->grid->modelFilter[$filterKey] as $value) {
					$possibleFilters[] = array($filterKey, $value);
				}
			}
		}
		$arrayVariationsClsName = $this->getChildInstanceName('Array_Variations');
		$rawFilterVariations = $arrayVariationsClsName::get($possibleFilters);
		$filterVariations = array();
		foreach ($rawFilterVariations as $filterVariationArrs) {
			$filterVariation = array();
			foreach ($filterVariationArrs as $filterVariationArr) {
				$filterKey = $filterVariationArr[0];
				$filterValue = $filterVariationArr[1];
				if (!isset($filterVariation[$filterKey])) {
					$filterVariation[$filterKey] = array($filterValue);
				} else {
					$filterVariation[$filterKey][] = $filterValue;
				}
			}
			$filterVariations[] = $filterVariation;
		}
		return $filterVariations;
	}

	protected function getAllOrderPermutations ()
	{
		$possibleOrders = array();
		foreach ($this->grid->orderOptions as $orderKey => $orderOptions) {
			if (isset($orderOptions['name'])) {
				$possibleOrders[] = array($orderKey, 'ASC');
				$possibleOrders[] = array($orderKey, 'DESC');
			}
		}
		if (count($possibleOrders) > 2) {
			$arrayPermutationsClsName = $this->getChildInstanceName('Array_Permutations');
			$rawOrderPermutations = $arrayPermutationsClsName::get($possibleOrders);
			$orderPermutations = array();
			foreach ($rawOrderPermutations as $orderPermutations) {
				$resultItem = array();
				foreach ($orderPermutations as $orderPermutation) {
					if (isset($resultItems[$orderPermutation[0]])) continue;
					$resultItem[$orderPermutation[0]] = $orderPermutation[1];
				}
				$result[] = $resultItem;
			}
		} else {
			$result = array(
				//array(),
				array($possibleOrders[0][0] => 'DESC'),
				array($possibleOrders[0][0] => 'ASC'),
			);
		}
		return $result;
	}

	protected function getAllScales ()
	{
		if (in_array($this->grid->countPerPageMax, $this->grid->countScale) && $this->grid->countPerPageMax === 0) {
			$result = $this->grid->countScale;
		} else if ($this->grid->countPerPageMax === 0) {
			$result = array_merge($this->grid->countScale, array($this->grid->countPerPageMax));
		} else {
			$result = $this->grid->countScale;
		}
		return $result;
	}

	protected function getAllPages ($listCount, $countPerPage)
	{
		$result = array();
		if ($countPerPage === 0) {
			$result = array(1);
		} else {
			for ($i = 1, $l = ceil($listCount / $countPerPage) + 1; $i < $l; $i += 1) {
				$result[] = $i;
			}
		}
		return $result;
	}

}

