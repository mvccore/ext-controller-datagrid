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
trait PreDispatchMethods {
	
	/**
	 * Process necessary operations for rendering 
	 * and set up and call model instance total count.
	 * @inheritDocs
	 * @return void
	 */
	public function PreDispatch () {
		if ($this->dispatchState <= \MvcCore\IController::DISPATCH_STATE_INITIALIZED) 
			$this->Init();
		if ($this->dispatchState >= \MvcCore\IController::DISPATCH_STATE_PRE_DISPATCHED) return;
		
		if ($this->viewEnabled) {
			if ($this->view === NULL)
				$this->view = $this->createView(TRUE);
			$this->view->grid = $this;
		}

		parent::PreDispatch();

		$this->LoadModel();
		
		if (!$this->preDispatchTotalCount()) return;
		$this->preDispatchTranslations();
		$this->preDispatchPaging();
		$this->preDispatchCountScales();
		$this->preDispatchRenderConfig();
		$this->preDispatchColumnsConfigs();
		$this->preDispatchTableHeadFilterForm();
	}
	
	/**
	 * Set up model instance and call database for total count.
	 * @throws \InvalidArgumentException 
	 * @return void
	 */
	public function LoadModel () {
		$model = $this->GetModel(TRUE);
		$model
			->SetGrid($this)
			->SetOffset($this->offset)
			->SetLimit($this->limit)
			->SetSorting($this->sorting)
			->SetFiltering($this->filtering);
		$this->totalCount = $model->GetTotalCount();
		$this->pageData = $model->GetPageData();
	}

	/**
	 * Create customized datagrid view instance.
	 * @param  bool $actionView
	 * @return \MvcCore\View
	 */
	protected function createView ($actionView = TRUE) {
		$viewClass = $this->configRendering->GetViewClass();
		$view = $viewClass::CreateInstance()
			->SetController($this)
			->SetEncoding($this->response->GetEncoding());
		if ($view instanceof \MvcCore\Ext\Controllers\DataGrids\View);
			$view->SetConfigRendering($this->configRendering);
		return $view;
	}
	
	/**
	 * Check if pages count is larger or at least the same as page number from URL.
	 * @return bool
	 */
	protected function preDispatchTotalCount () {
		$pagesCountByTotalCount = ($this->count > 0) 
			? intval(ceil(floatval($this->totalCount) / floatval($this->count))) 
			: 0 ;
		// If user write to large page number to URL, redirect user to the last page.
		$page = $this->urlParams[static::URL_PARAM_PAGE];
		if ($page > $pagesCountByTotalCount && $pagesCountByTotalCount > 0) {
			$redirectUrl = $this->GridUrl([
				static::URL_PARAM_PAGE	=> $pagesCountByTotalCount,
			]);
			/** @var \MvcCore\Controller $this */
			$this::Redirect(
				$redirectUrl, 
				\MvcCore\IResponse::SEE_OTHER, 
				'Grid page is too high by total count.'
			);
			return FALSE;
		}
		return TRUE;
	}
	
	/**
	 * Translate if necessary:
	 * - controls texts
	 * - columns human names
	 * - columns url names (if configured)
	 * @return void
	 */
	protected function preDispatchTranslations () {
		if (!$this->translate) return;
		foreach ($this->controlsTexts as $key => $controlText)
			$this->controlsTexts[$key] = call_user_func_array(
				$this->translator, [$controlText, ['{0}', '{1}']]
			);
		foreach ($this->configColumns as $configColumn) {
			$configColumn->SetHeadingName(
				call_user_func_array(
					$this->translator, [$configColumn->GetHeadingName()]
				)
			);
			$title = $configColumn->GetTitle();
			if ($title !== NULL) {
				$configColumn->SetTitle(
					call_user_func_array(
						$this->translator, [$title]
					)
				);
			}
		}
	}

	/**
	 * Initialize paging control render config boolean and paging content.
	 * @return void
	 */
	protected function preDispatchPaging () {
		$renderPaging = $this->configRendering->GetRenderControlPaging();
		if (!$renderPaging) return;

		$multiplePages = $this->totalCount > $this->count && $this->count !== 0;
		if (($renderPaging & \MvcCore\Ext\Controllers\IDataGrid::CONTROL_DISPLAY_IF_NECESSARY) != 0 && !$multiplePages) {
			$this->configRendering->SetRenderControlPaging(static::CONTROL_DISPLAY_NEVER);
			return;
		}
		
		$paging = [];
		$displayingCount = $this->count;
		if (
			$displayingCount === 0 && (
				$renderPaging & static::CONTROL_DISPLAY_ALWAYS
			) != 0
		) $displayingCount = $this->totalCount;

		$nearbyPages = $this->configRendering->GetControlPagingNearbyPagesCount();
		$outerPages = $this->configRendering->GetControlPagingOuterPagesCount();
		$prevAndNext = $this->configRendering->GetRenderControlPagingPrevAndNext();
		$firstAndLast = $this->configRendering->GetRenderControlPagingFirstAndLast();

		$this->pagesCount = intval(ceil($this->totalCount / $displayingCount));
		$currentPage = $this->intdiv($this->offset, $displayingCount) + 1;

		$displayPrev = $prevAndNext && $this->offset - $displayingCount >= 0;
		$displayFirst = $firstAndLast && $this->offset > $nearbyPages * $displayingCount;
		$displayNext = $prevAndNext && $this->offset + $displayingCount < $this->totalCount;
		$displayLast = $firstAndLast && $this->offset < ($this->pagesCount * $displayingCount) - (($nearbyPages + 1) * $displayingCount);
		
		$outerPagesMinRatio = $this->configRendering->GetControlPagingOuterPagesDisplayRatio();
		$hiddenStartingPagesCount = $currentPage - $nearbyPages - ($firstAndLast ? 2 : 1);
		$displayOuterStartPages = $outerPages && floatval($hiddenStartingPagesCount) / floatval($outerPages) > $outerPagesMinRatio;
		$hiddenEndingPagesCount = $this->pagesCount - ($currentPage + $nearbyPages) - ($firstAndLast ? 1 : 0);
		$displayOuterEndPages = $outerPages && (floatval($hiddenEndingPagesCount ) / floatval($outerPages)) > $outerPagesMinRatio;
		
		$this->preDispatchPagingPrevAndFirst($paging, [
			$displayingCount, $displayFirst, $displayPrev
		]);
		$this->preDispatchPagingLeftOuterPages($paging, [
			$displayingCount, $displayOuterStartPages, $firstAndLast, $outerPages, $hiddenStartingPagesCount
		]);
		$this->preDispatchPagingNearbyAndCurrent($paging, [
			$displayingCount, $currentPage, $nearbyPages
		]);
		$this->preDispatchPagingRightOuterPages($paging, [
			$displayingCount, $displayOuterEndPages, $firstAndLast, $outerPages, $hiddenEndingPagesCount
		]);
		$this->preDispatchPagingLastAndNext($paging, [
			$displayingCount, $displayLast, $displayNext
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
		list ($displayingCount, $displayFirst, $displayPrev) = $params;
		if ($displayPrev) {
			$offset = $this->offset - $displayingCount;
			$paging[] = (new \MvcCore\Ext\Controllers\DataGrids\Paging\Page(
				$offset,
				$this->GridPageUrl($offset), 
				$this->GetControlText('previous'), FALSE, TRUE
			))->SetIsPrev(TRUE);
		}
		if ($displayFirst) {
			$paging[] = (new \MvcCore\Ext\Controllers\DataGrids\Paging\Page(
				0,
				$this->GridPageUrl(0), 
				$this->GetControlText('first')
			))->SetIsFirst(TRUE);
		}
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
		list($displayingCount, $displayOuterStartPages, $firstAndLast, $outerPages, $hiddenStartingPagesCount) = $params;
		if ($displayOuterStartPages) {
			$stepValue = floatval($hiddenStartingPagesCount) / floatval($outerPages + 1);
			$stepCounter = $firstAndLast ? 1.0 : 0.0;
			for ($i = 0; $i < $outerPages; $i++) {
				$stepCounter += $stepValue;
				$pageIndex = intval(floor($stepCounter));
				if ($i > 0)
					$paging[] = new \MvcCore\Ext\Controllers\DataGrids\Paging\Dot;
				$offset = ($pageIndex - 1) * $displayingCount;
				$paging[] = new \MvcCore\Ext\Controllers\DataGrids\Paging\Page(
					$offset,
					$this->GridPageUrl($offset), 
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
		list($displayingCount, $currentPage, $nearbyPages) = $params;
		$beginIndex = max($currentPage - $nearbyPages, 1);
		$endIndex = min($currentPage + $nearbyPages + 1, $this->pagesCount + 1);

		$leftOverflowPagesCount = $currentPage - ($nearbyPages + 1);
		if ($leftOverflowPagesCount < 0)
			$endIndex = min($endIndex + abs($leftOverflowPagesCount), $this->pagesCount + 1);
		
		$rightOverflowPagesCount = $this->pagesCount - $currentPage - $nearbyPages;
		if ($rightOverflowPagesCount < 0)
			$beginIndex = max($beginIndex - abs($rightOverflowPagesCount), 1);

		for ($pageIndex = $beginIndex; $pageIndex < $endIndex; $pageIndex++) {
			$offset = ($pageIndex - 1) * $displayingCount;
			$paging[] = new \MvcCore\Ext\Controllers\DataGrids\Paging\Page(
				$offset,
				$this->GridPageUrl($offset), 
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
		list($displayingCount, $displayOuterEndPages, $firstAndLast, $outerPages, $hiddenEndingPagesCount) = $params;
		if ($displayOuterEndPages) {
			$paging[] = new \MvcCore\Ext\Controllers\DataGrids\Paging\Dots;
			$stepValue = floatval($hiddenEndingPagesCount) / floatval($outerPages + 1);
			$stepCounter = floatval($this->pagesCount - $hiddenEndingPagesCount - ($firstAndLast ? 1 : 0));
			for ($i = 0; $i < $outerPages; $i++) {
				$stepCounter += $stepValue;
				$pageIndex = intval(ceil($stepCounter));
				if ($i > 0)
					$paging[] = new \MvcCore\Ext\Controllers\DataGrids\Paging\Dot;
				$offset = ($pageIndex - 1) * $displayingCount;
				$paging[] = new \MvcCore\Ext\Controllers\DataGrids\Paging\Page(
					$offset,
					$this->GridPageUrl($offset), 
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
		list ($displayingCount, $displayLast, $displayNext) = $params;
		if ($displayNext || $displayLast)
			$paging[] = new \MvcCore\Ext\Controllers\DataGrids\Paging\Dots;
		if ($displayLast) {
			$offset = ($this->pagesCount - 1) * $displayingCount;
			$paging[] = (new \MvcCore\Ext\Controllers\DataGrids\Paging\Page(
				$offset,
				$this->GridPageUrl($offset), 
				str_replace('{0}', $this->pagesCount, $this->GetControlText('last'))
			))->SetIsLast(TRUE);
		}
		if ($displayNext) {
			$offset = $this->offset + $displayingCount;
			$paging[] = (new \MvcCore\Ext\Controllers\DataGrids\Paging\Page(
				$offset,
				$this->GridPageUrl($offset), 
				$this->GetControlText('next')
			))->SetIsNext(TRUE);
		}
	}
	
	/**
	 * Switch rendering config boolean to render count scales control to 
	 * never render, if datagrid renders only single page and items per page value
	 * is greater than zero (items per page value is not set to unlimited value).
	 * Because than - the count scales control is completely useless.
	 * @return void
	 */
	protected function preDispatchCountScales () {
		$renderCountScales = $this->configRendering->GetRenderControlCountScales();
		if (!$renderCountScales) return;
		$multiplePages = $this->totalCount > $this->count && $this->count !== 0;
		if (!$multiplePages && ($renderCountScales & static::CONTROL_DISPLAY_IF_NECESSARY) != 0) 
			$this->configRendering->SetRenderControlCountScales(static::CONTROL_DISPLAY_NEVER);
	}

	/**
	 * Switch necessary rendering config booleans to `FALSE`
	 * if sorting or filtering is completely disabled.
	 * @return void
	 */
	protected function preDispatchRenderConfig () {
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

	/**
	 * Merge all column css classes together before rendering.
	 * @return void
	 */
	protected function preDispatchColumnsConfigs () {
		$columnCssClassBase = $this->configRendering->GetType() === self::TYPE_TABLE 
			? 'grid-col-'
			: 'grid-item-';
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\Column $configColumn */
		$newConfigColumns = [];
		foreach ($this->configColumns as $urlName => $configColumn) {
			$cssClasses = $configColumn->GetCssClasses();
			$cssClasses[] = $columnCssClassBase . $configColumn->GetPropName();
			$configColumn->SetCssClasses([implode(' ', $cssClasses)]);
			$newConfigColumns[$urlName] = $configColumn;
		}
		$this->configColumns = new \MvcCore\Ext\Controllers\DataGrids\Iterators\Columns($newConfigColumns);
	}

	/**
	 * Execute `PreDispatch()` method on table head filter form 
	 * if necessary and set up translated controls texts.
	 * @return void
	 */
	protected function preDispatchTableHeadFilterForm () {
		if (
			$this->configRendering->GetRenderTableHeadFiltering() && 
			$this->tableHeadFilterForm !== NULL
		) {
			$form = $this->tableHeadFilterForm;
			$form->PreDispatch(FALSE);
			$submitFields = $form->GetSubmitFields();
			$delimiter = $form::HTML_IDS_DELIMITER;
			foreach ($submitFields as $submitField) {
				if (mb_strpos($submitField->GetName(), 'clear' . $delimiter) !== 0) continue;
				// set up translated text value:
				$submitField->SetValue($this->GetControlText('clear'));
			}
		}
	}
}
