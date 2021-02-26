<?php

namespace MvcCore\Ext\Controllers\DataGrid;

trait PreDispatchMethods {
	
	/**
	 * Try to parse decorated class properties atributes or PHPDocs tags
	 * to complete array of datagrid columns configuration.
	 * 
	 * First argument is datagrid model instance used to get all instance properties.
	 * 
	 * Second argument is used for automatic columns configuration completion
	 * by model class implementing `\MvcCore\Ext\Controllers\DataGrids\Models\IGridColumns`.
	 * Array keys are properties names, array values are arrays with three items:
	 * - `string`    - database column name 
	 * - `\string[]` - property type(s)
	 * - `array`     - format arguments
	 * 
	 * Thrd argument is access mod flags to load model instance properties.
	 * If value is zero, there are used all access mode flags - private, protected and public.
	 * 
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Models\IGridModel $model 
	 * @param  array                                                $modelMetaData
	 * @param  int                                                  $accesModFlags
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column[]
	 */
	public static function ParseConfigColumns (\MvcCore\Ext\Controllers\DataGrids\Models\IGridModel $model, $modelMetaData = [], $accesModFlags = 0) {
		$modelType = new \ReflectionClass($model);
		if ($accesModFlags === 0)
			$accesModFlags = (
				\ReflectionProperty::IS_PRIVATE | 
				\ReflectionProperty::IS_PROTECTED | 
				\ReflectionProperty::IS_PUBLIC
			);
		$props = $modelType->getProperties($accesModFlags);
		$toolClass = \MvcCore\Application::GetInstance()->GetToolClass();
		$attrsAnotations = $toolClass::GetAttributesAnotations();
		$attrClassFullName = '\\MvcCore\\Ext\\Controllers\\DataGrids\\Configs\\Column';
		$tagName = $attrClassFullName::PHP_DOCS_TAG_NAME;
		$attrClassNoFirstSlash = ltrim($attrClassFullName, '\\');
		$attrClassName = basename(str_replace('\\', '/', $attrClassFullName));
		$result = [];
		foreach ($props as $prop) {
			if ($prop->isStatic()) continue;
			if ($attrsAnotations) {
				$args = $toolClass::GetAttrCtorArgs($prop, $attrClassNoFirstSlash);
			} else {
				$args = $toolClass::GetPhpDocsTagArgs($prop, $tagName);
				if (is_array($args) && count($args) === 2 && $args[0] === $attrClassName) 
					$args = (array) $args[1];
			}
			$propName = $prop->name;
			$urlName = NULL;
			$humanName = NULL;
			$dbColumnName = NULL;
			$types = NULL;
			$format = NULL;
			$modelMetaDataExists = isset($modelMetaData[$propName]);
			if ($modelMetaDataExists) 
				list($dbColumnName, $types, $format) = $modelMetaData[$propName];
			if ($args === NULL && $modelMetaDataExists) {
				$columnConfig = new \MvcCore\Ext\Controllers\DataGrids\Configs\Column(
					$propName, $dbColumnName, $humanName, $urlName, FALSE, FALSE, $types, $format, NULL
				);
				$result[$columnConfig->GetUrlName()] = $columnConfig;
			} else if ($dbColumnName !== NULL || isset($args['dbColumnName'])) {
				if			 (isset($args['dbColumnName']))	$dbColumnName			= $args['dbColumnName'];
				if			 (isset($args['humanName']))	$humanName				= $args['humanName'];
				if			 (isset($args['urlName']))		$urlName				= $args['urlName'];
				$order		= isset($args['order'])			? $args['order']		: NULL;
				$filter		= isset($args['filter'])		? $args['filter']		: NULL;
				$types		= isset($args['types'])			? $args['types']		: NULL;
				$format		= isset($args['format'])		? $args['format']		: NULL;
				$viewHelper	= isset($args['viewHelper'])	? $args['viewHelper']	: NULL;
				$columnConfig = new \MvcCore\Ext\Controllers\DataGrids\Configs\Column(
					$propName, $dbColumnName, $humanName, $urlName, $order, $filter, $types, $format, $viewHelper
				);
				$result[$columnConfig->GetUrlName()] = $columnConfig;
			}
		}
		return $result;
	}

	/**
	 * @inheritDocs
	 * @return void
	 */
	public function PreDispatch () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		if ($this->dispatchState >= \MvcCore\IController::DISPATCH_STATE_PRE_DISPATCHED) return;
		
		$this->GetConfigRendering();
		$this->GetConfigColumns();

		if ($this->viewEnabled) {
			$this->setUpGridViewInstance();
			$this->view->grid = $this;
		}

		parent::PreDispatch();
		
		$this->setUpOffsetLimit();
		$this->setUpOrdering();
		$this->setUpFiltering();
		$this->LoadModel();
		
		$this->setUpPaging();
		$this->setUpCountScales();
		$this->setUpTranslations();
	}
	
	/**
	 * 
	 * @return void
	 */
	protected function setUpGridViewInstance () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		$viewClass = $this->configRendering->GetViewClass();
		$view = (new $viewClass)->SetController($this);
		if ($view instanceof \MvcCore\Ext\Controllers\DataGrids\View);
			$view->SetConfigRendering($this->configRendering);
		$this->view = $view;
	}

	/**
	 * Set up offset and limit properties for datagrid model instance.
	 * Offset is always presented, limit could be `NULL` or integer.
	 * @return void
	 */
	protected function setUpOffsetLimit () {
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
	protected function setUpOrdering () {
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
	protected function setUpFiltering () {
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
	
	/**
	 * 
	 * @throws \InvalidArgumentException 
	 * @return bool
	 */
	public function LoadModel () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		$model = $this->GetModel(TRUE);

		$model
			->SetOffset($this->offset)
			->SetLimit($this->limit)
			->SetFiltering($this->filtering)
			->SetOrdering($this->ordering);
		
		$this->totalCount = $model->GetTotalCount();
		
		// Check if pages count is larger or at least the same as page number from URL:
		$pagesCountByTotalCount = ($this->itemsPerPage > 0) 
			? intval(ceil(floatval($this->totalCount) / floatval($this->itemsPerPage))) 
			: 0 ;
		// If user write to large page number to URL, redirect user to the last page.
		$page = $this->urlParams['page'];
		if ($page > $pagesCountByTotalCount && $pagesCountByTotalCount > 0) {
			/** @var $context \MvcCore\Controller */
			$context = $this;
			$redirectUrl = $this->GridUrl([
				'page'	=> $pagesCountByTotalCount,
			]);
			$context::Redirect(
				$redirectUrl, 
				\MvcCore\IResponse::SEE_OTHER, 
				'Grid page is too high by total count.'
			);
			return FALSE;
		}

		$this->pageData = $model->GetPageData();

		return TRUE;
	}

	/**
	 * 
	 * @return void
	 */
	protected function setUpPaging () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		$renderPaging = $this->configRendering->GetRenderControlPaging();
		if (!$renderPaging) return;

		$multiplePages = $this->totalCount > $this->itemsPerPage && $this->itemsPerPage !== 0;
		if (($renderPaging & \MvcCore\Ext\Controllers\IDataGrid::CONTROL_DISPLAY_IF_NECESSARY) != 0 && !$multiplePages) {
			$this->configRendering->SetRenderControlPaging(\MvcCore\Ext\Controllers\IDataGrid::CONTROL_DISPLAY_NEVER);
			return;
		}
		
		$paging = [];
		
		$itemsPerPage = $this->itemsPerPage;
		if (
			$this->itemsPerPage === 0 && (
				$renderPaging & \MvcCore\Ext\Controllers\IDataGrid::CONTROL_DISPLAY_ALWAYS
			) != 0
		) $itemsPerPage = $this->totalCount;

		$nearbyPages = $this->configRendering->GetControlPagingNearbyPagesCount();
		$outerPages = $this->configRendering->GetControlPagingOuterPagesCount();
		$prevAndNext = $this->configRendering->GetRenderControlPagingPrevAndNext();
		$firstAndLast = $this->configRendering->GetRenderControlPagingFirstAndLast();

		$pagesCount = intval(ceil($this->totalCount / $itemsPerPage));
		$currentPage = intdiv($this->offset, $itemsPerPage) + 1;

		$displayPrev = $prevAndNext && $this->offset - $itemsPerPage >= 0;
		$displayFirst = $firstAndLast && $this->offset > $nearbyPages * $itemsPerPage;
		$displayNext = $prevAndNext && $this->offset + $itemsPerPage < $this->totalCount;
		$displayLast = $firstAndLast && $this->offset < ($pagesCount * $itemsPerPage) - (($nearbyPages + 1) * $itemsPerPage);
		
		$outerPagesMinRatio = $this->configRendering->GetControlPagingOuterPagesDisplayRatio();
		$hiddenStartingPagesCount = $currentPage - $nearbyPages - ($firstAndLast ? 2 : 1);
		$displayOuterStartPages = $outerPages && floatval($hiddenStartingPagesCount) / floatval($outerPages) > $outerPagesMinRatio;
		$hiddenEndingPagesCount = $pagesCount - ($currentPage + $nearbyPages) - ($firstAndLast ? 1 : 0);
		$displayOuterEndPages = $outerPages && (floatval($hiddenEndingPagesCount ) / floatval($outerPages)) > $outerPagesMinRatio;
		
		// prev, first and `...`:
		if ($displayPrev) 
			$paging[] = (new \MvcCore\Ext\Controllers\DataGrids\Paging\Page(
				$this->GridPageUrl($this->offset - $itemsPerPage), 
				$this->GetControlText('previous'), FALSE, TRUE
			))->SetIsPrev(TRUE);
		if ($displayFirst) 
			$paging[] = (new \MvcCore\Ext\Controllers\DataGrids\Paging\Page(
				$this->GridPageUrl(0), 
				$this->GetControlText('first')
			))->SetIsFirst(TRUE);
		if ($displayFirst || $displayPrev)
			$paging[] = new \MvcCore\Ext\Controllers\DataGrids\Paging\Dots;

		// right outer pages and `...`:
		if ($displayOuterStartPages) {
			$stepValue = floatval($hiddenStartingPagesCount) / floatval($outerPages + 1);
			$stepCounter = $firstAndLast ? 1.0 : 0.0;
			for ($i = 0; $i < $outerPages; $i++) {
				$stepCounter += $stepValue;
				$pageIndex = intval(floor($stepCounter));
				if ($i > 0)
					$paging[] = new \MvcCore\Ext\Controllers\DataGrids\Paging\Dot;
				$paging[] = new \MvcCore\Ext\Controllers\DataGrids\Paging\Page(
					$this->GridPageUrl(($pageIndex - 1) * $itemsPerPage), 
					$pageIndex
				);
			}
			$paging[] = new \MvcCore\Ext\Controllers\DataGrids\Paging\Dots;
		}
		
		// left nearby pages, current page and right nearby pages:
		$beginIndex = max($currentPage - $nearbyPages, 1);
		$endIndex = min($currentPage + $nearbyPages + 1, $pagesCount + 1);

		$leftOverflowPagesCount = $currentPage - ($nearbyPages + 1);
		if ($leftOverflowPagesCount < 0)
			$endIndex = min($endIndex + abs($leftOverflowPagesCount), $pagesCount + 1);
		
		$rightOverflowPagesCount = $pagesCount - $currentPage - $nearbyPages;
		if ($rightOverflowPagesCount < 0)
			$beginIndex = max($beginIndex - abs($rightOverflowPagesCount), 1);

		for ($pageIndex = $beginIndex; $pageIndex < $endIndex; $pageIndex++) {
			$paging[] = new \MvcCore\Ext\Controllers\DataGrids\Paging\Page(
				$this->GridPageUrl(($pageIndex - 1) * $itemsPerPage), 
				$pageIndex, 
				$pageIndex === $currentPage
			);
		}
		
		// `...` and left outer pages:
		if ($displayOuterEndPages) {
			$paging[] = new \MvcCore\Ext\Controllers\DataGrids\Paging\Dots;
			$stepValue = floatval($hiddenEndingPagesCount) / floatval($outerPages + 1);
			$stepCounter = floatval($pagesCount - $hiddenEndingPagesCount - ($firstAndLast ? 1 : 0));
			for ($i = 0; $i < $outerPages; $i++) {
				$stepCounter += $stepValue;
				$pageIndex = intval(ceil($stepCounter));
				if ($i > 0)
					$paging[] = new \MvcCore\Ext\Controllers\DataGrids\Paging\Dot;
				$paging[] = new \MvcCore\Ext\Controllers\DataGrids\Paging\Page(
					$this->GridPageUrl(($pageIndex - 1) * $itemsPerPage), 
					$pageIndex
				);
			}
		}

		// `...`, last and next:
		if ($displayNext || $displayLast)
			$paging[] = new \MvcCore\Ext\Controllers\DataGrids\Paging\Dots;
		if ($displayLast) 
			$paging[] = (new \MvcCore\Ext\Controllers\DataGrids\Paging\Page(
				$this->GridPageUrl(($pagesCount - 1) * $itemsPerPage), 
				str_replace('{0}', $pagesCount, $this->GetControlText('last'))
			))->SetIsLast(TRUE);
		if ($displayNext) 
			$paging[] = (new \MvcCore\Ext\Controllers\DataGrids\Paging\Page(
				$this->GridPageUrl($this->offset + $itemsPerPage), 
				$this->GetControlText('next')
			))->SetIsNext(TRUE);

		$this->paging = new \MvcCore\Ext\Controllers\DataGrids\Iterators\Paging($paging);
	}
	
	/**
	 * 
	 * @return void
	 */
	protected function setUpCountScales () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		$renderCountScales = $this->configRendering->GetRenderControlCountScales();
		if (!$renderCountScales) return;
		$multiplePages = $this->totalCount > $this->itemsPerPage && $this->itemsPerPage !== 0;
		if (($renderCountScales & \MvcCore\Ext\Controllers\IDataGrid::CONTROL_DISPLAY_IF_NECESSARY) != 0 && !$multiplePages) 
			$this->configRendering->SetRenderControlCountScales(\MvcCore\Ext\Controllers\IDataGrid::CONTROL_DISPLAY_NEVER);
	}
	
	/**
	 * 
	 * @return void
	 */
	protected function setUpTranslations () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		if (!$this->translate) return;
		foreach ($this->controlsTexts as $key => $controlText)
			$this->controlsTexts[$key] = call_user_func_array(
				$this->translator, [$controlText, ['{0}']]
			);
		foreach ($this->configColumns as $configColumn) {
			$configColumn->SetHumanName(
				call_user_func_array(
					$this->translator, [$configColumn->GetHumanName()]
				)
			);
			if ($this->translateUrlNames)
				$configColumn->SetUrlName(
					call_user_func_array(
						$this->translator, [$configColumn->GetUrlName()]
					)
				);
		}
	}
}
