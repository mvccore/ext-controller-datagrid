<?php

class Grid_UrlParser extends Grid_ChildsConstructor
{
	protected $parsedSectionStrings;

	public function getParsedSectionStrings ()
	{
		return $this->parsedSectionStrings;
	}

	public function parseRequestUrlToMainStorages ()
	{
		$routePattern = $this->routes[$this->grid->privateRouteName]['pattern'];
		$routeParamRaw = $this->request->getParam($this->routeParam);
		$routeParam = preg_replace($routePattern, "$1", $routeParamRaw);

		$routeParam = trim($routeParam, $this->urlDelimiters->sections);
		$sections = explode($this->urlDelimiters->sections, $routeParam);

		// complete section string with given order
		$this->parsedSectionStrings = new stdClass;
		$urlPrefixesKeys = array_keys((array) $this->urlPrefixes);
		foreach ($sections as $sectionIterator => $sectionString) {

			// try to determinate which prefix belongs to this section string
			for ($i = $sectionIterator, $l = count($urlPrefixesKeys); $i < $l; $i += 1) {

				$urlPrefixKey = $urlPrefixesKeys[$i];
				$urlPrefixValue = $this->urlPrefixes->$urlPrefixKey;
				$urlPrefixValue = (strlen($urlPrefixValue) > 0) ? $urlPrefixValue . $this->urlDelimiters->subjects : '' ;

				if (strlen($urlPrefixValue) == 0) {
					// url prefix is empty string, so continue with normal order
					break;
				} else {
					// prefix has some value, try to determinate, if this section belong to this prefix
					$prefixPos = strpos($sectionString, $urlPrefixValue);
					if ($prefixPos === 0) {
						$sectionString = substr($sectionString, strlen($urlPrefixValue));
						break;
					}
				}

			}

			$this->parsedSectionStrings->$urlPrefixKey = $sectionString;

		}

		// call all section methods
		for ($i = 0, $l = count($urlPrefixesKeys); $i < $l; $i += 1) {

			$urlPrefixKey = $urlPrefixesKeys[$i];
			if (!isset($this->parsedSectionStrings->$urlPrefixKey)) {
				$this->parsedSectionStrings->$urlPrefixKey = '';
			}
			$sectionString = $this->parsedSectionStrings->$urlPrefixKey;

			$setupMethodName = 'setup' . ucfirst($urlPrefixKey) . 'WithParsedSection';
			$this->$setupMethodName($sectionString);

		}

		return array(
			$this->page,
			$this->countPerPage,
			$this->order,
			$this->filter,
		);
	}
	
	protected function setupPageWithParsedSection ($sectionString = '')
	{
		preg_match("#[0-9]#", $sectionString, $sectionStringMatches);
		if ($sectionStringMatches) {
			$this->page = (int) preg_replace("#[^0-9]#", "$1", $sectionString);
		} else {
			$this->page = $this->pageDefault;
		}
	}

	protected function setupCountWithParsedSection ($sectionString = '')
	{
		preg_match("#[0-9]#", $sectionString, $sectionStringMatches);
		if ($sectionStringMatches) {

			$this->countPerPage = (int) preg_replace("#[^0-9]#", "$1", $sectionString);
			if ($this->countPerPage > $this->countPerPageMax && $this->countPerPageMax > 0) {
				$this->countPerPage = $this->countPerPageMax;
			}
			if ($this->countPerPage === 0 && $this->countPerPageMax > 0) {
				$this->countPerPage = $this->countPerPageDefault;
			}

		} else {
			$this->countPerPage = $this->countPerPageDefault;
		}
	}

	protected function setupOrderWithParsedSection ($sectionString = '')
	{
		if ($sectionString) {

			$explodedOrderPairs = explode($this->urlDelimiters->subjects, $sectionString);

			foreach ($explodedOrderPairs as $iterator => $orderKeyValueString) {
				$keyValueDelimiterPos = strpos($orderKeyValueString, $this->urlDelimiters->values);

				if ($keyValueDelimiterPos === FALSE) {
					$orderKey = $orderKeyValueString;
					$orderValue = $this->urlSufixes->orderAsc;
				} else {
					$orderKeyAndValue = explode($this->urlDelimiters->values, $orderKeyValueString);
					$orderKey = $orderKeyAndValue[0];
					$orderValue = $orderKeyAndValue[1];
				}

				$orderKey = $this->getPossibleUrlAliasKey($orderKey, $this->urlAliasKeys);

				// if parsed kay is allowed and parsed value is allowed
				if (isset($this->orderOptions[$orderKey])) {
					if ($orderValue == $this->urlSufixes->orderAsc) {
						$this->order[$orderKey] = 'ASC';
						$this->grid->modelOrder[$orderKey] = 'ASC';
					}
					if ($orderValue == $this->urlSufixes->orderDesc) {
						$this->order[$orderKey] = 'DESC';
						$this->grid->modelOrder[$orderKey] = 'DESC';
					}
				}
			}
		}
	}

	protected function setupFilterWithParsedSection ($sectionString = '')
	{
		if ($sectionString) {

			$explodedFilterPairs = explode($this->urlDelimiters->subjects, $sectionString);

			foreach ($explodedFilterPairs as $iterator => $filterKeyValuesString) {
				$keyValueDelimiterPos = strpos($filterKeyValuesString, $this->urlDelimiters->values);

				if ($keyValueDelimiterPos !== FALSE) {
					$filterKeyAndValues = explode($this->urlDelimiters->values, $filterKeyValuesString);
					$filterKey = $filterKeyAndValues[0];

					if ($this->urlDelimiters->values == $this->urlDelimiters->multipleValues) {
						// for cases, where key&value delimiter is the same as multiple values delimiter
						$filterValues = array();
						for ($i = 1, $l = count($filterKeyAndValues); $i < $l; $i += 1) {
							$filterValues[] = $filterKeyAndValues[$i];
						}

					} else {
						// for cases, where delimiters are diferent
						$filterMultipleValuesDelimiterPos = strpos($filterKeyAndValues[1], $this->urlDelimiters->multipleValues);
						if ($filterMultipleValuesDelimiterPos === FALSE) {
							$filterValues = array($filterKeyAndValues[1]);
						} else {
							$filterValues = explode($this->urlDelimiters->multipleValues, $filterKeyAndValues[1]);
						}

					}
				}

				$filterKey = $this->getPossibleUrlAliasKey($filterKey, $this->urlAliasKeys);

				// if parsed key is allowed and parsed values are allowed
				if (isset($this->filterOptions[$filterKey])) {
					// go throw all filter values and let there only allowed characters
					foreach ($filterValues as $iterator => $filterValue) {
						$valueTranslator = $this->filterOptions[$filterKey]['valueTranslator'];
						$filterValues[$iterator] = $valueTranslator($filterValues[$iterator], FALSE);
					}
					$this->filter[$filterKey] = $filterValues;
					$this->grid->modelFilter[$filterKey] = $filterValues;
				}
			}
		}
		
		// ksort filter array, wecause we need to compare whole arrays in url builder
		ksort($this->filter);

	}

}