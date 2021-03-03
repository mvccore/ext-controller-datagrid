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
				$sort		= isset($args['sort'])			? $args['sort']			: NULL;
				$filter		= isset($args['filter'])		? $args['filter']		: NULL;
				$types		= isset($args['types'])			? $args['types']		: NULL;
				$format		= isset($args['format'])		? $args['format']		: NULL;
				$viewHelper	= isset($args['viewHelper'])	? $args['viewHelper']	: NULL;
				$columnConfig = new \MvcCore\Ext\Controllers\DataGrids\Configs\Column(
					$propName, $dbColumnName, $humanName, $urlName, $sort, $filter, $types, $format, $viewHelper
				);
				$result[$columnConfig->GetUrlName()] = $columnConfig;
			}
		}
		return $result;
	}

	/**
	 * Process necessary operations for rendering 
	 * and set up and call model instance total count.
	 * @inheritDocs
	 * @return void
	 */
	public function PreDispatch () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		if ($this->dispatchState >= \MvcCore\IController::DISPATCH_STATE_PRE_DISPATCHED) return;
		
		if ($this->viewEnabled) {
			$this->preDispatchViewInstance();
			$this->view->grid = $this;
		}

		parent::PreDispatch();
		
		$this->LoadModel();
		
		$this->preDispatchTotalCount();
		$this->preDispatchPaging();
		$this->preDispatchCountScales();
		$this->preDispatchTranslations();
		$this->preDispatchRenderConfig();
		
		if ($this->configRendering->GetRenderTableHeadFiltering()) 
			$this->tableHeadFilterForm->PreDispatch(FALSE);
	}
	
	/**
	 * Set up model instance and call database for total count.
	 * @throws \InvalidArgumentException 
	 * @return void
	 */
	public function LoadModel () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		$model = $this->GetModel(TRUE);
		$model
			->SetOffset($this->offset)
			->SetLimit($this->limit)
			->SetFiltering($this->filtering)
			->SetSorting($this->sorting);
		$this->totalCount = $model->GetTotalCount();
	}

	/**
	 * Create customized datagrid view instance.
	 * @return void
	 */
	protected function preDispatchViewInstance () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		$viewClass = $this->configRendering->GetViewClass();
		$view = (new $viewClass)->SetController($this);
		if ($view instanceof \MvcCore\Ext\Controllers\DataGrids\View);
			$view->SetConfigRendering($this->configRendering);
		$this->view = $view;
	}

	/**
	 * Check if pages count is larger or at least the same as page number from URL.
	 * @return bool
	 */
	protected function preDispatchTotalCount () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
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

		$this->pageData = $this->model->GetPageData();

		return TRUE;
	}

	/**
	 * Initialize paging control render config boolean and paging content.
	 * @return void
	 */
	protected function preDispatchPaging () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		$renderPaging = $this->configRendering->GetRenderControlPaging();
		if (!$renderPaging) return;

		$multiplePages = $this->totalCount > $this->itemsPerPage && $this->itemsPerPage !== 0;
		if (($renderPaging & \MvcCore\Ext\Controllers\IDataGrid::CONTROL_DISPLAY_IF_NECESSARY) != 0 && !$multiplePages) {
			$this->configRendering->SetRenderControlPaging(static::CONTROL_DISPLAY_NEVER);
			return;
		}
		
		$paging = [];
		$itemsPerPage = $this->itemsPerPage;
		if (
			$this->itemsPerPage === 0 && (
				$renderPaging & static::CONTROL_DISPLAY_ALWAYS
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
		
		$this->preDispatchPagingPrevAndFirst($paging, [
			$itemsPerPage, $displayFirst, $displayPrev
		]);
		$this->preDispatchPagingLeftOuterPages($paging, [
			$itemsPerPage, $displayOuterStartPages, $firstAndLast, $outerPages, $hiddenStartingPagesCount
		]);
		$this->preDispatchPagingNearbyAndCurrent($paging, [
			$pagesCount, $itemsPerPage, $currentPage, $nearbyPages
		]);
		$this->preDispatchPagingRightOuterPages($paging, [
			$pagesCount, $itemsPerPage, $displayOuterEndPages, $firstAndLast, $outerPages, $hiddenEndingPagesCount
		]);
		$this->preDispatchPagingLastAndNext($paging, [
			$pagesCount, $itemsPerPage, $displayLast, $displayNext
		]);

		$this->paging = new \MvcCore\Ext\Controllers\DataGrids\Iterators\Paging($paging);
	}
	
	/**
	 * Complete paging control items prev, first and `...`.
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Paging\Item[] $paging 
	 * @param  array                                            $params
	 * @return void
	 */
	protected function preDispatchPagingPrevAndFirst (& $paging, $params) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		list ($itemsPerPage, $displayFirst, $displayPrev) = $params;
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
	}
	
	/**
	 * Complete paging control items right outer pages and `...`.
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Paging\Item[] $paging 
	 * @param  array                                            $params
	 * @return void
	 */
	protected function preDispatchPagingLeftOuterPages (& $paging, $params) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		list($itemsPerPage, $displayOuterStartPages, $firstAndLast, $outerPages, $hiddenStartingPagesCount) = $params;
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
	}

	/**
	 * Complete paging control items left nearby pages, current page and right nearby pages.
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Paging\Item[] $paging 
	 * @param  array                                            $params
	 * @return void
	 */
	protected function preDispatchPagingNearbyAndCurrent (& $paging, $params) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		list($pagesCount, $itemsPerPage, $currentPage, $nearbyPages) = $params;
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
	}

	/**
	 * Complete paging control items `...` and right outer pages.
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Paging\Item[] $paging 
	 * @param  array                                            $params
	 * @return void
	 */
	protected function preDispatchPagingRightOuterPages (& $paging, $params) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		list($pagesCount, $itemsPerPage, $displayOuterEndPages, $firstAndLast, $outerPages, $hiddenEndingPagesCount) = $params;
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
	}

	/**
	 * Complete paging control items `...`, last and next.
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Paging\Item[] $paging 
	 * @param  array                                            $params
	 * @return void
	 */
	protected function preDispatchPagingLastAndNext (& $paging, $params) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		list ($pagesCount, $itemsPerPage, $displayLast, $displayNext) = $params;
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
	}
	
	/**
	 * Switch rendering config boolean to render count scales control to 
	 * never render, if datagrid renders only single page and items per page value
	 * is greater than zero (items per page value is not set to unlimited value).
	 * Because than - the count scales control is completely useless.
	 * @return void
	 */
	protected function preDispatchCountScales () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		$renderCountScales = $this->configRendering->GetRenderControlCountScales();
		if (!$renderCountScales) return;
		$multiplePages = $this->totalCount > $this->itemsPerPage && $this->itemsPerPage !== 0;
		if (!$multiplePages && ($renderCountScales & static::CONTROL_DISPLAY_IF_NECESSARY) != 0) 
			$this->configRendering->SetRenderControlCountScales(static::CONTROL_DISPLAY_NEVER);
	}
	
	/**
	 * Translate if necessary:
	 * - controls texts
	 * - columns human names
	 * - columns url names (if configured)
	 * @return void
	 */
	protected function preDispatchTranslations () {
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

	/**
	 * Switch necessary rendering config booleans to `FALSE`
	 * if sorting or filtering is completely disabled.
	 * @return void
	 */
	protected function preDispatchRenderConfig () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		$sortDisabled = $this->sortingMode === static::SORT_DISABLED;
		$filterDisabled = $this->filteringMode === static::FILTER_DISABLED;
		$renderConf = $this->configRendering;
		if ($sortDisabled) {
			$renderConf->SetRenderControlSorting(FALSE);
			$renderConf->SetRenderTableHeadSorting(FALSE);
		}
		if ($filterDisabled) {
			$renderConf->SetRenderFilterForm(FALSE);
			$renderConf->SetRenderTableHeadFiltering(FALSE);
		}
		if (!$renderConf->GetRenderTableHead()) {
			$renderConf->SetRenderTableHeadSorting(FALSE);
			$renderConf->SetRenderTableHeadFiltering(FALSE);
		}
		$gridType = $renderConf->GetType();
		$gridTableType = ($gridType & static::TYPE_TABLE) !== 0;
		$this->AddCssClasses([
			'grid-type-' . ($gridTableType ? 'table' : 'grid')
		]);
	}
}
