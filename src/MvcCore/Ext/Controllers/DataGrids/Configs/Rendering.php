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
implements	\MvcCore\Ext\Controllers\DataGrids\Configs\IRendering,
			\JsonSerializable {
	
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
	 * @jsonSerialize
	 * @var bool
	 */
	#[JsonSerialize]
	protected $renderTableHead						= TRUE;

	/**
	 * Render table head sorting links in datagrid table type.
	 * Rendered by default.
	 * @jsonSerialize
	 * @var bool
	 */
	#[JsonSerialize]
	protected $renderTableHeadSorting				= TRUE;

	/**
	 * Render table head filtering fields and buttons in datagrid table type.
	 * Not rendered by default.
	 * @jsonSerialize
	 * @var bool
	 */
	#[JsonSerialize]
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
	 * @jsonSerialize
	 * @var int
	 */
	#[JsonSerialize]
	protected $renderControlPaging					= \MvcCore\Ext\Controllers\IDataGrid::CONTROL_DISPLAY_IF_NECESSARY;
	
	/**
	 * Render previous and next page links in paging control.
	 * Rendered by default.
	 * @jsonSerialize
	 * @var bool
	 */
	#[JsonSerialize]
	protected $renderControlPagingPrevAndNext		= TRUE;
	
	/**
	 * Render first and last page links in paging control.
	 * Rendered by default.
	 * @jsonSerialize
	 * @var bool
	 */
	#[JsonSerialize]
	protected $renderControlPagingFirstAndLast		= FALSE;

	/**
	 * Rendered nearby pages count in page control.
	 * This value means how many pages will be rendered 
	 * around current page to left or to right side.
	 * There are rendered 3 nearby pages to each side by default.
	 * @jsonSerialize
	 * @var int
	 */
	#[JsonSerialize]
	protected $controlPagingNearbyPagesCount		= 3;
	
	/**
	 * Rendered outer pages count in page control.
	 * This value is used if there are really many pages in paging control.
	 * The value means how many pages will be rendered in 
	 * overview of all not rendered page links in each side.
	 * There are rendered 2 outer pages to each side by default.
	 * @jsonSerialize
	 * @var int
	 */
	#[JsonSerialize]
	protected $controlPagingOuterPagesCount			= 2;
	
	/**
	 * Outer pages ratio. This value is used to start render outer 
	 * pages overview in paging control. If not rendered pages count 
	 * in side, divided by outer pages count, is higher than this value,
	 * then outer pages are rendered.
	 * @jsonSerialize
	 * @var int
	 */
	#[JsonSerialize]
	protected $controlPagingOuterPagesDisplayRatio	= 3.0;
	
	/**
	 * Render control with items per page (all datagrid types).
	 * This control is always rendered by default.
	 * @jsonSerialize
	 * @var int
	 */
	#[JsonSerialize]
	protected $renderControlCountScales				= \MvcCore\Ext\Controllers\IDataGrid::CONTROL_DISPLAY_ALWAYS;

	/**
	 * Render status control (all datagrid types).
	 * Rendered if necessary by default.
	 * @jsonSerialize
	 * @var int
	 */
	#[JsonSerialize]
	protected $renderControlStatus					= \MvcCore\Ext\Controllers\IDataGrid::CONTROL_DISPLAY_IF_NECESSARY;
	
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
	 * @jsonSerialize
	 * @var \string[]
	 */
	#[JsonSerialize]
	protected $cssClassesControlSorting				= ['grid-control-sorting'];
	
	/**
	 * Css classes for datagrid paging control.
	 * @jsonSerialize
	 * @var \string[]
	 */
	#[JsonSerialize]
	protected $cssClassesControlPaging				= ['grid-control-paging'];
	
	/**
	 * Css classes for datagrid count scales control.
	 * @jsonSerialize
	 * @var \string[]
	 */
	#[JsonSerialize]
	protected $cssClassesControlCountScales			= ['grid-control-count-scales'];
	
	/**
	 * Css classes for datagrid status control.
	 * @jsonSerialize
	 * @var \string[]
	 */
	#[JsonSerialize]
	protected $cssClassesControlStatus				= ['grid-control-status'];
	

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
	 * Custom datagrid status control template.
	 * Relative from `/App/Views/Scripts` without file extension.
	 * @var string|NULL
	 */
	protected $templateControlStatus				= NULL;
	
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
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $this */
		$this->renderTableHead = $renderTableHead;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return bool
	 */
	public function GetRenderTableHead () {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $this */
		return $this->renderTableHead;
	}
	
	/**
	 * @inheritDocs
	 * @param  bool $renderTableHeadSorting
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetRenderTableHeadSorting ($renderTableHeadSorting) {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $this */
		$this->renderTableHeadSorting = $renderTableHeadSorting;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return bool
	 */
	public function GetRenderTableHeadSorting () {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $this */
		return $this->renderTableHeadSorting;
	}
	
	/**
	 * @inheritDocs
	 * @param  bool $renderTableHeadFiltering
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetRenderTableHeadFiltering ($renderTableHeadFiltering) {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $this */
		$this->renderTableHeadFiltering = $renderTableHeadFiltering;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return bool
	 */
	public function GetRenderTableHeadFiltering () {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $this */
		return $this->renderTableHeadFiltering;
	}

	/**
	 * @inheritDocs
	 * @param  bool $renderControlSorting
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetRenderControlSorting ($renderControlSorting) {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $this */
		$this->renderControlSorting = $renderControlSorting;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return bool
	 */
	public function GetRenderControlSorting () {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $this */
		return $this->renderControlSorting;
	}

	/**
	 * @inheritDocs
	 * @param  int $renderControlPaging
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetRenderControlPaging ($renderControlPaging = \MvcCore\Ext\Controllers\IDataGrid::CONTROL_DISPLAY_IF_NECESSARY) {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $this */
		$this->renderControlPaging = $renderControlPaging;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return int
	 */
	public function GetRenderControlPaging () {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $this */
		return $this->renderControlPaging;
	}

	/**
	 * @inheritDocs
	 * @param  bool $renderControlPagingPrevAndNext
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetRenderControlPagingPrevAndNext ($renderControlPagingPrevAndNext) {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $this */
		$this->renderControlPagingPrevAndNext = $renderControlPagingPrevAndNext;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return bool
	 */
	public function GetRenderControlPagingPrevAndNext () {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $this */
		return $this->renderControlPagingPrevAndNext;
	}

	
	/**
	 * @inheritDocs
	 * @param  bool $renderControlPagingFirstAndLast
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetRenderControlPagingFirstAndLast ($renderControlPagingFirstAndLast) {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $this */
		$this->renderControlPagingFirstAndLast = $renderControlPagingFirstAndLast;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return bool
	 */
	public function GetRenderControlPagingFirstAndLast () {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $this */
		return $this->renderControlPagingFirstAndLast;
	}

	/**
	 * @inheritDocs
	 * @param  int $nearbyPagesCount
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetControlPagingNearbyPagesCount ($nearbyPagesCount) {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $this */
		$this->controlPagingNearbyPagesCount = $nearbyPagesCount;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return int
	 */
	public function GetControlPagingNearbyPagesCount () {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $this */
		return $this->controlPagingNearbyPagesCount;
	}
	
	/**
	 * @inheritDocs
	 * @param  int $outerPagesCount
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetControlPagingOuterPagesCount ($outerPagesCount) {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $this */
		$this->controlPagingOuterPagesCount = $outerPagesCount;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return int
	 */
	public function GetControlPagingOuterPagesCount () {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $this */
		return $this->controlPagingOuterPagesCount;
	}
	
	/**
	 * @inheritDocs
	 * @param  float $outerPagesDisplayRatio
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetControlPagingOuterPagesDisplayRatio ($outerPagesDisplayRatio) {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $this */
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
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $this */
		return $this->controlPagingOuterPagesDisplayRatio;
	}

	/**
	 * @inheritDocs
	 * @param  int $renderControlCountScales
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetRenderControlCountScales ($renderControlCountScales = \MvcCore\Ext\Controllers\IDataGrid::CONTROL_DISPLAY_ALWAYS) {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $this */
		$this->renderControlCountScales = $renderControlCountScales;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return int
	 */
	public function GetRenderControlCountScales () {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $this */
		return $this->renderControlCountScales;
	}

	/**
	 * @inheritDocs
	 * @param  int $renderControlStatus
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetRenderControlStatus ($renderControlStatus = \MvcCore\Ext\Controllers\IDataGrid::CONTROL_DISPLAY_ALWAYS) {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $this */
		$this->renderControlStatus = $renderControlStatus;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return int
	 */
	public function GetRenderControlStatus () {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $this */
		return $this->renderControlStatus;
	}

	/**
	 * @inheritDocs
	 * @param  bool $renderFilterForm
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetRenderFilterForm ($renderFilterForm) {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $this */
		$this->renderFilterForm = $renderFilterForm;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return bool
	 */
	public function GetRenderFilterForm () {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $this */
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
	 * @param string|\string[] $cssClasses
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetCssClassesControlStatus ($cssClasses) {
		$this->cssClassesControlStatus = is_array($cssClasses)
			? $cssClasses
			: explode(' ', (string) $cssClasses);
		return $this;
	}

	/**
	 * @inheritDocs
	 * @param string|\string[] $cssClasses
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function AddCssClassesControlStatus ($cssClasses) {
		$cssClassesArr = is_array($cssClasses)
			? $cssClasses
			: explode(' ', (string) $cssClasses);
		$this->cssClassesControlStatus = array_merge($this->cssClassesControlStatus, $cssClassesArr);
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return \string[]
	 */
	public function GetCssClassesControlStatus () {
		return $this->cssClassesControlStatus;
	}

	
	/**
	 * @inheritDocs
	 * @param  string $templateContent
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetTemplateContent ($templateContent) {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $this */
		$this->templateContent = $templateContent;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return string
	 */
	public function GetTemplateContent () {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $this */
		return $this->templateContent;
	}

	/**
	 * @inheritDocs
	 * @param  string $templateTableHead
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetTemplateTableHead ($templateTableHead) {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $this */
		$this->templateTableHead = $templateTableHead;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return string
	 */
	public function GetTemplateTableHead () {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $this */
		return $this->templateTableHead;
	}
	
	/**
	 * @inheritDocs
	 * @param  string $templateTableBody
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetTemplateTableBody ($templateTableBody) {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $this */
		$this->templateTableBody = $templateTableBody;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return string
	 */
	public function GetTemplateTableBody () {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $this */
		return $this->templateTableBody;
	}
	
	/**
	 * @inheritDocs
	 * @param  string $templateGridBody
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetTemplateGridBody ($templateGridBody) {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $this */
		$this->templateGridBody = $templateGridBody;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return string
	 */
	public function GetTemplateGridBody () {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $this */
		return $this->templateGridBody;
	}

	/**
	 * @inheritDocs
	 * @param  string $templateControlPaging
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetTemplateControlPaging ($templateControlPaging) {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $this */
		$this->templateControlPaging = $templateControlPaging;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return string
	 */
	public function GetTemplateControlPaging () {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $this */
		return $this->templateControlPaging;
	}

	/**
	 * @inheritDocs
	 * @param  string $templateControlSorting
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetTemplateControlSorting ($templateControlSorting) {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $this */
		$this->templateControlSorting = $templateControlSorting;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return string
	 */
	public function GetTemplateControlSorting () {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $this */
		return $this->templateControlSorting;
	}

	/**
	 * @inheritDocs
	 * @param  string $templateControlCountScales
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetTemplateControlCountScales ($templateControlCountScales) {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $this */
		$this->templateControlCountScales = $templateControlCountScales;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return string
	 */
	public function GetTemplateControlCountScales () {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $this */
		return $this->templateControlCountScales;
	}

	/**
	 * @inheritDocs
	 * @param  string $templateControlStatus
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetTemplateControlStatus ($templateControlStatus) {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $this */
		$this->templateControlStatus = $templateControlStatus;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return string
	 */
	public function GetTemplateControlStatus () {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $this */
		return $this->templateControlStatus;
	}

	/**
	 * @inheritDocs
	 * @param  string $templateFilterForm
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetTemplateFilterForm ($templateFilterForm) {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $this */
		$this->templateFilterForm = $templateFilterForm;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return string
	 */
	public function GetTemplateFilterForm () {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $this */
		return $this->templateFilterForm;
	}
	
	/**
	 * @inheritDocs
	 * @param  string|\MvcCore\Ext\Controllers\DataGrids\View|\MvcCore\View $viewClass 
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetViewClass ($viewClass) {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $this */
		$this->viewClass = $viewClass;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return string|\MvcCore\Ext\Controllers\DataGrids\View|\MvcCore\View
	 */
	public function GetViewClass () {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $this */
		return $this->viewClass;
	}

	/**
	 * Return data for JSON serialization.
	 * @return array<string, mixed>
	 */
	#[\ReturnTypeWillChange]
	public function jsonSerialize () {
		return JsonSerialize::Serialize($this, \ReflectionProperty::IS_PROTECTED);
	}
}