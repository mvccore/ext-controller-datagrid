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
trait ConfigGettersSetters {
	
	/**
	 * @inheritDocs
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Models\IGridModel $model 
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetModel (\MvcCore\Ext\Controllers\DataGrids\Models\IGridModel $model) {
		$this->model = $model;
		$this->GetModel(TRUE);// validate
		return $this;
	}

	/**
	 * @inheritDocs
	 * @throws \InvalidArgumentException
	 * @return \MvcCore\Ext\Controllers\DataGrids\Models\IGridModel|NULL
	 */
	public function GetModel ($throwExceptionIfNull = FALSE) {
		if (
			($this->model === NULL && $throwExceptionIfNull) || 
			($this->model !== NULL && !($this->model instanceof \MvcCore\Ext\Controllers\DataGrids\Models\IGridModel))
		) throw new \InvalidArgumentException("No datagrid model defined or model doesn't implement `\\MvcCore\\Ext\\Controllers\\DataGrids\\Models\\IGridModel`.");
		return $this->model;
	}

	/**
	 * @inheritDocs
	 * @param  int $itemsPerPage
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetItemsPerPage ($itemsPerPage) {
		$this->itemsPerPage = $itemsPerPage;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return int
	 */
	public function GetItemsPerPage () {
		return $this->itemsPerPage;
	}

	/**
	 * @inheritDocs
	 * @param  \int[] $countScales
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetCountScales (array $countScales) {
		$this->countScales = $countScales;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return \int[]
	 */
	public function GetCountScales () {
		return $this->countScales;
	}
	
	/**
	 * @inheritDocs
	 * @param  bool $allowedCustomUrlCountScale
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetAllowedCustomUrlCountScale ($allowedCustomUrlCountScale) {
		$this->allowedCustomUrlCountScale = $allowedCustomUrlCountScale;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return bool
	 */
	public function GetAllowedCustomUrlCountScale () {
		return $this->allowedCustomUrlCountScale;
	}

	/**
	 * @inheritDocs
	 * @param  int $sortingMode 
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetSortingMode ($sortingMode = \MvcCore\Ext\Controllers\IDataGrid::SORT_MULTIPLE_COLUMNS) {
		$this->sortingMode = $sortingMode;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return int
	 */
	public function GetSortingMode () {
		return $this->sortingMode;
	}
	

	/**
	 * @inheritDocs
	 * @param  int $filteringMode 
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetFilteringMode ($filteringMode = \MvcCore\Ext\Controllers\IDataGrid::FILTER_MULTIPLE_COLUMNS) {
		$this->filteringMode = $filteringMode;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return int
	 */
	public function GetFilteringMode () {
		return $this->filteringMode;
	}
	
	/**
	 * @inheritDocs
	 * @param  array $sorting
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetSorting (array $sorting) {
		$this->sorting = $sorting;
		return $this;
	}
	
	/**
	 * @inheritDocs
	 * @return array
	 */
	public function GetSorting () {
		return $this->sorting;
	}
	
	/**
	 * @inheritDocs
	 * @param  array $filtering
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetFiltering (array $filtering) {
		$this->filtering = $filtering;
		return $this;
	}
	
	/**
	 * @inheritDocs
	 * @return array
	 */
	public function GetFiltering () {
		return $this->filtering;
	}
	
	/**
	 * @inheritDocs
	 * @param  bool $ignoreDisabledColumns
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetIgnoreDisabledColumns ($ignoreDisabledColumns) {
		$this->ignoreDisabledColumns = $ignoreDisabledColumns;
		return $this;
	}
	
	/**
	 * @inheritDocs
	 * @return bool
	 */
	public function GetIgnoreDisabledColumns () {
		return $this->ignoreDisabledColumns;
	}
	
	/**
	 * @inheritDocs
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Forms\IFilterForm|\MvcCore\Ext\IForm $translator
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
	 * @inheritDocs
	 * @return \MvcCore\Ext\Controllers\DataGrids\Forms\IFilterForm|\MvcCore\Ext\IForm|NULL
	 */
	public function GetControlFilterForm () {
		return $this->controlFilterForm;
	}
	
	/**
	 * @inheritDocs
	 * @param  callable|\Closure $translator
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetTranslator ($translator) {
		$this->translator = $translator;
		return $this;
	}
	
	/**
	 * @inheritDocs
	 * @return callable|\Closure|NULL
	 */
	public function GetTranslator () {
		return $this->translator;
	}
	
	/**
	 * @inheritDocs
	 * @param  bool $translateUrlNames
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetTranslateUrlNames ($translateUrlNames) {
		$this->translateUrlNames = $translateUrlNames;
		return $this;
	}
	
	/**
	 * @inheritDocs
	 * @return bool
	 */
	public function GetTranslateUrlNames () {
		return $this->translateUrlNames;
	}

	/**
	 * @inheritDocs
	 * @param  \MvcCore\Route|NULL $route
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetRoute (\MvcCore\IRoute $route) {
		/** @var \MvcCore\Route $route */
		$this->route = $route;
		return $this;
	}

	/**
	 * @inheritDocs
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
	 * @inheritDocs
	 * @param  array $urlParams
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetUrlParams (array $urlParams) {
		$this->urlParams = $urlParams;
		return $this;
	}

	/**
	 * @inheritDocs
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
	 * @inheritDocs
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments $configUrlSegments
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetConfigUrlSegments (\MvcCore\Ext\Controllers\DataGrids\Configs\IUrlSegments $configUrlSegments) {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments $configUrlSegments */
		$this->configUrlSegments = $configUrlSegments;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function GetConfigUrlSegments () {
		if ($this->configUrlSegments === NULL)
			$this->configUrlSegments = new \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments;
		return $this->configUrlSegments;
	}
	
	/**
	 * @inheritDocs
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $configRendering
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetConfigRendering (\MvcCore\Ext\Controllers\DataGrids\Configs\IRendering $configRendering) {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $configRendering */
		$this->configRendering = $configRendering;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function GetConfigRendering () {
		if ($this->configRendering === NULL)
			$this->configRendering = new \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering;
		return $this->configRendering;
	}

	/**
	 * @inheritDocs
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\Column[]|\MvcCore\Ext\Controllers\DataGrids\Iterators\Columns $configRendering
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetConfigColumns ($configColumns) {
		$throwInvalidTypeError = FALSE;
		if ($configColumns instanceof \MvcCore\Ext\Controllers\DataGrids\Iterators\Columns) {
			$this->configColumns = $configColumns;	
		} else if (is_array($configColumns)) {
			/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\Column $configColumn */
			$configColumnsByUrlNames = [];
			foreach ($configColumns as $index => $configColumn) {
				if ($configColumn instanceof \MvcCore\Ext\Controllers\DataGrids\Configs\IColumn) {
					$propName = $configColumn->GetPropName();
					if ($propName === NULL) throw new \InvalidArgumentException(
						"Datagrid column configuration item requires `propName` (index: {$index})."
					);
					$urlName = $configColumn->GetUrlName();
					if ($urlName === NULL) $urlName = $propName;
					$urlNameTranslated = $this->translateUrlNames
						? call_user_func_array($this->translator, [$urlName])
						: $urlName;
					$configColumn->SetUrlName($urlNameTranslated);
					$dbColumnName = $configColumn->GetDbColumnName();
					if ($dbColumnName === NULL) 
						$configColumn->SetDbColumnName($propName);
					$headingName = $configColumn->GetHeadingName();
					if ($headingName === NULL) 
						$configColumn->SetHeadingName($propName);
					$configColumnsByUrlNames[$urlNameTranslated] = $configColumn;
				} else {
					$throwInvalidTypeError = TRUE;
					break;
				}
			}
			if (!$throwInvalidTypeError)
				$this->configColumns = new \MvcCore\Ext\Controllers\DataGrids\Iterators\Columns(
					$configColumnsByUrlNames
				);
		} else {
			$throwInvalidTypeError = TRUE;
		}
		if ($throwInvalidTypeError) throw new \InvalidArgumentException(
			"Datagrid column configuration has to be array of types ".
			"`\\MvcCore\\Ext\\Controllers\\DataGrids\\Configs\\Column` or iterator ".
			"`\\MvcCore\\Ext\\Controllers\\DataGrids\\Iterators\\Columns`."
		);
		return $this;
	}

	/**
	 * @inheritDocs
	 * @param  bool $activeOnly `TRUE` by default.
	 * @return \MvcCore\Ext\Controllers\DataGrids\Iterators\Columns|NULL
	 */
	public function GetConfigColumns ($activeOnly = TRUE) {
		if ($this->configColumns === NULL) {
			$model = $this->GetModel(TRUE);
			if ($model instanceof \MvcCore\Ext\Controllers\DataGrids\Models\IGridColumns) {
				/** @var \MvcCore\Ext\Controllers\DataGrids\Models\GridColumns $model */
				$configColumnsArr = $model->GetConfigColumns();
			} else {
				$context = $this;
				$configColumnsArr = $context::ParseConfigColumns($this->model);
			}
			if (is_array($configColumnsArr) && count($configColumnsArr) > 0) {
				$configColumnsUrlNames = array_keys($configColumnsArr);
				$configColumnsUrlNamesStr = implode("\n", $configColumnsUrlNames);
				$notAllowedCharsInUrlNames = [
					$this->configUrlSegments->GetUrlDelimiterSubjectValue(),
					$this->configUrlSegments->GetUrlDelimiterSubjects(),
				];
				foreach ($notAllowedCharsInUrlNames as $notAllowedCharInUrlNames) {
					if (mb_strpos($configColumnsUrlNamesStr, $notAllowedCharInUrlNames) !== FALSE) {
						foreach ($configColumnsUrlNames as $configColumnsUrlName) {
							if (mb_strpos($configColumnsUrlName, $notAllowedCharInUrlNames) !== FALSE) {
								throw new \InvalidArgumentException(
									"Datagrid column configuration url name `{$configColumnsUrlName}` ".
									"contains not allowed grid url segment character `{$notAllowedCharInUrlNames}`. ".
									"Try to configure different grid url segment or different property url name."
								);
							}
						}
					}
				}
				if ($this->translateUrlNames) {
					$configColumnsArrTranslated = [];
					foreach ($configColumnsArr as $urlName => $configColumn) {
						$urlNameTranslated = call_user_func_array($this->translator, [$urlName]);
						$configColumnsArrTranslated[$urlNameTranslated] = $configColumn->SetUrlName($urlNameTranslated);;
					}
					$configColumnsArr = $configColumnsArrTranslated;
				}
				$this->configColumns = new \MvcCore\Ext\Controllers\DataGrids\Iterators\Columns(
					$configColumnsArr
				);
			} else {
				throw new \InvalidArgumentException(
					"There was not possible to complete datagrid columns from given model instance. \n".
					"- 1. You can use datagrid setter method `SetConfigColumns()` to directly configure datagrid columns or \n".
					"- 2. You can implement interface `\\MvcCore\\Ext\\Controllers\\DataGrids\\Models\\IGridColumns` \n".
					"  on given model instance and decorate model properties with attribute class \n".
					"  `\\MvcCore\\Ext\\Controllers\\DataGrids\\Configs\\Column` ".
					"  or with equivalent PHPDocs tag names."
				);
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
	 * @inheritDocs
	 * @param  array $controlsTexts
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetControlsTexts ($controlsTexts) {
		$this->controlsTexts = $controlsTexts;
		return $this;
	}
	
	/**
	 * @inheritDocs
	 * @return array
	 */
	public function GetControlsTexts () {
		return $this->controlsTexts;
	}
	
	/**
	 * @inheritDocs
	 * @return string|NULL
	 */
	public function GetControlText ($textKey) {
		if (isset($this->controlsTexts[$textKey]))
			return $this->controlsTexts[$textKey];
		return NULL;
	}
	
	/**
	 * @inheritDocs
	 * @param  \MvcCore\Ext\Form $tableHeadFilterForm
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetTableHeadFilterForm ($tableHeadFilterForm) {
		$this->tableHeadFilterForm = $tableHeadFilterForm;
		return $this;
	}
	
	/**
	 * @inheritDocs
	 * @return \MvcCore\Ext\Form
	 */
	public function GetTableHeadFilterForm () {
		if ($this->tableHeadFilterForm === NULL) 
			$this->createTableHeadFilterForm(FALSE);
		return $this->tableHeadFilterForm;
	}

	/**
	 * @inheritDocs
	 * @return string
	 */
	public function GetFilterFormValuesDelimiter () {
		return $this->filterFormValuesDelimiter;
	}
	
	/**
	 * @inheritDocs
	 * @param  string $filterFormValuesDelimiter
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetFilterFormValuesDelimiter ($filterFormValuesDelimiter) {
		$this->filterFormValuesDelimiter = $filterFormValuesDelimiter;
		return $this;
	}
	
	/**
	 * @inheritDocs
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
	 * @inheritDocs
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
	 * @inheritDocs
	 * @return \string[]
	 */
	public function GetCssClasses () {
		return $this->cssClasses;
	}

}