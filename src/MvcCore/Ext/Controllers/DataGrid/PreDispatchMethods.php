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
	 * Third argument is access mod flags to load model instance properties.
	 * If value is zero, there are used all access mode flags - private, protected and public.
	 * 
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Models\IGridModel|string $rowModelOrFullClassName
	 * @param  array                                                       $rowModelMetaData
	 * @param  int                                                         $rowModelAccessModFlags
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column[]
	 */
	public static function ParseConfigColumns (
		$rowModelOrFullClassName, 
		$rowModelMetaData = [], 
		$rowModelAccessModFlags = 0
	) {
		$props = static::parseConfigColumnsGetProps(
			$rowModelOrFullClassName, $rowModelAccessModFlags
		);
		$app = \MvcCore\Application::GetInstance();
		$attrsAnotations = $app->GetAttributesAnotations();
		/** @var string|\MvcCore\Tool $toolClass */
		$toolClass = $app->GetToolClass();
		$columnConfigs = [];
		$naturalSort = [];
		$indexSort = [];
		foreach ($props as $index => $prop) {
			$columnConfig = static::parseConfigColumn(
				$prop, $index, $rowModelMetaData, $attrsAnotations, $toolClass
			);
			if ($columnConfig === NULL) continue;
			$urlName = $columnConfig->GetUrlName();
			$columnIndex = $columnConfig->GetColumnIndex();
			$columnConfigs[$urlName] = $columnConfig;
			if ($columnIndex === NULL) {
				$naturalSort[] = $urlName;
			} else {
				if (!isset($indexSort[$columnIndex]))
					$indexSort[$columnIndex] = [];
				$indexSort[$columnIndex][] = $urlName;
			}
		}
		if (count($indexSort) === 0) {
			return $columnConfigs;
		} else {
			return static::parseConfigColumnSort(
				$columnConfigs, $naturalSort, $indexSort
			);
		}
	}

	/**
	 * Get model reflection properties by model instance and access mod flags.
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Models\IGridModel|string $rowModelOrFullClassName 
	 * @param  int                                                         $accesModFlags
	 * @return \ReflectionProperty[]
	 */
	protected static function parseConfigColumnsGetProps ($rowModelOrFullClassName, $accesModFlags) {
		$modelType = new \ReflectionClass($rowModelOrFullClassName);
		// `$accesModFlags` could contain foreing flags from model
		$localFlags = 0;
		if (($accesModFlags & \ReflectionProperty::IS_PRIVATE)	!= 0) $localFlags |= \ReflectionProperty::IS_PRIVATE;
		if (($accesModFlags & \ReflectionProperty::IS_PROTECTED)!= 0) $localFlags |= \ReflectionProperty::IS_PROTECTED;
		if (($accesModFlags & \ReflectionProperty::IS_PUBLIC)	!= 0) $localFlags |= \ReflectionProperty::IS_PUBLIC;
		return $localFlags === 0
			? $modelType->getProperties()
			: $modelType->getProperties($localFlags);
	}

	/**
	 * Complete datagrid column config instance or `NULL`.
	 * @param  \ReflectionProperty  $prop
	 * @param  int                  $index
	 * @param  array                $modelMetaData
	 * @param  bool                 $attrsAnotations
	 * @param  string|\MvcCore\Tool $toolClass
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column|NULL
	 */
	protected static function parseConfigColumn (
		\ReflectionProperty $prop, $index, $modelMetaData, $attrsAnotations, $toolClass
	) {
		if ($prop->isStatic()) NULL;
		$attrClassFullName = static::$attrClassFullName;
		if ($attrsAnotations) {
			$attrClassNoFirstSlash = ltrim($attrClassFullName, '\\');
			$args = $toolClass::GetAttrCtorArgs($prop, $attrClassNoFirstSlash);
		} else {
			$tagName = $attrClassFullName::PHP_DOCS_TAG_NAME;
			$args = $toolClass::GetPhpDocsTagArgs($prop, $tagName);
			if (is_array($args) && count($args) === 2) {
				$attrClassName = basename(str_replace('\\', '/', $attrClassFullName));
				if ($args[0] === $attrClassName) 
					$args = (array) $args[1];
			}
		}

		if (!is_array($args)) $args = [];
		$propName = $prop->name;
		$args['propName'] = $propName;
		$allowNull = NULL;
		$types = NULL;
		if (isset($modelMetaData[$propName])) {
			list(
				$args['dbColumnName'], $allowNull, $types, $format
			) = $modelMetaData[$propName];
			if (!isset($args['types']) && $types !== NULL) $args['types'] = $types;
			if (!isset($args['format']) && $format !== NULL) $args['format'] = $format;
		}
		if ($args === NULL || ($args !== NULL && !isset($args['dbColumnName']))) return NULL;
		/** @var \ReflectionClass $columnType */
		/** @var \ReflectionParameter[] $ctorParams */
		list ($columnType, $ctorParams, $phpWithTypes, $phpWithUnionTypes) = static::getAttrClassReflObjects();
		$typesNotSet = !isset($args['types']);
		if ($allowNull === NULL || $typesNotSet) {
			list($types, $allowNull) = static::parseConfigColumnTypes($prop, $phpWithTypes, $phpWithUnionTypes);
			if ($typesNotSet) $args['types'] = $types;
		}
		if (isset($args['filter']) && $allowNull) {
			$filter = $args['filter'];
			if (is_int($filter)) {
				$filter |= self::FILTER_ALLOW_NULL;
			} else if ($filter === TRUE) {
				$filter = self::FILTER_ALLOW_ALL;
			}
			$args['filter'] = $filter;
		}
		$ctorArgs = [];
		foreach ($ctorParams as $index => $ctorParam) 
			$ctorArgs[$index] = isset($args[$ctorParam->name])
				? $args[$ctorParam->name]
				: NULL;
		return $columnType->newInstanceArgs($ctorArgs);
	}


	/**
	 * Get property types array and `TRUE` if property allow `NULL` values.
	 * @param  \ReflectionProperty $prop 
	 * @param  bool                $phpWithTypes 
	 * @param  bool                $phpWithUnionTypes 
	 * @return array               [\string[], bool]
	 */
	protected static function parseConfigColumnTypes (\ReflectionProperty $prop, $phpWithTypes, $phpWithUnionTypes) {
		$phpWithTypes = PHP_VERSION_ID >= 70400;
		$phpWithUnionTypes = PHP_VERSION_ID >= 80000;
		if ($phpWithTypes && $prop->hasType()) {
			/** @var $reflType \ReflectionUnionType|\ReflectionNamedType */
			$refType = $prop->getType();
			if ($refType !== NULL) {
				if ($phpWithUnionTypes && $refType instanceof \ReflectionUnionType) {
					$refTypes = $refType->getTypes();
					/** @var \ReflectionNamedType $refTypesItem */
					$strIndex = NULL;
					foreach ($refTypes as $index => $refTypesItem) {
						$typeName = $refTypesItem->getName();
						if ($strIndex === NULL && $typeName === 'string')
							$strIndex = $index;
						if ($typeName !== 'null')
							$types[] = $typeName;
					}
					if ($strIndex !== NULL) {
						unset($types[$strIndex]);
						$types = array_values($types);
						$types[] = 'string';
					}
				} else {
					$types = [$refType->getName()];
				}
				$allowNull = $refType->allowsNull();
			}
		} else {
			preg_match('/@var\s+([^\s]+)/', $prop->getDocComment(), $matches);
			if ($matches) {
				$rawTypes = '|'.$matches[1].'|';
				$nullPos = mb_stripos($rawTypes,'|null|');
				$qmPos = mb_strpos($rawTypes, '?');
				$qmMatched = $qmPos !== FALSE;
				$nullMatched = $nullPos !== FALSE;
				$allowNull = $qmMatched || $nullMatched;
				if ($qmMatched) 
					$rawTypes = str_replace('?', '', $rawTypes);
				if ($nullMatched)
					$rawTypes = (
						mb_substr($rawTypes, 0, $nullPos) . 
						mb_substr($rawTypes, $nullPos + 5)
					);
				$rawTypes = mb_substr($rawTypes, 1, mb_strlen($rawTypes) - 2);
				$types = explode('|', $rawTypes);
			}
		}
		return [$types, $allowNull];
	}

	/**
	 * Return cached reflection class for column config and it's constructor arguments array.
	 * @return array [\ReflectionClass, \ReflectionParameter[], bool, bool]
	 */
	protected static function getAttrClassReflObjects () {
		static $__attrClassReflObjects = NULL;
		if ($__attrClassReflObjects === NULL) {
			$columnType = new \ReflectionClass(static::$attrClassFullName);
			$__attrClassReflObjects = [
				$columnType,
				$columnType->getConstructor()->getParameters(),
				PHP_VERSION_ID >= 70400, // $phpWithTypes
				PHP_VERSION_ID >= 80000, // $phpWithUnionTypes
			];
		}
		return $__attrClassReflObjects;
	}

	/**
	 * Sort config colums by optional grid column index.
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\Column[] $columnConfigs 
	 * @param  \string[]                                           $naturalSort 
	 * @param  array                                               $indexSort 
	 * @return array
	 */
	protected static function parseConfigColumnSort (array $columnConfigs, array $naturalSort, array $indexSort) {
		$result = [];
		ksort($indexSort);
		foreach ($indexSort as $columnIndex => $indexedUrlNames) 
			array_splice($naturalSort, $columnIndex, 0, $indexedUrlNames);
		foreach ($naturalSort as $urlName) 
			$result[$urlName] = $columnConfigs[$urlName];
		return $result;
	}

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
		
		$this->preDispatchTranslations();
		$this->preDispatchTotalCount();
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
			->SetFiltering($this->filtering)
			->SetSorting($this->sorting);
		$this->totalCount = $model->GetTotalCount();
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
		$pagesCountByTotalCount = ($this->itemsPerPage > 0) 
			? intval(ceil(floatval($this->totalCount) / floatval($this->itemsPerPage))) 
			: 0 ;
		// If user write to large page number to URL, redirect user to the last page.
		$page = $this->urlParams[static::URL_PARAM_PAGE];
		if ($page > $pagesCountByTotalCount && $pagesCountByTotalCount > 0) {
			/** @var \MvcCore\Controller $context */
			$context = $this;
			$redirectUrl = $this->GridUrl([
				static::URL_PARAM_PAGE	=> $pagesCountByTotalCount,
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

		$this->pagesCount = intval(ceil($this->totalCount / $itemsPerPage));
		$currentPage = $this->intdiv($this->offset, $itemsPerPage) + 1;

		$displayPrev = $prevAndNext && $this->offset - $itemsPerPage >= 0;
		$displayFirst = $firstAndLast && $this->offset > $nearbyPages * $itemsPerPage;
		$displayNext = $prevAndNext && $this->offset + $itemsPerPage < $this->totalCount;
		$displayLast = $firstAndLast && $this->offset < ($this->pagesCount * $itemsPerPage) - (($nearbyPages + 1) * $itemsPerPage);
		
		$outerPagesMinRatio = $this->configRendering->GetControlPagingOuterPagesDisplayRatio();
		$hiddenStartingPagesCount = $currentPage - $nearbyPages - ($firstAndLast ? 2 : 1);
		$displayOuterStartPages = $outerPages && floatval($hiddenStartingPagesCount) / floatval($outerPages) > $outerPagesMinRatio;
		$hiddenEndingPagesCount = $this->pagesCount - ($currentPage + $nearbyPages) - ($firstAndLast ? 1 : 0);
		$displayOuterEndPages = $outerPages && (floatval($hiddenEndingPagesCount ) / floatval($outerPages)) > $outerPagesMinRatio;
		
		$this->preDispatchPagingPrevAndFirst($paging, [
			$itemsPerPage, $displayFirst, $displayPrev
		]);
		$this->preDispatchPagingLeftOuterPages($paging, [
			$itemsPerPage, $displayOuterStartPages, $firstAndLast, $outerPages, $hiddenStartingPagesCount
		]);
		$this->preDispatchPagingNearbyAndCurrent($paging, [
			$itemsPerPage, $currentPage, $nearbyPages
		]);
		$this->preDispatchPagingRightOuterPages($paging, [
			$itemsPerPage, $displayOuterEndPages, $firstAndLast, $outerPages, $hiddenEndingPagesCount
		]);
		$this->preDispatchPagingLastAndNext($paging, [
			$itemsPerPage, $displayLast, $displayNext
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
		list($itemsPerPage, $currentPage, $nearbyPages) = $params;
		$beginIndex = max($currentPage - $nearbyPages, 1);
		$endIndex = min($currentPage + $nearbyPages + 1, $this->pagesCount + 1);

		$leftOverflowPagesCount = $currentPage - ($nearbyPages + 1);
		if ($leftOverflowPagesCount < 0)
			$endIndex = min($endIndex + abs($leftOverflowPagesCount), $this->pagesCount + 1);
		
		$rightOverflowPagesCount = $this->pagesCount - $currentPage - $nearbyPages;
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
		list($itemsPerPage, $displayOuterEndPages, $firstAndLast, $outerPages, $hiddenEndingPagesCount) = $params;
		if ($displayOuterEndPages) {
			$paging[] = new \MvcCore\Ext\Controllers\DataGrids\Paging\Dots;
			$stepValue = floatval($hiddenEndingPagesCount) / floatval($outerPages + 1);
			$stepCounter = floatval($this->pagesCount - $hiddenEndingPagesCount - ($firstAndLast ? 1 : 0));
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
		list ($itemsPerPage, $displayLast, $displayNext) = $params;
		if ($displayNext || $displayLast)
			$paging[] = new \MvcCore\Ext\Controllers\DataGrids\Paging\Dots;
		if ($displayLast) 
			$paging[] = (new \MvcCore\Ext\Controllers\DataGrids\Paging\Page(
				$this->GridPageUrl(($this->pagesCount - 1) * $itemsPerPage), 
				str_replace('{0}', $this->pagesCount, $this->GetControlText('last'))
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
