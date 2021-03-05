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

trait InitMethods {

	/**
	 * Create `\MvcCore\Ext\Controllers\DataGrid` instance.
	 * @param  \MvcCore\Controller|NULL $controller
	 * @param  string|int|NULL          $childControllerIndex Automatic name for this instance used in view.
	 * @return void
	 */
	public function __construct (\MvcCore\IController $controller = NULL, $childControllerIndex = NULL) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		/** @var $controller \MvcCore\Controller */
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
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		if ($this->dispatchState > \MvcCore\IController::DISPATCH_STATE_CREATED) return;
		
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
		$this->GetConfigRendering();
		$this->GetConfigColumns();

		$this->GetRoute();
		$this->GetUrlParams();
		
		$this->initGridAction();
		if (!$this->initUrlParams()) return; // redirect inside
		$this->initOffsetLimit();
		
		if (!$this->initUrlBuilding()) return; // redirect inside
		$this->initTranslations();
		$this->initOperators();
		
		$this->initSorting();
		$this->initFiltering();

		call_user_func([$this, $this->gridAction]);
	}

	/**
	 * Complete internal action method name.
	 * @return void
	 */
	protected function initGridAction () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		$gridActionParam = $this->request->GetParam(static::URL_PARAM_ACTION, '-_a-zA-Z', static::$gridActionDefaultKey, 'string');
		if (!isset(static::$gridActions[$gridActionParam])) $gridActionParam = static::$gridActionDefaultKey;
		$this->gridAction = static::$gridActions[$gridActionParam];
	}

	/**
	 * Initialize internal property `$this->queryStringParamsSepatator`
	 * to be able to build internal grid URL strings.
	 * Check valid values from URL for page and items par page.
	 * If some value is invalid, redirect to default value.
	 * @return bool
	 */
	protected function initUrlParams () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		/** @var $context \MvcCore\Controller */
		$context = $this;

		// init `$this->queryStringParamsSepatator` from router to build grid urls:
		if ($this->queryStringParamsSepatator === NULL) {
			$routerType = new \ReflectionClass($this->router);
			$method = $routerType->getMethod('getQueryStringParamsSepatator');
			$method->setAccessible(TRUE);
			$this->queryStringParamsSepatator = $method->invoke($this->router);
		}

		// set up default page if null:
		if (isset($this->urlParams['page'])) {
			if ($this->urlParams['page'] === 0) {
				// redirect to proper page number:
				$redirectUrl = $this->GridUrl([
					'page'	=> 1,
				]);
				$context::Redirect(
					$redirectUrl, 
					\MvcCore\IResponse::SEE_OTHER, 
					'Grid page is too low.'
				);
				return FALSE;
			}
		} else {
			$this->urlParams['page'] = 1;
		}

		// set up items per page configured from script into count cales if it is not there:
		if (!in_array($this->itemsPerPage, $this->countScales, TRUE)) {
			$this->countScales[] = $this->itemsPerPage;
			sort($this->countScales);
			if ($this->countScales[0] === 0) {
				array_shift($this->countScales);
				$this->countScales[] = 0;
			}
		}

		// set up default count if null or check if count has allowed size:
		if (!isset($this->urlParams['count'])) {
			$this->urlParams['count'] = $this->GetItemsPerPage();
		} else {
			$urlCount = $this->urlParams['count'];
			$lastCountsScale = $this->countScales[count($this->countScales) - 1];
			if ($lastCountsScale !== 0 && ($urlCount === 0 || $urlCount > $lastCountsScale)) {
				// redirect to allowed max count:
				$redirectUrl = $this->GridUrl([
					'page'	=> $this->urlParams['page'],
					'count'	=> $lastCountsScale,
				]);
				$context::Redirect(
					$redirectUrl, 
					\MvcCore\IResponse::SEE_OTHER, 
					'Grid count is too high.'
				);
				return FALSE;
			}
		}

		// check if count scale from url is allowed and change count scale if necessary:
		$urlItemsPerPage = $this->urlParams['count'];
		if (
			$this->allowedCustomUrlCountScale ||
			in_array($urlItemsPerPage, $this->countScales, TRUE)
		) {
			$this->itemsPerPage = $urlItemsPerPage;
		} else {
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
			$minDifferenceCountScale = $this->countScales[$minDifferenceCountScaleKey];
			$redirectUrl = $this->GridUrl([
				'count'	=> $minDifferenceCountScale,
			]);
			$context::Redirect(
				$redirectUrl, 
				\MvcCore\IResponse::SEE_OTHER, 
				'Grid custom count scale is not allowed.'
			);
			return FALSE;
		}
		
		// check if page is not larger than 1 if count is unlimited:
		$this->page = $this->urlParams['page'];
		if ($this->itemsPerPage === 0 && $this->page > 1) {
			$redirectUrl = $this->GridUrl([
				'page'	=> 1,
			]);
			$context::Redirect(
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
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		$count = $this->urlParams['count'];
		$inlimitedCount = $count === 0;

		$this->limit = $inlimitedCount
			? NULL
			: $count;

		if ($inlimitedCount) {
			$this->offset = 0;
		} else {
			$page = $this->urlParams['page'];
			$this->offset = ($page - 1) * $this->limit;
		}
	}
	
	/**
	 * Initialize datagrid internal action method name and translation booleans.
	 * @return bool
	 */
	protected function initUrlBuilding () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		/** @var $context \MvcCore\Controller */
		$context = $this;
		
		$routeConfig = $this->route->GetAdvancedConfigProperty('defaults');
		$this->itemsPerPageRouteConfig = $routeConfig['count'];
		
		// remove all default values from route to build urls with `$this->urlParams` only:
		$defaultParams = [];
		$pageIsDefault = $this->page === 1;
		$countIsDefault = $this->itemsPerPage === $this->itemsPerPageRouteConfig;
		if ($pageIsDefault && $countIsDefault) {
			$defaultParams = ['page' => 1, 'count' => $this->itemsPerPageRouteConfig];
		} else if ($pageIsDefault && !$countIsDefault) {
			$defaultParams = ['page' => NULL, 'count' => NULL];
		} else if (!$pageIsDefault && $countIsDefault) {
			$defaultParams = ['page' => NULL, 'count' => $this->itemsPerPageRouteConfig];
		} else {
			$defaultParams = ['page' => NULL, 'count' => NULL];
		}
		$this->route->SetDefaults($defaultParams);
		
		// redirect to canonical url if necessary:
		$defaultAction = $this->gridAction === static::$gridActions[static::$gridActionDefaultKey];
		if ($defaultAction && $this->router->GetAutoCanonizeRequests()) {
			list ($gridParam) = $this->route->Url(
				$this->gridRequest,
				[],
				$this->urlParams,
				$this->queryStringParamsSepatator,
				FALSE
			);
			$gridParam = rtrim($gridParam, '/');
			$reqPathRaw = $this->gridRequest->GetPath(FALSE);
			if ($gridParam !== $reqPathRaw) {
				$redirectUrl = $this->Url('self', [static::URL_PARAM_GRID => rawurldecode($gridParam)]);
				$context::Redirect(
					$redirectUrl, 
					\MvcCore\IResponse::SEE_OTHER, 
					'Grid canonical URL redirect.'
				);
				return FALSE;
			}
		}

		// remove all default values from route to build urls with `$this->urlParams` only:
		$this->route->SetDefaults([]);
		
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
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		if (!$this->filteringMode) return;
		$this->allowedOperators = $this->getAllowedOperators($this->filteringMode);
	}

	/**
	 * Parse sorting from URL as array of databse column names as keys 
	 * and sorting directions `ASC | DESC` as values.
	 * @return void
	 */
	protected function initSorting () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		if (!$this->sortingMode || !$this->urlParams['sort']) return;
		$rawSorting = trim($this->urlParams['sort']);
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
			$rawColumnName = $this->removeUnsafeChars($rawColumnName);
			if ($rawColumnName === NULL) continue;
			if ($this->translateUrlNames)
				$rawColumnName = call_user_func_array(
					$this->translator, [$rawColumnName]
				);
			if (!isset($this->configColumns[$rawColumnName])) continue;
			$configColumn = $this->configColumns[$rawColumnName];
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
	 * @return void
	 */
	protected function initFiltering () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		if (!$this->filteringMode || !isset($this->urlParams['filter'])) return;
		$rawFiltering = trim($this->urlParams['filter']);
		if (mb_strlen($rawFiltering) === 0) return;
		$subjsDelim = $this->configUrlSegments->GetUrlDelimiterSubjects();
		$subjValueDelim = $this->configUrlSegments->GetUrlDelimiterSubjectValue();
		$valuesDelim = $this->configUrlSegments->GetUrlDelimiterValues();

		$rawFilteringItems = explode($subjsDelim, $rawFiltering);
		$multiFiltering = ($this->filteringMode & static::FILTER_MULTIPLE_COLUMNS) != 0;
		$filtering = [];
		foreach ($rawFilteringItems as $rawFilteringItem) {
			$delimPos = mb_strpos($rawFilteringItem, $subjValueDelim);
			$operator = '=';
			$multiple = TRUE;
			$rawOperatorStr = NULL;
			$values = NULL;
			$regex = NULL;
			if ($delimPos === FALSE) {
				$rawColumnName = $rawFilteringItem;
				$values = [1]; // boolean 1 as default value if no operator and value defined
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
				$rawValuesStr = $this->removeUnsafeChars($rawValuesStr);
				if ($rawValuesStr === NULL) continue;
			}
			$rawColumnName = $this->removeUnsafeChars($rawColumnName);
			if ($rawColumnName === NULL) continue;
			if ($this->translateUrlNames)
				$rawColumnName = call_user_func_array(
					$this->translator, [$rawColumnName]
				);
			if (!isset($this->configColumns[$rawColumnName])) continue;
			$configColumn = $this->configColumns[$rawColumnName];
			$columnFilterCfg = $configColumn->GetFilter();
			if ($columnFilterCfg === FALSE || $columnFilterCfg === NULL) continue;
			$allowedOperators = $columnFilterCfg === TRUE || !is_integer($columnFilterCfg)
				? $this->allowedOperators
				: $this->getAllowedOperators($columnFilterCfg);
			if ($values === NULL) {
				$rawValues = explode($valuesDelim, $rawValuesStr);
				foreach ($rawValues as $rawValue) {
					$rawValue = trim($rawValue);
					if ($rawValue !== '') $values[] = $rawValue;
				}
			}
			if (count($values) === 0) continue;
			if (isset($allowedOperators[$rawOperatorStr])) {
				$operatorCfg = $allowedOperators[$rawOperatorStr];
				$operator = $operatorCfg->operator;
				$multiple = $operatorCfg->multiple;
				$regex = $operatorCfg->regex;
			}
			if (!$multiple && count($values) > 1)
				$values = [$values[0]];
			if ($regex !== NULL) {
				$newValues = [];
				foreach ($values as $value)
					if (preg_match($regex, $value))
						$newValues[] = $value;
				if (count($newValues) === 0) continue;
				$values = $newValues;
			}
			$columnDbName = $configColumn->GetDbColumnName();
			if (!isset($filtering[$columnDbName]))
				$filtering[$columnDbName] = [];
			$filtering[$columnDbName][$operator] = $values;
			if (!$multiFiltering) break;
		}
		$this->filtering = $filtering;
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
	protected function removeUnsafeChars ($rawValue) {
		// remove white spaces from both sides: `SPACE \t \n \r \0 \x0B`:
		$rawValue = trim((string) $rawValue);
		
		// Remove base ASCII characters from 0 to 31 included:
		$cleanedValue = strtr($rawValue, static::$baseAsciiChars);

		// Replace characters to entities: & " ' < > to &amp; &quot; &#039; &lt; &gt;
		// http://php.net/manual/en/function.htmlspecialchars.php
		$cleanedValue = htmlspecialchars($cleanedValue, ENT_QUOTES); // double and single quotes
		
		// Replace characters to entities: | = \ %
		$cleanedValue = strtr($cleanedValue, static::$specialMeaningChars);

		if (mb_strlen($cleanedValue) === 0) return NULL;
		
		return $cleanedValue;
	}
}
