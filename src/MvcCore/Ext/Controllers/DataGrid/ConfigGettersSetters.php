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
use A;

/**
 * @mixin \MvcCore\Ext\Controllers\DataGrid
 */
trait ConfigGettersSetters {
	
	/**
	 * @inheritDoc
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Models\IGridModel $model 
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetModel (\MvcCore\Ext\Controllers\DataGrids\Models\IGridModel $model) {
		$this->model = $model;
		return $this;
	}

	/**
	 * @inheritDoc
	 * @return \MvcCore\Ext\Controllers\DataGrids\Models\IGridModel|NULL
	 */
	public function GetModel () {
		return $this->model;
	}

	/**
	 * @inheritDoc
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Models\TGridRow|string|NULL $rowClass
	 * @param  int                                                            $rowClassPropsFlags
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetRowClass (/*\MvcCore\Ext\Controllers\DataGrids\Models\IGridRow*/ $rowClass, $propsFlags = 0) {
		$this->rowClass = $rowClass;
		if ($propsFlags > 0)
			$this->rowClassPropsFlags = $propsFlags;
		return $this;
	}

	/**
	 * @inheritDoc
	 * @return \MvcCore\Ext\Controllers\DataGrids\Models\TGridRow|string|NULL
	 */
	public function GetRowClass () {
		return $this->rowClass;
	}

	/**
	 * @inheritDoc
	 * @param  int $itemsPerPage
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetItemsPerPage ($itemsPerPage) {
		$this->itemsPerPage = $itemsPerPage;
		return $this;
	}

	/**
	 * @inheritDoc
	 * @return int
	 */
	public function GetItemsPerPage () {
		return $this->itemsPerPage;
	}

	/**
	 * @inheritDoc
	 * @param  \int[] $countScales
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetCountScales (array $countScales) {
		$this->countScales = $countScales;
		return $this;
	}

	/**
	 * @inheritDoc
	 * @return \int[]
	 */
	public function GetCountScales () {
		return $this->countScales;
	}
	
	/**
	 * @inheritDoc
	 * @param  bool $allowedCustomUrlCountScale
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetAllowedCustomUrlCountScale ($allowedCustomUrlCountScale) {
		$this->allowedCustomUrlCountScale = $allowedCustomUrlCountScale;
		return $this;
	}

	/**
	 * @inheritDoc
	 * @return bool
	 */
	public function GetAllowedCustomUrlCountScale () {
		return $this->allowedCustomUrlCountScale;
	}

	/**
	 * @inheritDoc
	 * @param  int $sortingMode 
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetSortingMode ($sortingMode = \MvcCore\Ext\Controllers\IDataGrid::SORT_MULTIPLE_COLUMNS) {
		$this->sortingMode = $sortingMode;
		return $this;
	}

	/**
	 * @inheritDoc
	 * @return int
	 */
	public function GetSortingMode () {
		return $this->sortingMode;
	}
	
	
	/**
	 * @inheritDoc
	 * @param  int $filteringMode 
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function AddFilteringMode ($filteringMode = \MvcCore\Ext\Controllers\IDataGrid::FILTER_ALLOW_LIKE_RIGHT_SIDE) {
		$this->filteringMode |= $filteringMode;
		return $this;
	}
	
	/**
	 * @inheritDoc
	 * @param  int $filteringMode 
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function RemoveFilteringMode ($filteringMode = \MvcCore\Ext\Controllers\IDataGrid::FILTER_ALLOW_LIKE_ANYWHERE) {
		$this->filteringMode = ~((~$this->filteringMode) | $filteringMode);
		return $this;
	}

	/**
	 * @inheritDoc
	 * @param  int $filteringMode 
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetFilteringMode ($filteringMode = \MvcCore\Ext\Controllers\IDataGrid::FILTER_ALLOW_DEFAULT) {
		$this->filteringMode = $filteringMode;
		return $this;
	}

	/**
	 * @inheritDoc
	 * @return int
	 */
	public function GetFilteringMode () {
		return $this->filteringMode;
	}
	
	/**
	 * @inheritDoc
	 * @param  array $sorting
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetSorting (array $sorting) {
		$this->sorting = $sorting;
		return $this;
	}
	
	/**
	 * @inheritDoc
	 * @return array
	 */
	public function GetSorting () {
		return $this->sorting;
	}
	
	/**
	 * @inheritDoc
	 * @param  array $filtering
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetFiltering (array $filtering) {
		$this->filtering = $filtering;
		return $this;
	}
	
	/**
	 * @inheritDoc
	 * @return array
	 */
	public function GetFiltering () {
		return $this->filtering;
	}
	
	/**
	 * @inheritDoc
	 * @param  bool $ignoreDisabledColumns
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetIgnoreDisabledColumns ($ignoreDisabledColumns) {
		$this->ignoreDisabledColumns = $ignoreDisabledColumns;
		return $this;
	}
	
	/**
	 * @inheritDoc
	 * @return bool
	 */
	public function GetIgnoreDisabledColumns () {
		return $this->ignoreDisabledColumns;
	}
	
	/**
	 * @inheritDoc
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Forms\IFilterForm|\MvcCore\Ext\IForm $filterForm
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetControlFilterForm (\MvcCore\Ext\Controllers\DataGrids\Forms\IFilterForm $filterForm) {
		$this->checkExtendedFormClasses();
		$formInterface = 'MvcCore\\Ext\\IForm';
		$toolClass = $this->application->GetToolClass();
		$toolClass::CheckClassInterface(get_class($filterForm), $formInterface, FALSE, TRUE);
		$this->controlFilterForm = $filterForm;
		return $this;
	}
	
	/**
	 * @inheritDoc
	 * @return \MvcCore\Ext\Controllers\DataGrids\Forms\IFilterForm|\MvcCore\Ext\IForm|NULL
	 */
	public function GetControlFilterForm () {
		return $this->controlFilterForm;
	}
	
	/**
	 * @inheritDoc
	 * @param  \MvcCore\Ext\ICache|FALSE|NULL $cache
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetCache ($cache) {
		$this->cache = $cache;
		return $this;
	}
	
	/**
	 * @inheritDoc
	 * @return \MvcCore\Ext\ICache|FALSE|NULL
	 */
	public function GetCache () {
		if ($this->cache === NULL) {
			$cache = FALSE;
			$cacheClassName = static::$cacheClass;
			if (class_exists($cacheClassName)) {
				/** @var \MvcCore\Ext\ICache|NULL $cache */
				$cache = $cacheClassName::GetStore();
				if ($cache === NULL)
					throw new \RuntimeException("Cache has not configured default store.");
				if (!$cache->GetEnabled())
					$cache = FALSE;
			}
			$this->cache = $cache;
		}
		return $this->cache;
	}
	
	/**
	 * @inheritDoc
	 * @param  callable|\Closure $translator
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetTranslator ($translator) {
		$this->translator = $translator;
		return $this;
	}
	
	/**
	 * @inheritDoc
	 * @return callable|\Closure|NULL
	 */
	public function GetTranslator () {
		return $this->translator;
	}
	
	/**
	 * @inheritDoc
	 * @param  bool $translateUrlNames
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetTranslateUrlNames ($translateUrlNames) {
		$this->translateUrlNames = $translateUrlNames;
		return $this;
	}
	
	/**
	 * @inheritDoc
	 * @return bool
	 */
	public function GetTranslateUrlNames () {
		return $this->translateUrlNames;
	}

	/**
	 * @inheritDoc
	 * @param  \MvcCore\Route|NULL $route
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetRoute (\MvcCore\IRoute $route) {
		/** @var \MvcCore\Route $route */
		$this->route = $route;
		return $this;
	}

	/**
	 * @inheritDoc
	 * @return \MvcCore\Route|NULL
	 */
	public function GetRoute () {
		if ($this->route === NULL) {
			$this->route = new \MvcCore\Route([
				'pattern'		=> $this->GetConfigUrlSegments()->GetRoutePattern(
					$this->sortingMode, $this->filteringMode
				),
				'defaults'		=> [
					static::URL_PARAM_PAGE		=> 1,
					static::URL_PARAM_COUNT		=> $this->GetItemsPerPage(),
				],
				'constraints'	=> [
					static::URL_PARAM_PAGE		=> '[0-9]+',
					static::URL_PARAM_COUNT		=> '[0-9]+',
					static::URL_PARAM_SORT		=> '[^/]+',
					static::URL_PARAM_FILTER	=> '[^/]+',
				]
			]);
			$this->route->SetRouter($this->router);
		}
		return $this->route;
	}

	/**
	 * Set application route name used to build 
	 * application url adresses, `self` by default.
	 * @param  string $appRouteName 
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetAppRouteName ($appRouteName) {
		$this->appRouteName = $appRouteName;
		return $this;
	}
	
	/**
	 * Get application route name used to build 
	 * application url adresses, `self` by default.
	 * @return string
	 */
	public function GetAppRouteName () {
		return $this->appRouteName;
	}

	/**
	 * @inheritDoc
	 * @param  array $urlParams
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetUrlParams (array $urlParams) {
		$this->urlParams = $urlParams;
		return $this;
	}

	/**
	 * @inheritDoc
	 * @return array
	 */
	public function GetUrlParams () {
		if ($this->urlParams === NULL) {
			$gridReq = $this->GetGridRequest();
			$route = $this->GetRoute();
			$matches = $route->Matches($gridReq);
			if ($matches === NULL) {
				$this->urlParams = [];
			} else {
				if (isset($matches[static::URL_PARAM_PAGE])) 
					$matches[static::URL_PARAM_PAGE] = intval($matches[static::URL_PARAM_PAGE]);
				if (isset($matches[static::URL_PARAM_COUNT])) 
					$matches[static::URL_PARAM_COUNT] = intval($matches[static::URL_PARAM_COUNT]);
				$this->urlParams = $matches;
			}
		}
		return $this->urlParams;
	}

	/**
	 * @inheritDoc
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments $configUrlSegments
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetConfigUrlSegments (\MvcCore\Ext\Controllers\DataGrids\Configs\IUrlSegments $configUrlSegments) {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments $configUrlSegments */
		$this->configUrlSegments = $configUrlSegments;
		return $this;
	}

	/**
	 * @inheritDoc
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function GetConfigUrlSegments () {
		if ($this->configUrlSegments === NULL)
			$this->configUrlSegments = new \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments;
		return $this->configUrlSegments;
	}
	
	/**
	 * @inheritDoc
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $configRendering
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetConfigRendering (\MvcCore\Ext\Controllers\DataGrids\Configs\IRendering $configRendering) {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $configRendering */
		$this->configRendering = $configRendering;
		return $this;
	}

	/**
	 * @inheritDoc
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function GetConfigRendering () {
		if ($this->configRendering === NULL)
			$this->configRendering = new \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering;
		return $this->configRendering;
	}

	/**
	 * @inheritDoc
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\Column[]|\MvcCore\Ext\Controllers\DataGrids\Iterators\Columns $configRendering
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetConfigColumns ($configColumns) {
		$throwInvalidTypeError = FALSE;
		if ($configColumns instanceof \MvcCore\Ext\Controllers\DataGrids\Iterators\Columns) {
			$this->configColumns = $configColumns;	
		} else if (is_array($configColumns)) {
			$configColumnsArr = $this->configColumnsCompleteMissing(
				$configColumns, $throwInvalidTypeError
			);
			if ($this->translate)
				$configColumnsArr = $this->configColumnsTranslate($configColumnsArr);
			$this->configColumnsValidateNames($configColumnsArr);
			if (!$throwInvalidTypeError)
				$this->configColumns = new \MvcCore\Ext\Controllers\DataGrids\Iterators\Columns(
					$configColumnsArr
				);
			if ($cache = $this->GetCache()) {
				list ($cacheKey, $cacheTags) = $this->GetGridCacheKeyAndTags();
				$cache->Save($cacheKey, $this->configColumns, NULL, $cacheTags);
			}
		} else {
			$throwInvalidTypeError = TRUE;
		}
		if ($throwInvalidTypeError) throw new \InvalidArgumentException(
			"Datagrid column configuration has to be iterator ".
			"`\\MvcCore\\Ext\\Controllers\\DataGrids\\Iterators\\Columns` ".
			" or array of types `\\MvcCore\\Ext\\Controllers\\DataGrids\\Configs\\Column`."
		);
		return $this;
	}

	/**
	 * @inheritDoc
	 * @param  bool $activeOnly `TRUE` by default.
	 * @throws \RuntimeException|\InvalidArgumentException
	 * @return \MvcCore\Ext\Controllers\DataGrids\Iterators\Columns|NULL
	 */
	public function GetConfigColumns ($activeOnly = TRUE) {
		if ($this->configColumns === NULL) {
			if ($cache = $this->GetCache()) {
				list ($cacheKey, $cacheTags) = $this->GetGridCacheKeyAndTags();
				$this->configColumns = $cache->Load(
					$cacheKey, 
					function (\MvcCore\Ext\ICache $cache, string $cacheKey) use (& $cacheTags) {
						$this->configColumnsParseTranslateValidate();
						$cache->Save($cacheKey, $this->configColumns, NULL, $cacheTags);
						return $this->configColumns;
					}
				);
			} else {
				$this->configColumnsParseTranslateValidate();
			}
		}
		if ($activeOnly) {
			$activeConfigColumns = [];
			foreach ($this->configColumns->GetArray() as $urlName => $configColumn) {
				if ($configColumn->GetDisabled()) continue;
				$activeConfigColumns[$urlName] = $configColumn;
			}
			return new \MvcCore\Ext\Controllers\DataGrids\Iterators\Columns(
				$activeConfigColumns
			);
		} else {
			return $this->configColumns;
		}
	}

	/**
	 * @inheritDoc
	 * @param  array $controlsTexts
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetControlsTexts ($controlsTexts) {
		$this->controlsTexts = $controlsTexts;
		return $this;
	}
	
	/**
	 * @inheritDoc
	 * @return array
	 */
	public function GetControlsTexts () {
		return $this->controlsTexts;
	}
	
	/**
	 * @inheritDoc
	 * @return string|NULL
	 */
	public function GetControlText ($textKey) {
		if (isset($this->controlsTexts[$textKey]))
			return $this->controlsTexts[$textKey];
		return NULL;
	}
	
	/**
	 * @inheritDoc
	 * @param  \MvcCore\Ext\Form $tableHeadFilterForm
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetTableHeadFilterForm ($tableHeadFilterForm) {
		$this->tableHeadFilterForm = $tableHeadFilterForm;
		return $this;
	}
	
	/**
	 * @inheritDoc
	 * @return \MvcCore\Ext\Form
	 */
	public function GetTableHeadFilterForm () {
		if ($this->tableHeadFilterForm === NULL) 
			$this->createTableHeadFilterForm(FALSE);
		return $this->tableHeadFilterForm;
	}

	/**
	 * @inheritDoc
	 * @return string
	 */
	public function GetFilterFormValuesDelimiter () {
		return $this->filterFormValuesDelimiter;
	}
	
	/**
	 * @inheritDoc
	 * @param  string $filterFormValuesDelimiter
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetFilterFormValuesDelimiter ($filterFormValuesDelimiter) {
		$this->filterFormValuesDelimiter = $filterFormValuesDelimiter;
		return $this;
	}

	/**
	 * @inheritDoc
	 * @return \MvcCore\Ext\Controllers\DataGrids\IClientRowModelDefinitionHandler|callable|NULL
	 */
	public function GetHandlerClientRowModelDefinition () {
		return $this->handlerClientRowModelDefinition;
	}

	/**
	 * @inheritDoc
	 * @param  \MvcCore\Ext\Controllers\DataGrids\IClientRowModelDefinitionHandler|callable|NULL $handlerClientRowModelDefinition
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetHandlerClientRowModelDefinition ($handlerClientRowModelDefinition) {
		$this->handlerClientRowModelDefinition = $handlerClientRowModelDefinition;
		return $this;
	}
	
	/**
	 * @inheritDoc
	 * @return array<string, int[]>
	 */
	public function GetTypesPossibleFilterFlags () {
		return $this->typesPossibleFilterFlags;
	}
	
	/**
	 * @inheritDoc
	 * @param  array<string, int[]> $typesPossibleFilterFlags
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetTypesPossibleFilterFlags ($typesPossibleFilterFlags) {
		$this->typesPossibleFilterFlags = $typesPossibleFilterFlags;
		return $this;
	}
	
	/**
	 * @inheritDoc
	 * @param  string|\string[] $cssClasses
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function AddCssClasses ($cssClasses) {
		$cssClassesArr = is_array($cssClasses)
			? $cssClasses
			: explode(' ', (string) $cssClasses);
		$this->cssClasses = array_merge($this->cssClasses, $cssClassesArr);
		return $this;
	}
	
	/**
	 * @inheritDoc
	 * @param  string|\string[] $cssClasses
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetCssClasses ($cssClasses) {
		$cssClassesArr = is_array($cssClasses)
			? $cssClasses
			: explode(' ', (string) $cssClasses);
		$this->cssClasses = $cssClassesArr;
		return $this;
	}
	
	/**
	 * @inheritDoc
	 * @return \string[]
	 */
	public function GetCssClasses () {
		return $this->cssClasses;
	}

	/**
	 * @inheritDoc
	 * @param  array|array<string,string> $containerAttrs
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function AddContainerAttrs ($containerAttrs) {
		$this->containerAttrs = array_merge($this->containerAttrs, $containerAttrs);
		return $this;
	}
	
	/**
	 * @inheritDoc
	 * @param  array|array<string,string> $containerAttrs
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetContainerAttrs ($containerAttrs) {
		$this->containerAttrs = $containerAttrs;
		return $this;
	}

	/**
	 * @inheritDoc
	 * @return array|array<string,string>
	 */
	public function GetContainerAttrs () {
		return $this->containerAttrs;
	}


}