<?php

class Grid_UrlBuilder extends Grid_ChildsConstructor
{
	protected $pimcoreRoute = array();
	protected $pimcoreRouteFullReverse = '';
	protected $pimcoreRouteParamReplacer = '';

	protected $parsedSectionStrings;
	protected $defaultSectionStrings;
	protected $urlPrefixesKeys = array();

	public function setParsedSectionStrings ($parsedSectionStrings)
	{
		$this->parsedSectionStrings = $parsedSectionStrings;
		return $this;
	}

	public function initUrlBuilerProperties ()
	{
		// get pimcore route from database by name
		$lang = $this->request->getParam('lang');
		$routeName = $this->routeName;
		
		// complete grid url param replacer
		$this->pimcoreRouteParamReplacer = '{%' . $this->routeParam . '}';

		// prebuild url to full reverse with {%gridUrl} replacement
		$urlOptions = array('lang' => $lang);
		
		$urlOptions[$this->grid->routeParam] = $this->pimcoreRouteParamReplacer;
		$urlHelper = new Pimcore_View_Helper_Url;
		$this->pimcoreRouteFullReverse = $urlHelper->url(
			$urlOptions,
			$routeName
		);
		
		// complete full reverse with empty "grid_url"
		$this->pimcoreRouteFullReverseEmpty = str_replace($this->pimcoreRouteParamReplacer, '', $this->pimcoreRouteFullReverse);
		$this->pimcoreRouteFullReverseEmpty = preg_replace("#[/]{2}#", "/", $this->pimcoreRouteFullReverseEmpty);// remove double slashes
		
		// complete default section strings
		$this->defaultSectionStrings = new stdClass;
		$this->defaultSectionStrings->page		= (string) $this->pageDefault;
		$this->defaultSectionStrings->count		= (string) $this->countPerPageDefault;
		$this->defaultSectionStrings->order		= $this->completeOrderSectionString	($this->orderDefault);
		$this->defaultSectionStrings->filter	= $this->completeFilterSectionString($this->filterDefault);

		// precompute url prefixes keys array
		$this->urlPrefixesKeys = array_keys((array) $this->urlPrefixes);

		return $this;
	}

	/* url builders ************************************************************/

	public function getUrlLocal ($arguments)
	{
		$params = $arguments[0];
		$urlGetterMethodPrefix = ucfirst($arguments[1]);

		$urlGetterMethodName = 'get' . $urlGetterMethodPrefix . 'Url';

		return $this->$urlGetterMethodName($params);
	}

	public function buildWholeUrlWithCustomSectionString ($customedSectionStrings = array())
	{
		// complete all grid url sections together
		$gridUrlStr = '';
		$dutyToAddSection = FALSE;
		$sectionStringRaw = '';
		$sectionString = '';

		for ($i = count($this->urlPrefixesKeys) - 1; $i >= 0; $i -= 1) {

			$urlSectionKey = $this->urlPrefixesKeys[$i];
			$urlPrefix = $this->urlPrefixes->$urlSectionKey;

			if (isset($customedSectionStrings[$urlSectionKey])) {
				// prave jsem poslal ze skladani nějaké sekce nějakou hodnotu nebo ""
				if ($dutyToAddSection && $customedSectionStrings[$urlSectionKey] == '') {
					// pokud už mam povinnost přidavat a hodnota je "", pak bych mel sahnout po default
					$sectionStringRaw = $this->defaultSectionStrings->$urlSectionKey;
				} else {
					// pokud ale nemam povinnost přidavat
					if ($customedSectionStrings[$urlSectionKey] === $this->defaultSectionStrings->$urlSectionKey) {
						if ($dutyToAddSection) {
							// pokud ale už mam povinnost pridavat, hodnota by mela byt jako to, co je poslane jako parametr funkce
							$sectionStringRaw = $customedSectionStrings[$urlSectionKey];
						} else {
							// hodnota muže zustat prazdnym řetězcem, pokud je stejna jako defaultni hodnota sekce
							$sectionStringRaw = '';
						}
					} else {
						// nebo čimkoli jinym, co je poslane jako parametr teto funkce
						$sectionStringRaw = $customedSectionStrings[$urlSectionKey];
					}
				}
			} else {
				// prave kolem zavolane složene sekce listuju ostatni sekce - kde bych mel přidavat to co je vyparsovane
				if ($dutyToAddSection) {
					// pokud mam povinnost přidavat a vyparsovanej string byl "", pak sahnu po default stringu
					if ($this->parsedSectionStrings->$urlSectionKey == '') {
						$sectionStringRaw = $this->defaultSectionStrings->$urlSectionKey;
					} else {
						$sectionStringRaw = $this->parsedSectionStrings->$urlSectionKey;
					}
				} else {
					// pokud nemam povinnost - sahnu po tom co jsem vyparsoval, což muže byt klidne i "" nebo nejaka hodnota
					// srovnám nejdřív vyparsovanou hodnotu s tím co je defaultní a když je to stejný string, opět přičtu nic, jinak přičtu vyparsovanou hodnotu
					if ($this->parsedSectionStrings->$urlSectionKey !== $this->defaultSectionStrings->$urlSectionKey) {
						$sectionStringRaw = $this->parsedSectionStrings->$urlSectionKey;
					}
				}
			}

			if (strlen($sectionStringRaw) > 0) {
				$dutyToAddSection = TRUE;

				$sectionString = (($urlPrefix) ? $urlPrefix . $this->urlDelimiters->subjects : '' ) . $sectionStringRaw;
				$gridUrlStr = $sectionString . $this->urlDelimiters->sections . $gridUrlStr;
			}

		}

		// explode full reverse
		$explodedFullReverse = explode($this->pimcoreRouteParamReplacer, $this->pimcoreRouteFullReverse);

		if (substr($explodedFullReverse[0], strlen($explodedFullReverse[0]) - 1, 1) == $this->urlDelimiters->sections) {
			// pokud je posledni znak z předstringu před {%gridUrl} stringem lomitko
			if (substr($gridUrlStr, 0, 1) == $this->urlDelimiters->sections) {
				// a pokud prvni znak nahrazovane url je lomítko - oddelej ho a zabran dvema lomitkum v url
				$gridUrlStr = substr($gridUrlStr, 1);
			}
		}
		if (substr($explodedFullReverse[0], strlen($explodedFullReverse[0]) - 1, 1) != $this->urlDelimiters->sections) {
			// pokud neni posledni znak z předstringu před {%gridUrl} stringem lomitko
			if (substr($gridUrlStr, 0, 1) != $this->urlDelimiters->sections) {
				// a pokud prvni znak nahrazovane url neni lomítko - pridej ho a zabran zadnemu lomitku v url mezi dvema promenymi
				$gridUrlStr = '/' . $gridUrlStr;
			}
		}
		if (isset($explodedFullReverse[1]) && substr($explodedFullReverse[1], 0, 1) == $this->urlDelimiters->sections) {
			// pokud je prvni znak z postringu po {%gridUrl} stringu lomitko
			if (substr($gridUrlStr, strlen($gridUrlStr) - 1, 1) == $this->urlDelimiters->sections) {
				// a pokud posledni znak nahrazovane url je lomítko - oddelej ho a zabran dvema lomitkum v url
				$gridUrlStr = substr($gridUrlStr, 0, strlen($gridUrlStr) - 1);
			}
		}
		if (isset($explodedFullReverse[1]) && substr($explodedFullReverse[1], 0, 1) != $this->urlDelimiters->sections && strlen($explodedFullReverse[1]) > 1) {
			// pokud neni prvni znak z postringu po {%gridUrl} stringu lomitko a url string pak pokracuje dal
			if (substr($gridUrlStr, strlen($gridUrlStr) - 1, 1) != $this->urlDelimiters->sections) {
				// a pokud posledni znak nahrazovane url neni lomítko - pridej ho a zabran zadnym lomitkum v url mezi dvema promenymi
				$gridUrlStr = $gridUrlStr . '/';
			}
		}

		if ($gridUrlStr == '') {
			if (
				substr($explodedFullReverse[0], strlen($explodedFullReverse[0]) - 1, 1) == $this->urlDelimiters->sections &&
				substr($explodedFullReverse[1], 0, 1) == $this->urlDelimiters->sections
			) {
				// pokud se posledni znak z prvniho predstringu rovna lomitko a prvni znak z druheho predstringu taky, oddelej to zadni a spoj to
				$wholeUrl = $explodedFullReverse[0] . substr($explodedFullReverse[1], 1);
			} else {
				// pokud ne - spoj to a proste grid url pro takto nulovou adresu bude vypadat jako tam nic nebylo
				$wholeUrl = $explodedFullReverse[0] . $explodedFullReverse[1];
			}
		} else {
			// complete whole url
			$wholeUrl = str_replace(
				$this->pimcoreRouteParamReplacer,
				$gridUrlStr,
				$this->pimcoreRouteFullReverse
			);
		};
		
		// check if completed url is the same as static route witl all params empty
		if ($wholeUrl === $this->pimcoreRouteFullReverseEmpty) {
			$wholeUrl = $this->grid->baseUrl;
		}

		// return result
		return $wholeUrl;
	}

	/* forms url builders ******************************************************/

	protected function getFormActionUrl ($action = '')
	{
		$reverse = $this->routes[$action]['reverse'];
		$wholeRouteParamValue = $this->request->getParam($this->routeParam);

		if (!$wholeRouteParamValue) {
			$wholeRouteParamValue = '';
		}

		$sectionsString = str_replace('{%' . $this->privateRouteParam . '}', $wholeRouteParamValue, $reverse);

		$wholeUrl = str_replace(
			$this->pimcoreRouteParamReplacer,
			$sectionsString,
			$this->pimcoreRouteFullReverse
		);

		return $wholeUrl;
	}

	/* particular url builders *************************************************/

	protected function getPageUrl ($page = 0)
	{
		$orderUrlSectionStr = '';
		if ($page !== $this->pageDefault) {
			$orderUrlSectionStr = (string) $page;
		}
		// return result
		return $this->buildWholeUrlWithCustomSectionString(array(
			'page'	=> $orderUrlSectionStr
		));
	}

	protected function getCountUrl ($countPerPage = 0)
	{
		$orderUrlSectionStr = '';
		if ($countPerPage !== $this->countPerPageDefault) {
			$orderUrlSectionStr = (string) $countPerPage;
		}
		$customSectionParams = array(
			'count'	=> $orderUrlSectionStr
		);
		if ($countPerPage === 0) {
			// for all listed items - we neet a page number in "1" to not generate duplicity content
			$customSectionParams['page'] = 1;
		}
		// return result
		return $this->buildWholeUrlWithCustomSectionString($customSectionParams);
	}

	protected function getOrderUrl ($params = array())
	{
		// clone order array and prepend currently called property
		$orderLocal = $this->order;
		foreach ($params as $orderKey => $orderValue) {
			if (isset($orderLocal[$orderKey])) {
				unset($orderLocal[$orderKey]);
			}
			if (!$orderValue) {
				unset($params[$orderKey]);
			}
		}
		$orderLocal = array_merge($params, $orderLocal);

		// compare local order array and order default array, which doesn't need any information in url
		$orderUrlSectionStr = '';
		if ($orderLocal !== $this->orderDefault) {
			$orderUrlSectionStr = $this->completeOrderSectionString($orderLocal);
		}

		// return result
		return $this->buildWholeUrlWithCustomSectionString(array(
			'order'	=> $orderUrlSectionStr
		));
	}

	// used only in special cases - for example to generate link for very offten used option in filter form - $this->grid->getUrl(array('price' => array(100, 50000)), 'filter')
	protected function getFilterUrl ($params = array())
	{
		// clone order array and insert currently called property and values
		$filterLocal = $this->filter;
		foreach ($params as $filterKey => $filterValues) {
			$filterLocal[$filterKey] = $filterValues;
		}
		ksort($filterLocal);
		
		// compare local filter array and filter default array, which doesn't need any information in url
		$filterUrlSectionStr = '';
		if ($filterLocal !== $this->filterDefault) {
			$filterUrlSectionStr = $this->completeFilterSectionString($filterLocal);
		}

		// return result
		return $this->buildWholeUrlWithCustomSectionString(array(
			'filter'	=> $filterUrlSectionStr
		));
	}

	/* url section string completers *******************************************/

	public function completeOrderSectionString ($order = array())
	{
		$result = '';

		foreach ($order as $orderKey => $orderValue) {
			if (!isset($this->orderOptions[$orderKey]['name'])) continue;

			$orderKeyAliased = $this->getPossibleUrlAliasKey($orderKey, $this->urlAliasKeys, TRUE);
			$orderUrlValue = ($orderValue == 'ASC') ? $this->urlSufixes->orderAsc : $this->urlSufixes->orderDesc ;

			$result .= (($result) ? $this->urlDelimiters->subjects : '' )
				. $orderKeyAliased . $this->urlDelimiters->values . $orderUrlValue
			;
		}

		return $result;
	}

	public function completeFilterSectionString ($filter = array())
	{
		$result = '';

		foreach ($filter as $filterKey => $filterValues) {
			if (!isset($this->filterOptions[$filterKey]['name'])) continue;

			$filterKeyAliased = $this->getPossibleUrlAliasKey($filterKey, $this->urlAliasKeys, TRUE);
			$translatedValues = array();
			$translatingFunction = $this->filterOptions[$filterKey]['valueTranslator'];
			foreach ($filterValues as $filterValue) {
				$translatedValues[] = $translatingFunction($filterValue, TRUE);
			}
			$filterUrlValues = implode($this->urlDelimiters->multipleValues, $translatedValues);

			$result .= (($result) ? $this->urlDelimiters->subjects : '' )
				. $filterKeyAliased . $this->urlDelimiters->values . $filterUrlValues
			;
		}

		return $result;
	}

}

