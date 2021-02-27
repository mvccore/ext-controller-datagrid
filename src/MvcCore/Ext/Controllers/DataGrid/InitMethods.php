<?php

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
		parent::Init();

		$this->GetConfigUrlSegments();
		$this->GetConfigRendering();
		$this->GetConfigColumns();

		$this->GetRoute();
		$this->GetUrlParams();

		$this->initUrlParams();
		$this->initOffsetLimit();
		$this->initOrdering();
		$this->initFiltering();

		$this->initLocalProps();
	}

	/**
	 * @return void
	 */
	protected function initUrlParams () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		/** @var $context \MvcCore\Controller */
		$context = $this;

		// set up default page if null:
		if (isset($this->urlParams['page'])) {
			if ($this->urlParams['page'] === 0) {
				// redirect to proper page number:
				$redirectUrl = $this->GridUrl([
					'page'	=> 1,
				]);
				return $context::Redirect(
					$redirectUrl, 
					\MvcCore\IResponse::SEE_OTHER, 
					'Grid page is too low.'
				);
			}
		} else {
			$this->urlParams['page'] = 1;
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
				return $context::Redirect(
					$redirectUrl, 
					\MvcCore\IResponse::SEE_OTHER, 
					'Grid count is too high.'
				);
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
			return $context::Redirect(
				$redirectUrl, 
				\MvcCore\IResponse::SEE_OTHER, 
				'Grid custom count scale is not allowed.'
			);
		}
		
		// check if page is not larger than 1 if count is unlimited:
		$this->page = $this->urlParams['page'];
		if ($this->itemsPerPage === 0 && $this->page > 1) {
			$redirectUrl = $this->GridUrl([
				'page'	=> 1,
			]);
			return $context::Redirect(
				$redirectUrl, 
				\MvcCore\IResponse::SEE_OTHER, 
				'Grid page is too high with unlimited count.'
			);
		}
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
	 * Parse ordering from URL as array of databse column names as keys 
	 * and ordering directions `ASC | DESC` as values.
	 * @return void
	 */
	protected function initOrdering () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		$rawOrdering = $this->urlParams['order'];
		$subjsDelim = $this->configUrlSegments->GetUrlDelimiterSubjects();
		$subjValueDelim = $this->configUrlSegments->GetUrlDelimiterSubjectValue();
		$ascSuffix = $this->configUrlSegments->GetUrlSuffixOrderAsc();
		$descSuffix = $this->configUrlSegments->GetUrlSuffixOrderDesc();
		$orderSuffixes = [$ascSuffix => 'ASC', $descSuffix => 'DESC'];
		$rawOrderingItems = explode($subjsDelim, $rawOrdering);
		$ordering = [];
		foreach ($rawOrderingItems as $rawOrderingItem) {
			$delimPos = mb_strpos($rawOrderingItem, $subjValueDelim);
			$direction = 'ASC';
			if ($delimPos === FALSE) {
				$rawColumnName = $rawOrderingItem;
			} else {
				$rawColumnName = mb_substr($rawOrderingItem, 0, $delimPos);
				$rawDirection = mb_substr($rawOrderingItem, $delimPos + 1);
				if (isset($orderSuffixes[$rawDirection])) 
					$direction = $orderSuffixes[$rawDirection];
			}
			if ($this->translateUrlNames)
				$rawColumnName = call_user_func_array(
					$this->translator, [$rawColumnName]
				);
			if (!isset($this->configColumns[$rawColumnName])) continue;
			$configColumn = $this->configColumns[$rawColumnName];
			$ordering[$configColumn->GetDbColumnName()] = $direction;
			if (!$this->multiSorting) break;
		}
		if (count($ordering) === 0) {
			foreach ($this->configColumns as $configColumn) {
				$configColumnOrder = $configColumn->GetOrder();
				if (is_string($configColumnOrder)) {
					$dbColumnName = $configColumn->GetDbColumnName();
					$ordering[$dbColumnName] = $configColumnOrder;
					if (!$this->multiSorting) break;
				}
			}
		}
		$this->ordering = $ordering;
	}
	
	/**
	 * Parse filtering from URL as array of databse column names as keys 
	 * and values as array of raw filtering values.
	 * @return void
	 */
	protected function initFiltering () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		$rawFiltering = $this->urlParams['filter'];
		$subjsDelim = $this->configUrlSegments->GetUrlDelimiterSubjects();
		$subjValueDelim = $this->configUrlSegments->GetUrlDelimiterSubjectValue();
		$valuesDelim = $this->configUrlSegments->GetUrlDelimiterValues();
		$rawFilteringItems = explode($subjsDelim, $rawFiltering);
		$filtering = [];
		foreach ($rawFilteringItems as $rawFilteringItem) {
			$delimPos = mb_strpos($rawFilteringItem, $subjValueDelim);
			$values = [];
			if ($delimPos === FALSE) {
				$rawColumnName = $rawFilteringItem;
				$values = [1];
			} else {
				$rawColumnName = mb_substr($rawFilteringItem, 0, $delimPos);
				$rawValuesStr = mb_substr($rawFilteringItem, $delimPos + 1);
				$rawValues = explode($valuesDelim, $rawValuesStr);
				foreach ($rawValues as $rawValue) {
					$rawValue = trim($rawValue);
					if ($rawValue !== '') $values[] = $rawValue;
				}
			}
			if ($this->translateUrlNames)
				$rawColumnName = call_user_func_array(
					$this->translator, [$rawColumnName]
				);
			if (!isset($this->configColumns[$rawColumnName])) continue;
			$configColumn = $this->configColumns[$rawColumnName];
			if (count($values) === 0) continue;
			$filtering[$configColumn->GetDbColumnName()] = $values;
			if (!$this->multiFiltering) break;
		}
		$this->filtering = $filtering;
	}
	
	protected function initLocalProps () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		$gridActionParam = $this->request->GetParam(static::PARAM_ACTION);
		xxx($gridActionParam);

		$this->translate = is_callable($this->translator) || $this->translator instanceof \Closure;
		if (!$this->translate)
			$this->translateUrlNames = FALSE;
	}
}
