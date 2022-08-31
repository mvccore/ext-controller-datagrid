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
trait InitMethods {

	/**
	 * Create `\MvcCore\Ext\Controllers\DataGrid` instance.
	 * @param  \MvcCore\Controller|NULL $controller
	 * @param  string|int|NULL          $childControllerIndex Automatic name for this instance used in view.
	 * @return void
	 */
	public function __construct (\MvcCore\IController $controller = NULL, $childControllerIndex = NULL) {
		/** @var \MvcCore\Controller $controller */
		if (is_string($this->countScales)) 
			$this->countScales = array_map('intval', explode(',', (string) $this->countScales));
		if ($controller === NULL) {
			$controller = \MvcCore\Ext\Form::GetCallerControllerInstance();
			if ($controller === NULL) 
				$controller = \MvcCore\Application::GetInstance()->GetController();
			if ($controller === NULL) throw new \InvalidArgumentException(
				'['.get_class($this).'] There was not possible to determinate caller controller, '
				.'where is datagrid instance created. Provide `$controller` instance explicitly '
				.'by first `\MvcCore\Ext\Controllers\DataGrid::__construct($controller);` argument.'
			);
		}
		$controller->AddChildController($this, $childControllerIndex);
	}

	/**
	 * @inheritDocs
	 * @return void
	 */
	public function Init () {
		if ($this->dispatchState > \MvcCore\IController::DISPATCH_STATE_CREATED) return;
		
		$this->GetConfigRendering();
		if ($this->configRendering->GetRenderFilterForm()) {
			if ($this->controlFilterForm === NULL)
				throw new \InvalidArgumentException(
					"With enabled custom filter form rendering control, ".
					"you have to set custom form instance by method `SetControlFilterForm()` ".
					"implementing `\MvcCore\Ext\Controllers\DataGrids\Forms\IFilterForm`."
				);
			$controlFilterFormChildIndex = array_search($this->controlFilterForm, $this->childControllers, TRUE);
			if ($controlFilterFormChildIndex !== FALSE)
				unset($this->childControllers[$controlFilterFormChildIndex]);
		}

		parent::Init();
		
		$this->GetConfigUrlSegments();
		$this->initTranslations();
		$this->GetConfigColumns(FALSE);

		$this->initGridAction();

		$this->GetRoute();
		$this->GetUrlParams();
		
		$this->initQsParamsSeparator();
		$this->initCountScales();

		if (!$this->initUrlParams()) return; // redirect inside
		$this->initOffsetLimit();
		
		if (!$this->initUrlBuilding()) return; // redirect inside
		$this->initOperators();
		
		$this->initSorting();
		if (!$this->initFiltering()) return; // redirect inside
		
		call_user_func([$this, $this->gridAction]);
	}
	
	/**
	 * Initialize necessary properties for application URL building.
	 * Aplication URL building is always initialized in controllers,
	 * where grid doesn't exist and where is necessary to create 
	 * URL to controller containing datagrid with sorting or filtering.
	 * @return void
	 */
	protected function initAppUrlCompletion () {
		$this->GetConfigUrlSegments();
		$this->initTranslations();
		$this->GetConfigColumns(FALSE);
		$this->GetGridRequest();
		$this->initQsParamsSeparator();
		$this->initCountScales();
		$this->GetUrlParams();
		$this->initOperators();
		$this->appUrlCompletionInit = TRUE;
	}

	/**
	 * Complete internal action method name.
	 * @return void
	 */
	protected function initGridAction () {
		$gridActionParam = $this->request->GetParam(static::URL_PARAM_ACTION, '-_a-zA-Z', static::$gridActionDefaultKey, 'string');
		if (!isset(static::$gridActions[$gridActionParam])) $gridActionParam = static::$gridActionDefaultKey;
		$this->gridAction = static::$gridActions[$gridActionParam];
	}

	/**
	 * Init `$this->queryStringParamsSepatator` from router to build grid urls:
	 * @return void
	 */
	protected function initQsParamsSeparator () {
		if ($this->queryStringParamsSepatator === NULL) {
			$routerType = new \ReflectionClass($this->router);
			$method = $routerType->getMethod('getQueryStringParamsSepatator');
			$method->setAccessible(TRUE);
			$this->queryStringParamsSepatator = $method->invoke($this->router);
		}
	}
	
	/**
	 * Set up items per page configured from script 
	 * into count scales if it is not there.
	 * @return void
	 */
	protected function initCountScales () {
		if (in_array($this->itemsPerPage, $this->countScales, TRUE)) return;
		$this->countScales[] = $this->itemsPerPage;
		sort($this->countScales);
		if ($this->countScales[0] === 0) {
			array_shift($this->countScales);
			$this->countScales[] = 0;
		}
	}

	/**
	 * Initialize internal property `$this->queryStringParamsSepatator`
	 * to be able to build internal grid URL strings.
	 * Check valid values from URL for page and items par page.
	 * If some value is invalid, redirect to default value.
	 * @return bool
	 */
	protected function initUrlParams () {
		if (!$this->initUrlParamCount())		return FALSE;
		if (!$this->initUrlParamPage())			return FALSE;
		if (!$this->initUrlParamPageByCount())	return FALSE;
		return TRUE;
	}

	/**
	 * Set up default count if null or 
	 * check if count has allowed size.
	 * @return bool
	 */
	protected function initUrlParamCount () {
		if (!isset($this->urlParams[static::URL_PARAM_COUNT])) {
			// if there is no count param in url - use items per page as default count
			$this->count = $this->GetItemsPerPage();
		} else {
			// verify if count is not too high, if it is - redirect to highest count in count scales:
			$this->count = intval($this->urlParams[static::URL_PARAM_COUNT]);
			$lastCountsScale = $this->countScales[count($this->countScales) - 1];
			if ($lastCountsScale !== 0 && ($this->count === 0 || $this->count > $lastCountsScale)) {
				// redirect to allowed max count:
				$redirectUrl = $this->GridUrl([
					static::URL_PARAM_PAGE	=> $this->urlParams[static::URL_PARAM_PAGE],
					static::URL_PARAM_COUNT	=> $lastCountsScale,
				]);
				/** @var \MvcCore\Controller $this */
				$this::Redirect(
					$redirectUrl, 
					\MvcCore\IResponse::SEE_OTHER, 
					'Grid count is too high.'
				);
				return FALSE;
			}
		}
		if (
			!$this->allowedCustomUrlCountScale &&
			!in_array($this->count, $this->countScales, TRUE)
		) {
			// if there is not allowed custom count scale - choose closest value and redirect
			$redirectUrl = $this->GridUrl([
				static::URL_PARAM_COUNT	=> $this->getClosestCountScale($this->count),
			]);
			/** @var \MvcCore\Controller $this */
			$this::Redirect(
				$redirectUrl, 
				\MvcCore\IResponse::SEE_OTHER, 
				'Grid custom count scale is not allowed.'
			);
			return FALSE;
		}
		return TRUE;
	}
	
	/**
	 * Set up default page if NULL or redirect if page is zero.
	 * @return bool
	 */
	protected function initUrlParamPage () {
		if (!isset($this->urlParams[static::URL_PARAM_PAGE])) {
			$this->page = 1;
		} else {
			if ($this->urlParams[static::URL_PARAM_PAGE] === 0) {
				// redirect to proper page number:
				$redirectUrl = $this->GridUrl([
					static::URL_PARAM_PAGE	=> 1,
				]);
				/** @var \MvcCore\Controller $this */
				$this::Redirect(
					$redirectUrl, 
					\MvcCore\IResponse::SEE_OTHER, 
					'Grid page is too low.'
				);
				return FALSE;
			}
			$this->page = $this->urlParams[static::URL_PARAM_PAGE];
		}
		return TRUE;
	}

	/**
	 * Check if page is not larger than 1 if count is unlimited.
	 * @return bool
	 */
	protected function initUrlParamPageByCount () {
		if ($this->count === 0 && $this->page > 1) {
			$redirectUrl = $this->GridUrl([
				static::URL_PARAM_PAGE	=> 1,
			]);
			/** @var \MvcCore\Controller $this */
			$this::Redirect(
				$redirectUrl, 
				\MvcCore\IResponse::SEE_OTHER, 
				'Grid page is too high with unlimited count.'
			);
			return FALSE;
		}
		return TRUE;
	}
	
	/**
	 * Set up offset and limit properties for datagrid model instance.
	 * Offset is always presented, limit could be `NULL` or integer.
	 * @return void
	 */
	protected function initOffsetLimit () {
		$unlimitedCount = $this->count === 0;
		$this->limit = $unlimitedCount
			? NULL
			:  $this->count;
		if ($unlimitedCount) {
			$this->offset = 0;
		} else {
			$page = $this->urlParams[static::URL_PARAM_PAGE];
			$this->offset = ($page - 1) * $this->limit;
		}
	}
	
	/**
	 * Process canonical redirect if necessary and remove default 
	 * values from route to be able to build grid urls.
	 * @return bool
	 */
	protected function initUrlBuilding () {
		$defaultAction = $this->gridAction === static::$gridActions[static::$gridActionDefaultKey];
		$processCanonicalRedirect = $defaultAction && $this->router->GetAutoCanonizeRequests();
		
		if ($processCanonicalRedirect && !$this->initUrlBuildingCanonicalRedirect()) 
			return FALSE;

		// remove all default values from route to build urls with `$this->urlParams` only:
		$this->route->SetDefaults([]);
		
		return TRUE;
	}

	/**
	 * TODO: PHPDocs
	 * @return bool
	 */
	protected function initUrlBuildingCanonicalRedirect () {
		// remove all default values from route to build urls with `$this->urlParams` only:
		$defaultParams = [];
		$pageIsDefault = $this->page === 1;
		$countIsDefault = $this->count === $this->itemsPerPage;
		if ($pageIsDefault && $countIsDefault) {
			$defaultParams = [static::URL_PARAM_PAGE => 1,		static::URL_PARAM_COUNT => $this->itemsPerPage];
		} else if ($pageIsDefault && !$countIsDefault) {
			$defaultParams = [static::URL_PARAM_PAGE => NULL,	static::URL_PARAM_COUNT => NULL];
		} else if (!$pageIsDefault && $countIsDefault) {
			$defaultParams = [static::URL_PARAM_PAGE => NULL,	static::URL_PARAM_COUNT => $this->itemsPerPage];
		} else {
			$defaultParams = [static::URL_PARAM_PAGE => NULL,	static::URL_PARAM_COUNT => NULL];
		}
		$this->route->SetDefaults($defaultParams);
		
		// redirect to canonical url if necessary:
		list ($gridParam) = $this->route->Url(
			$this->gridRequest,
			[],
			$this->urlParams,
			$this->queryStringParamsSepatator,
			FALSE
		);
		$gridParam = rtrim(rawurldecode($gridParam), '/');
		$reqPathRaw = rtrim($this->gridRequest->GetPath(TRUE), '/');

		$redirectUrl = NULL;
		if (
			$this->request->HasParam(static::URL_PARAM_GRID) &&
			$gridParam !== '' && 
			$reqPathRaw !== '' && 
			$gridParam !== $reqPathRaw
		) {
			$redirectUrl = parent::Url($this->appRouteName, [
				static::URL_PARAM_GRID => $gridParam
			]);
		}
		if ($redirectUrl !== NULL) {
			/** @var \MvcCore\Controller $this */
			$this::Redirect(
				$redirectUrl, 
				\MvcCore\IResponse::SEE_OTHER, 
				'Grid canonical URL redirect.'
			);
			return FALSE;
		}
		return TRUE;
	}
	
	/**
	 * Initialize translation booleans and translate url names 
	 * and URL operators if necessary. Initialize allowed default 
	 * filter URL operators collection to check filter values.
	 * @return void
	 */
	protected function initTranslations () {
		// complete transaction booleans and translate filter url segments if necessary:
		if ($this->translate !== NULL) return;
		$this->translate = is_callable($this->translator) || $this->translator instanceof \Closure;
		if (!$this->translate) 
			$this->translateUrlNames = FALSE;
		if ($this->translateUrlNames) {
			$translatedUrlFilterOperators = [];
			foreach ($this->configUrlSegments->GetUrlFilterOperators() as $operator => $urlSegment) {
				$translatedUrlFilterOperators[$operator] = call_user_func_array(
					$this->translator, [$urlSegment]
				);
			}
			$this->configUrlSegments->SetUrlFilterOperators($translatedUrlFilterOperators);
		}
	}
	
	/**
	 * Initialize default allowed operators collection.
	 * @return void
	 */
	protected function initOperators () {
		if (!$this->filteringMode) return;
		foreach ($this->configColumns as $urlName => $columnConfig) {
			$columnFilterCfg = $columnConfig->GetFilter();
			if (is_integer($columnFilterCfg) && $columnFilterCfg !== 0) 
				$this->columnsAllowedOperators[$columnConfig->GetPropName()] = $this->getAllowedOperators($columnFilterCfg);
		}
		$this->defaultAllowedOperators = $this->getAllowedOperators($this->filteringMode);
	}

	/**
	 * Parse sorting from URL as array of databse column names as keys 
	 * and sorting directions `ASC | DESC` as values.
	 * @return void
	 */
	protected function initSorting () {
		if (!$this->sortingMode || !$this->urlParams[static::URL_PARAM_SORT]) return;
		$rawSorting = trim($this->urlParams[static::URL_PARAM_SORT]);
		if (mb_strlen($rawSorting) === 0) return;
		$subjsDelim = $this->configUrlSegments->GetUrlDelimiterSubjects();
		$subjValueDelim = $this->configUrlSegments->GetUrlDelimiterSubjectValue();
		$ascSuffix = $this->configUrlSegments->GetUrlSuffixSortAsc();
		$descSuffix = $this->configUrlSegments->GetUrlSuffixSortDesc();
		$sortSuffixes = [$ascSuffix => 'ASC', $descSuffix => 'DESC'];
		$rawSortingItems = explode($subjsDelim, $rawSorting);
		$multiSorting = ($this->sortingMode & static::SORT_MULTIPLE_COLUMNS) != 0;
		$sorting = [];
		foreach ($rawSortingItems as $rawSortingItem) {
			$delimPos = mb_strpos($rawSortingItem, $subjValueDelim);
			$direction = 'ASC';
			if ($delimPos === FALSE) {
				$rawColumnName = $rawSortingItem;
			} else {
				$rawColumnName = mb_substr($rawSortingItem, 0, $delimPos);
				$rawDirection = mb_substr($rawSortingItem, $delimPos + 1);
				if (isset($sortSuffixes[$rawDirection])) 
					$direction = $sortSuffixes[$rawDirection];
			}
			$rawColumnName = $this->removeUnknownChars($rawColumnName);
			if ($rawColumnName === NULL) continue;
			if (!isset($this->configColumns[$rawColumnName])) continue;
			$configColumn = $this->configColumns[$rawColumnName];
			if (!$this->ignoreDisabledColumns && $configColumn->GetDisabled()) continue;
			$columnSortCfg = $configColumn->GetSort();
			if ($columnSortCfg === FALSE || $columnSortCfg === NULL) continue;
			$sorting[$configColumn->GetDbColumnName()] = $direction;
			if (!$multiSorting) break;
		}
		if (count($sorting) === 0) {
			foreach ($this->configColumns as $configColumn) {
				$configColumnSort = $configColumn->GetSort();
				if (is_string($configColumnSort)) {
					$dbColumnName = $configColumn->GetDbColumnName();
					$sorting[$dbColumnName] = $configColumnSort;
					if (!$multiSorting) break;
				}
			}
		}
		$this->sorting = $sorting;
	}
	
	/**
	 * Parse filtering from URL as array of databse column names as keys 
	 * and values as array of raw filtering values.
	 * @return bool
	 */
	protected function initFiltering () {
		if (!$this->filteringMode || !isset($this->urlParams[static::URL_PARAM_FILTER])) return TRUE;
		$rawFiltering = trim($this->urlParams[static::URL_PARAM_FILTER]);
		if (mb_strlen($rawFiltering) === 0) return TRUE;
		$subjsDelim = $this->configUrlSegments->GetUrlDelimiterSubjects();
		$subjValueDelim = $this->configUrlSegments->GetUrlDelimiterSubjectValue();
		$valuesDelim = $this->configUrlSegments->GetUrlDelimiterValues();
		$urlFilterOperators = $this->configUrlSegments->GetUrlFilterOperators();
		$filteringColumns = $this->getFilteringColumns();
		$rawFilteringItems = explode($subjsDelim, $rawFiltering);
		$multiFiltering = ($this->filteringMode & static::FILTER_MULTIPLE_COLUMNS) != 0;
		$filtering = [];
		$invalidFilterValue = FALSE;
		foreach ($rawFilteringItems as $rawFilteringItem) {
			// parse column, operator and values
			$delimPos = mb_strpos($rawFilteringItem, $subjValueDelim);
			$multiple = TRUE;
			$rawOperatorStr = $urlFilterOperators['='];
			$values = NULL;
			$regex = NULL;
			if ($delimPos === FALSE) {
				$rawColumnName = $rawFilteringItem;
				$values = ['1']; // boolean 1 as default value if no operator and value defined
			} else {
				$rawColumnName = mb_substr($rawFilteringItem, 0, $delimPos);
				$rawOperatorAndValuesStr = mb_substr($rawFilteringItem, $delimPos + 1);
				$delimPos = mb_strpos($rawOperatorAndValuesStr, $subjValueDelim);
				if ($delimPos === FALSE) {
					$rawValuesStr = $rawOperatorAndValuesStr;
				} else {
					$rawOperatorStr = mb_substr($rawOperatorAndValuesStr, 0, $delimPos);
					$rawValuesStr = mb_substr($rawOperatorAndValuesStr, $delimPos + 1);
				}
			}
			$rawColumnName = $this->removeUnknownChars($rawColumnName);
			// check if column exists
			if ($rawColumnName === NULL || !isset($this->configColumns[$rawColumnName])) continue;
			$configColumn = $this->configColumns[$rawColumnName];
			$columnPropName = $configColumn->GetPropName();
			$columnTypes = $configColumn->GetTypes();
			// check if column support filtering
			if (!isset($filteringColumns[$columnPropName])) continue;
			$columnFilterCfg = $configColumn->GetFilter();
			// check if column has allowed parsed operator
			$allowedOperators = is_integer($columnFilterCfg)
				? $this->columnsAllowedOperators[$columnPropName]
				: $this->defaultAllowedOperators;
			if (!isset($allowedOperators[$rawOperatorStr])) continue;
			// check parsed values
			if ($values === NULL) {
				$values = [];
				$rawValuesArr = explode($valuesDelim, $rawValuesStr);
				$operatorCfg = $allowedOperators[$rawOperatorStr];
				$operator = $operatorCfg->operator;
				$multiple = $operatorCfg->multiple;
				$regex = $operatorCfg->regex;
				if (!$multiple && count($rawValuesArr) > 1)
					$rawValuesArr = [$rawValuesArr[0]];
				$columnFilter = $configColumn->GetFilter();
				$columnAllowNullFilter = (
					is_int($columnFilter) && ($columnFilter & self::FILTER_ALLOW_NULL) != 0
				);
				$viewHelperName = $configColumn->GetViewHelper();
				list ($useViewHelper, $viewHelper) = $this->getFilteringViewHelper($viewHelperName);
				foreach ($rawValuesArr as $rawValue) {
					$rawValue = $this->removeUnknownChars($rawValue);
					if ($rawValue === NULL) continue;
					if ($useViewHelper) {
						$rawValue = call_user_func_array(
							[$viewHelper, 'Unformat'],
							array_merge([$rawValue], $configColumn->GetFormat() ?: [])
						);
						if ($rawValue === NULL) continue;
					}
					$rawValueToCheckType = $rawValue;
					// complete possible operator prefixes from submitted value
					$containsPercentage = $this->checkFilterFormValueForSpecialLikeChar($rawValue, '%');
					$containsUnderScore = $this->checkFilterFormValueForSpecialLikeChar($rawValue, '_');
					if (($containsPercentage & 1) !== 0) 
						$rawValueToCheckType = str_replace('%', '', $rawValueToCheckType);
					if (($containsUnderScore & 1) !== 0) 
						$rawValueToCheckType = str_replace('_', '', $rawValueToCheckType);
					//  check if operator configuration allowes submitted value form
					if ($regex !== NULL && !preg_match($regex, $rawValue)) continue;
					// check value by configured types
					if (strtolower($rawValue) === 'null') {
						if ($columnAllowNullFilter) {
							$values[] = 'null';
						} else {
							$invalidFilterValue = TRUE;
						}
					} else if (is_array($columnTypes) && count($columnTypes) > 0) {
						$typeValidationSuccess = FALSE;
						foreach ($columnTypes as $columnType) {
							$typeValidationSuccessLocal = $this->validateRawFilterValueByType(
								$rawValueToCheckType, $columnType
							);
							if ($typeValidationSuccessLocal) {
								$typeValidationSuccess = TRUE;
								break;
							}
						}
						if (!$typeValidationSuccess) {
							$invalidFilterValue = TRUE;
							continue;
						}
						$values[] = $rawValue;
					} else {
						$values[] = $rawValue;
					}
				}
			}
			if (count($values) === 0) continue;
			// set up filtering value
			$columnDbName = $configColumn->GetDbColumnName();
			if (!isset($filtering[$columnDbName]))
				$filtering[$columnDbName] = [];
			$filtering[$columnDbName][$operator] = $values;
			if (!$multiFiltering) break;
		}
		// set up new initial filtering:
		$this->filtering = $filtering;
		if (!$invalidFilterValue) return TRUE;
		// check if there were any invalid filter value and redirect grid to proper url:
		$canonicalFilter = [];
		foreach ($filtering as $dbColumnName => $operatorAndFilteringValues) {
			$configColumn = $this->configColumns->GetByDbColumnName($dbColumnName);
			$canonicalFilter[$configColumn->GetPropName()] = $operatorAndFilteringValues;
		}
		$gridParams = array_merge($this->urlParams, [static::URL_PARAM_FILTER => $canonicalFilter]);
		$canonicalUrl = $this->Url(NULL, [
			static::URL_PARAM_GRID	=> $gridParams,
		]);
		$context = $this;
		$context::Redirect(
			$canonicalUrl, 
			\MvcCore\IResponse::SEE_OTHER, 
			'Grid filter canonical URL redirect.'
		);
		return FALSE;
	}

	/**
	 * 
	 * @param  string $rawFilterValueStr 
	 * @param  string $typeStr 
	 * @return bool
	 */
	protected function validateRawFilterValueByType ($rawFilterValueStr, $typeStr) {
		$typeValidator = isset(static::$filterValuesTypeValidators[$typeStr])
			? static::$filterValuesTypeValidators[$typeStr]
			: NULL;
		if ($typeValidator === NULL) return FALSE;
		return (bool) preg_match($typeValidator, $rawFilterValueStr);
	}

	/**
	 * Return allowed operators by column configuration.
	 * @param int $columnFilterFlags 
	 */
	protected function getAllowedOperators ($columnFilterFlags) {
		$urlFilterOperators = $this->configUrlSegments->GetUrlFilterOperators();
		$allowRanges		= ($columnFilterFlags & static::FILTER_ALLOW_RANGES) != 0;
		$allowLikeRight		= ($columnFilterFlags & static::FILTER_ALLOW_LIKE_RIGHT_SIDE) != 0;
		$allowLikeLeft		= ($columnFilterFlags & static::FILTER_ALLOW_LIKE_LEFT_SIDE)  != 0;
		$allowLikeAnywhere	= ($columnFilterFlags & static::FILTER_ALLOW_LIKE_ANYWHERE) != 0;
		$operators = ['=', '!=']; // equal and not equal are allowed for filtering by default
		if ($allowRanges)
			$operators = array_merge($operators, ['<', '>', '<=', '>=']);
		if ($allowLikeRight || $allowLikeLeft || $allowLikeAnywhere) 
			$operators = array_merge($operators, ['LIKE', 'NOT LIKE']);
		$allowedOperators = [];
		foreach ($operators as $operator) {
			$urlSegment = $urlFilterOperators[$operator];
			$multipleValues = strpos($operator, '<') === FALSE && strpos($operator, '>') === FALSE;
			$likeOperator = strpos($operator, 'LIKE') !== FALSE;
			$regex = NULL;
			if ($likeOperator && !$allowLikeAnywhere) {
				if ($allowLikeRight && !$allowLikeLeft) {
					$regex = "#^([^%_]).*$#";
				} else if ($allowLikeLeft && !$allowLikeRight) {
					$regex = "#.*([^%_])$#";
				} else if ($allowLikeLeft && $allowLikeRight) {
					$regex = "#^.([^%_]+).$#";
				}
			}
			$allowedOperators[$urlSegment] = (object) [
				'operator'	=> $operator,
				'multiple'	=> $multipleValues,
				'regex'		=> $regex,
			];
		}
		return $allowedOperators;
	}
	
	/**
	 * Remove general unsafe chars. Be carefull, 
	 * this method doesn't prevent SQL inject atacks.
	 * @param string|int $rawValue 
	 * @return string|null
	 */
	protected function removeUnknownChars ($rawValue) {
		// remove white spaces from both sides: `SPACE \t \n \r \0 \x0B`:
		$rawValue = trim((string) $rawValue);
		
		// Remove base ASCII characters from 0 to 31 (included), including new lines and tabs:
		$cleanedValue = strtr($rawValue, static::$baseAsciiChars);

		if (mb_strlen($cleanedValue) === 0) return NULL;
		
		return $cleanedValue;
	}

	/**
	 * 
	 * @param  int $urlItemsPerPage 
	 * @return int
	 */
	protected function getClosestCountScale ($urlItemsPerPage) {
		$differences = [];
		$lastCountScale = 0;
		foreach ($this->countScales as $index => $countScale) {
			if ($countScale === 0) {
				$differences[$urlItemsPerPage > $lastCountScale ? 0 : $urlItemsPerPage] = $index;
			} else {
				$differences[abs($countScale - $urlItemsPerPage)] = $index;
			}
			$lastCountScale = $countScale;
		}
		$minDifference = min(array_keys($differences));
		$minDifferenceCountScaleKey = $differences[$minDifference];
		return $this->countScales[$minDifferenceCountScaleKey];
	}
}
