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

namespace MvcCore\Ext\Controllers\DataGrids\Configs;

class		Rendering
implements	\MvcCore\Ext\Controllers\DataGrids\Configs\IRendering {
	
	/**
	 * Datagrid type - table or grid of items.
	 * `\MvcCore\Ext\Controllers\IDataGrid::TYPE_TABLE` type by default.
	 * @var int
	 */
	protected $type									= \MvcCore\Ext\Controllers\IDataGrid::TYPE_TABLE;

	/**
	 * Datagrid grid type columns count.
	 * Threre are rendered 3 columns by default.
	 * @var int
	 */
	protected $gridColumnsCount						= 3;

	/**
	 * Render table head in datagrid table type.
	 * Rendered by default.
	 * @var bool
	 */
	protected $renderTableHead						= TRUE;

	/**
	 * Render table head sorting links in datagrid table type.
	 * Rendered by default.
	 * @var bool
	 */
	protected $renderTableHeadSorting				= TRUE;

	/**
	 * Render table head filtering fields and buttons in datagrid table type.
	 * Not rendered by default.
	 * @var bool
	 */
	protected $renderTableHeadFiltering				= FALSE;

	/**
	 * Render separated sort control (all datagrid types).
	 * Not rendered by default.
	 * @var bool
	 */
	protected $renderControlSorting					= FALSE;

	/**
	 * Render paging control (all datagrid types).
	 * Rendered if necessary by default.
	 * @var int
	 */
	protected $renderControlPaging					= \MvcCore\Ext\Controllers\IDataGrid::CONTROL_DISPLAY_IF_NECESSARY;
	
	/**
	 * Render previous and next page links in paging control.
	 * Rendered by default.
	 * @var bool
	 */
	protected $renderControlPagingPrevAndNext		= TRUE;
	
	/**
	 * Render first and last page links in paging control.
	 * Rendered by default.
	 * @var bool
	 */
	protected $renderControlPagingFirstAndLast		= FALSE;

	/**
	 * Rendered nearby pages count in page control.
	 * This value means how many pages will be rendered 
	 * around current page to left or to right side.
	 * There are rendered 3 nearby pages to each side by default.
	 * @var int
	 */
	protected $controlPagingNearbyPagesCount		= 3;
	
	/**
	 * Rendered outer pages count in page control.
	 * This value is used if there are really many pages in paging control.
	 * The value means how many pages will be rendered in 
	 * overview of all not rendered page links in each side.
	 * There are rendered 2 outer pages to each side by default.
	 * @var int
	 */
	protected $controlPagingOuterPagesCount			= 2;
	
	/**
	 * Outer pages ratio. This value is used to start render outer 
	 * pages overview in paging control. If not rendered pages count 
	 * in side, divided by outer pages count, is higher than this value,
	 * then outer pages are rendered.
	 * @var int
	 */
	protected $controlPagingOuterPagesDisplayRatio	= 3.0;
	
	/**
	 * Render control with items per page (all datagrid types).
	 * This control is always rendered by default.
	 * @var int
	 */
	protected $renderControlCountScales				= \MvcCore\Ext\Controllers\IDataGrid::CONTROL_DISPLAY_ALWAYS;
	
	/**
	 * Render custom filter form (all datagrid types).
	 * This is not rendered by default. To render custom 
	 * filter form, you have to create some form instance 
	 * and give it into datagrid. Then you can enable this rendering.
	 * @var bool
	 */
	protected $renderFilterForm						= FALSE;


	/**
	 * Css classes for datagrid main `<table>` element containing data rows.
	 * @var \string[]
	 */
	protected $cssClassesContentTable				= ['grid-content'];
	
	/**
	 * Css classes for datagrid `<div>` elements wrapping top and bottom 
	 * sorting, paging and count scales controls.
	 * @var \string[]
	 */
	protected $cssClassesControlsWrapper			= ['grid-controls'];
	
	/**
	 * Css classes for datagrid sorting control.
	 * @var \string[]
	 */
	protected $cssClassesControlSorting				= ['grid-control-sorting'];
	
	/**
	 * Css classes for datagrid paging control.
	 * @var \string[]
	 */
	protected $cssClassesControlPaging				= ['grid-control-paging'];
	
	/**
	 * Css classes for datagrid count scales control.
	 * @var \string[]
	 */
	protected $cssClassesControlCountScales			= ['grid-control-count-scales'];
	

	/**
	 * Custom datagrid base content template.
	 * Relative from `/App/Views/Scripts` without file extension.
	 * @var string|NULL
	 */
	protected $templateContent						= NULL;
	
	/**
	 * Custom datagrid (table type) table head template.
	 * Relative from `/App/Views/Scripts` without file extension.
	 * @var string|NULL
	 */
	protected $templateTableHead					= NULL;
	
	/**
	 * Custom datagrid (table type) table body template.
	 * Relative from `/App/Views/Scripts` without file extension.
	 * @var string|NULL
	 */
	protected $templateTableBody					= NULL;
	
	/**
	 * Custom datagrid (grid type) table body template.
	 * Relative from `/App/Views/Scripts` without file extension.
	 * @var string|NULL
	 */
	protected $templateGridBody						= NULL;
	
	/**
	 * Custom datagrid paging control template.
	 * Relative from `/App/Views/Scripts` without file extension.
	 * @var string|NULL
	 */
	protected $templateControlPaging				= NULL;
	
	/**
	 * Custom datagrid sorting control template.
	 * Relative from `/App/Views/Scripts` without file extension.
	 * @var string|NULL
	 */
	protected $templateControlSorting				= NULL;
	
	/**
	 * Custom datagrid items per page control template.
	 * Relative from `/App/Views/Scripts` without file extension.
	 * @var string|NULL
	 */
	protected $templateControlCountScales			= NULL;
	
	/**
	 * Custom datagrid custom filter form control template.
	 * Relative from `/App/Views/Scripts` without file extension.
	 * @var string|NULL
	 */
	protected $templateFilterForm					= NULL;
	
	/**
	 * Datagrid view full class name.
	 * @var string|\MvcCore\Ext\Controllers\DataGrids\View|\MvcCore\View
	 */
	protected $viewClass = '\\MvcCore\\Ext\\Controllers\\DataGrids\\View';
	

	/**
	 * @inheritDocs
	 * @param  int $type
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetType ($type = \MvcCore\Ext\Controllers\IDataGrid::TYPE_TABLE) {
		$this->type = $type;
		return $this;
	}
	
	/**
	 * @inheritDocs
	 * @return int
	 */
	public function GetType () {
		return $this->type;
	}
	
	/**
	 * @inheritDocs
	 * @param  int $gridColumnsCount
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetGridColumnsCount ($gridColumnsCount) {
		$this->gridColumnsCount = $gridColumnsCount;
		return $this;
	}
	
	/**
	 * @inheritDocs
	 * @return int
	 */
	public function GetGridColumnsCount () {
		return $this->gridColumnsCount;
	}
	
	/**
	 * @inheritDocs
	 * @param  bool $renderTableHead
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetRenderTableHead ($renderTableHead) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		$this->renderTableHead = $renderTableHead;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return bool
	 */
	public function GetRenderTableHead () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		return $this->renderTableHead;
	}
	
	/**
	 * @inheritDocs
	 * @param  bool $renderTableHeadSorting
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetRenderTableHeadSorting ($renderTableHeadSorting) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		$this->renderTableHeadSorting = $renderTableHeadSorting;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return bool
	 */
	public function GetRenderTableHeadSorting () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		return $this->renderTableHeadSorting;
	}
	
	/**
	 * @inheritDocs
	 * @param  bool $renderTableHeadFiltering
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetRenderTableHeadFiltering ($renderTableHeadFiltering) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		$this->renderTableHeadFiltering = $renderTableHeadFiltering;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return bool
	 */
	public function GetRenderTableHeadFiltering () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		return $this->renderTableHeadFiltering;
	}

	/**
	 * @inheritDocs
	 * @param  bool $renderControlSorting
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetRenderControlSorting ($renderControlSorting) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		$this->renderControlSorting = $renderControlSorting;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return bool
	 */
	public function GetRenderControlSorting () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		return $this->renderControlSorting;
	}

	/**
	 * @inheritDocs
	 * @param  int $renderControlPaging
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetRenderControlPaging ($renderControlPaging = \MvcCore\Ext\Controllers\IDataGrid::CONTROL_DISPLAY_IF_NECESSARY) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		$this->renderControlPaging = $renderControlPaging;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return int
	 */
	public function GetRenderControlPaging () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		return $this->renderControlPaging;
	}

	/**
	 * @inheritDocs
	 * @param  bool $renderControlPagingPrevAndNext
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetRenderControlPagingPrevAndNext ($renderControlPagingPrevAndNext) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		$this->renderControlPagingPrevAndNext = $renderControlPagingPrevAndNext;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return bool
	 */
	public function GetRenderControlPagingPrevAndNext () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		return $this->renderControlPagingPrevAndNext;
	}

	
	/**
	 * @inheritDocs
	 * @param  bool $renderControlPagingFirstAndLast
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetRenderControlPagingFirstAndLast ($renderControlPagingFirstAndLast) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		$this->renderControlPagingFirstAndLast = $renderControlPagingFirstAndLast;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return bool
	 */
	public function GetRenderControlPagingFirstAndLast () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		return $this->renderControlPagingFirstAndLast;
	}

	/**
	 * @inheritDocs
	 * @param  int $nearbyPagesCount
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetControlPagingNearbyPagesCount ($nearbyPagesCount) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		$this->controlPagingNearbyPagesCount = $nearbyPagesCount;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return int
	 */
	public function GetControlPagingNearbyPagesCount () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		return $this->controlPagingNearbyPagesCount;
	}
	
	/**
	 * @inheritDocs
	 * @param  int $outerPagesCount
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetControlPagingOuterPagesCount ($outerPagesCount) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		$this->controlPagingOuterPagesCount = $outerPagesCount;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return int
	 */
	public function GetControlPagingOuterPagesCount () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		return $this->controlPagingOuterPagesCount;
	}
	
	/**
	 * @inheritDocs
	 * @param  float $outerPagesDisplayRatio
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetControlPagingOuterPagesDisplayRatio ($outerPagesDisplayRatio) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		if (floatval($outerPagesDisplayRatio) < 2.0)
			throw new \InvalidArgumentException(
				"Outer pages displaying ratio has to be larger than 2.0."
			);
		$this->controlPagingOuterPagesDisplayRatio = $outerPagesDisplayRatio;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return float
	 */
	public function GetControlPagingOuterPagesDisplayRatio () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		return $this->controlPagingOuterPagesDisplayRatio;
	}

	/**
	 * @inheritDocs
	 * @param  int $renderControlCountScales
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetRenderControlCountScales ($renderControlCountScales = \MvcCore\Ext\Controllers\IDataGrid::CONTROL_DISPLAY_ALWAYS) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		$this->renderControlCountScales = $renderControlCountScales;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return int
	 */
	public function GetRenderControlCountScales () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		return $this->renderControlCountScales;
	}

	/**
	 * @inheritDocs
	 * @param  bool $renderFilterForm
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetRenderFilterForm ($renderFilterForm) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		$this->renderFilterForm = $renderFilterForm;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return bool
	 */
	public function GetRenderFilterForm () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		return $this->renderFilterForm;
	}


	/**
	 * @inheritDocs
	 * @param string|\string[] $cssClasses
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetCssClassesContentTable ($cssClasses) {
		$this->cssClassesContentTable = is_array($cssClasses)
			? $cssClasses
			: explode(' ', (string) $cssClasses);
		return $this;
	}

	/**
	 * @inheritDocs
	 * @param string|\string[] $cssClasses
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function AddCssClassesContentTable ($cssClasses) {
		$cssClassesArr = is_array($cssClasses)
			? $cssClasses
			: explode(' ', (string) $cssClasses);
		$this->cssClassesContentTable = array_merge($this->cssClassesContentTable, $cssClassesArr);
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return \string[]
	 */
	public function GetCssClassesContentTable () {
		return $this->cssClassesContentTable;
	}

	/**
	 * @inheritDocs
	 * @param string|\string[] $cssClasses
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetCssClassesControlsWrapper ($cssClasses) {
		$this->cssClassesControlsWrapper = is_array($cssClasses)
			? $cssClasses
			: explode(' ', (string) $cssClasses);
		return $this;
	}

	/**
	 * @inheritDocs
	 * @param string|\string[] $cssClasses
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function AddCssClassesControlsWrapper ($cssClasses) {
		$cssClassesArr = is_array($cssClasses)
			? $cssClasses
			: explode(' ', (string) $cssClasses);
		$this->cssClassesControlsWrapper = array_merge($this->cssClassesControlsWrapper, $cssClassesArr);
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return \string[]
	 */
	public function GetCssClassesControlsWrapper () {
		return $this->cssClassesControlsWrapper;
	}

	/**
	 * @inheritDocs
	 * @param string|\string[] $cssClasses
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetCssClassesControlSorting ($cssClasses) {
		$this->cssClassesControlSorting = is_array($cssClasses)
			? $cssClasses
			: explode(' ', (string) $cssClasses);
		return $this;
	}

	/**
	 * @inheritDocs
	 * @param string|\string[] $cssClasses
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function AddCssClassesControlSorting ($cssClasses) {
		$cssClassesArr = is_array($cssClasses)
			? $cssClasses
			: explode(' ', (string) $cssClasses);
		$this->cssClassesControlSorting = array_merge($this->cssClassesControlSorting, $cssClassesArr);
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return \string[]
	 */
	public function GetCssClassesControlSorting () {
		return $this->cssClassesControlSorting;
	}

	/**
	 * @inheritDocs
	 * @param string|\string[] $cssClasses
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetCssClassesControlPaging ($cssClasses) {
		$this->cssClassesControlPaging = is_array($cssClasses)
			? $cssClasses
			: explode(' ', (string) $cssClasses);
		return $this;
	}

	/**
	 * @inheritDocs
	 * @param string|\string[] $cssClasses
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function AddCssClassesControlPaging ($cssClasses) {
		$cssClassesArr = is_array($cssClasses)
			? $cssClasses
			: explode(' ', (string) $cssClasses);
		$this->cssClassesControlPaging = array_merge($this->cssClassesControlPaging, $cssClassesArr);
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return \string[]
	 */
	public function GetCssClassesControlPaging () {
		return $this->cssClassesControlPaging;
	}

	/**
	 * @inheritDocs
	 * @param string|\string[] $cssClasses
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetCssClassesControlCountScales ($cssClasses) {
		$this->cssClassesControlCountScales = is_array($cssClasses)
			? $cssClasses
			: explode(' ', (string) $cssClasses);
		return $this;
	}

	/**
	 * @inheritDocs
	 * @param string|\string[] $cssClasses
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function AddCssClassesControlCountScales ($cssClasses) {
		$cssClassesArr = is_array($cssClasses)
			? $cssClasses
			: explode(' ', (string) $cssClasses);
		$this->cssClassesControlCountScales = array_merge($this->cssClassesControlCountScales, $cssClassesArr);
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return \string[]
	 */
	public function GetCssClassesControlCountScales () {
		return $this->cssClassesControlCountScales;
	}

	
	/**
	 * @inheritDocs
	 * @param  string $templateContent
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetTemplateContent ($templateContent) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		$this->templateContent = $templateContent;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return string
	 */
	public function GetTemplateContent () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		return $this->templateContent;
	}

	/**
	 * @inheritDocs
	 * @param  string $templateTableHead
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetTemplateTableHead ($templateTableHead) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		$this->templateTableHead = $templateTableHead;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return string
	 */
	public function GetTemplateTableHead () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		return $this->templateTableHead;
	}
	
	/**
	 * @inheritDocs
	 * @param  string $templateTableBody
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetTemplateTableBody ($templateTableBody) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		$this->templateTableBody = $templateTableBody;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return string
	 */
	public function GetTemplateTableBody () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		return $this->templateTableBody;
	}
	
	/**
	 * @inheritDocs
	 * @param  string $templateGridBody
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetTemplateGridBody ($templateGridBody) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		$this->templateGridBody = $templateGridBody;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return string
	 */
	public function GetTemplateGridBody () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		return $this->templateGridBody;
	}

	/**
	 * @inheritDocs
	 * @param  string $templateControlPaging
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetTemplateControlPaging ($templateControlPaging) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		$this->templateControlPaging = $templateControlPaging;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return string
	 */
	public function GetTemplateControlPaging () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		return $this->templateControlPaging;
	}

	/**
	 * @inheritDocs
	 * @param  string $templateControlSorting
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetTemplateControlSorting ($templateControlSorting) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		$this->templateControlSorting = $templateControlSorting;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return string
	 */
	public function GetTemplateControlSorting () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		return $this->templateControlSorting;
	}

	/**
	 * @inheritDocs
	 * @param  string $templateControlCountScales
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetTemplateControlCountScales ($templateControlCountScales) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		$this->templateControlCountScales = $templateControlCountScales;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return string
	 */
	public function GetTemplateControlCountScales () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		return $this->templateControlCountScales;
	}

	/**
	 * @inheritDocs
	 * @param  string $templateFilterForm
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetTemplateFilterForm ($templateFilterForm) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		$this->templateFilterForm = $templateFilterForm;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return string
	 */
	public function GetTemplateFilterForm () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		return $this->templateFilterForm;
	}
	
	/**
	 * @inheritDocs
	 * @param  string|\MvcCore\Ext\Controllers\DataGrids\View|\MvcCore\View $viewClass 
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetViewClass ($viewClass) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		$this->viewClass = $viewClass;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return string|\MvcCore\Ext\Controllers\DataGrids\View|\MvcCore\View
	 */
	public function GetViewClass () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		return $this->viewClass;
	}
}