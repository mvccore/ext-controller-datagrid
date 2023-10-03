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

interface IRendering {

	/**
	 * Merge any other rendering config.
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $configRendering 
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function Merge (\MvcCore\Ext\Controllers\DataGrids\Configs\IRendering $configRendering);

	/**
	 * Set datagrid type - table or grid of items.
	 * `\MvcCore\Ext\Controllers\IDataGrid::TYPE_TABLE` type by default.
	 * @param  int $type
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetType ($type = \MvcCore\Ext\Controllers\IDataGrid::TYPE_TABLE);
	
	/**
	 * Get datagrid type - table or grid of items.
	 * `\MvcCore\Ext\Controllers\IDataGrid::TYPE_TABLE` type by default.
	 * @return int
	 */
	public function GetType ();
	
	/**
	 * Set datagrid grid type columns count.
	 * Threre are rendered 3 columns by default.
	 * @param  int $gridColumnsCount
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetGridColumnsCount ($gridColumnsCount);
	
	/**
	 * Get datagrid grid type columns count.
	 * Threre are rendered 3 columns by default.
	 * @return int
	 */
	public function GetGridColumnsCount ();
	
	/**
	 * Set render table head in datagrid table type.
	 * Rendered by default.
	 * @param  bool $renderTableHead
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetRenderTableHead ($renderTableHead);

	/**
	 * Get render table head in datagrid table type.
	 * Rendered by default.
	 * @return bool
	 */
	public function GetRenderTableHead ();
	
	/**
	 * Set render table head sorting links in datagrid table type.
	 * Rendered by default.
	 * @param  bool $renderTableHeadSorting
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetRenderTableHeadSorting ($renderTableHeadSorting);

	/**
	 * Get render table head sorting links in datagrid table type.
	 * Rendered by default.
	 * @return bool
	 */
	public function GetRenderTableHeadSorting ();
	
	/**
	 * Set render table head filtering fields and buttons in datagrid table type.
	 * Not rendered by default.
	 * @param  bool $renderTableHeadFiltering
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetRenderTableHeadFiltering ($renderTableHeadFiltering);

	/**
	 * Get render table body cells as filtering links, 
	 * where `=` filtering allowed or where `LIKE` filtering
	 * allowed for `DateTime` displayed as `Date`.
	 * Not rendered by default.
	 * @return bool
	 */
	public function GetRenderTableHeadFiltering ();

	/**
	 * Set render table body cells as filtering links, 
	 * where `=` filtering allowed or where `LIKE` filtering
	 * allowed for `DateTime` displayed as `Date`.
	 * Not rendered by default.
	 * @param  bool $renderTableBodyFilteringLinks
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetRenderTableBodyFilteringLinks ($renderTableBodyFilteringLinks);

	/**
	 * @inheritDoc
	 * @return bool
	 */
	public function GetRenderTableBodyFilteringLinks ();

	/**
	 * Set render separated sort control (all datagrid types).
	 * Not rendered by default.
	 * @param  bool $renderControlSorting
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetRenderControlSorting ($renderControlSorting);

	/**
	 * Get render separated sort control (all datagrid types).
	 * Not rendered by default.
	 * @return bool
	 */
	public function GetRenderControlSorting ();
	
	/**
	 * Set render paging control (all datagrid types).
	 * Rendered if necessary by default.
	 * @param  int $renderControlPaging
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetRenderControlPaging ($renderControlPaging = \MvcCore\Ext\Controllers\IDataGrid::CONTROL_DISPLAY_IF_NECESSARY);

	/**
	 * Get render paging control (all datagrid types).
	 * Rendered if necessary by default.
	 * @return int
	 */
	public function GetRenderControlPaging ();

	/**
	 * Set render previous and next page links in paging control.
	 * Rendered by default.
	 * @param  bool $renderControlPagingPrevAndNext
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetRenderControlPagingPrevAndNext ($renderControlPagingPrevAndNext);

	/**
	 * Get render previous and next page links in paging control.
	 * Rendered by default.
	 * @return bool
	 */
	public function GetRenderControlPagingPrevAndNext ();

	/**
	 * Set render first and last page links in paging control.
	 * Rendered by default.
	 * @param  bool $renderControlPagingFirstAndLast
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetRenderControlPagingFirstAndLast ($renderControlPagingFirstAndLast);

	/**
	 * Get render first and last page links in paging control.
	 * Rendered by default.
	 * @return bool
	 */
	public function GetRenderControlPagingFirstAndLast ();

	/**
	 * Set rendered  nearby pages count in page control.
	 * This value means how many pages will be rendered 
	 * around current page to left or to right side.
	 * There are rendered 2 nearby pages to each side by default.
	 * @param  int $nearbyPagesCount
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetControlPagingNearbyPagesCount ($nearbyPagesCount);

	/**
	 * Get rendered nearby pages count in page control.
	 * This value means how many pages will be rendered 
	 * around current page to left or to right side.
	 * There are rendered 2 nearby pages to each side by default.
	 * @return int
	 */
	public function GetControlPagingNearbyPagesCount ();
	
	/**
	 * Set rendered outer pages count in page control.
	 * This value is used if there are really many pages in paging control.
	 * The value means how many pages will be rendered in 
	 * overview of all not rendered page links in each side.
	 * There are rendered 2 outer pages to each side by default.
	 * @param  int $outerPagesCount
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetControlPagingOuterPagesCount ($outerPagesCount);

	/**
	 * Get rendered outer pages count in page control.
	 * This value is used if there are really many pages in paging control.
	 * The value means how many pages will be rendered in 
	 * overview of all not rendered page links in each side.
	 * There are rendered 2 outer pages to each side by default.
	 * @return int
	 */
	public function GetControlPagingOuterPagesCount ();

	/**
	 * Set outer pages ratio. This value is used to start render outer 
	 * pages overview in paging control. If not rendered pages count 
	 * in side, divided by outer pages count, is higher than this value,
	 * then outer pages are rendered. Default ratio is `3.0`.
	 * @param  float $outerPagesDisplayRatio
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetControlPagingOuterPagesDisplayRatio ($outerPagesDisplayRatio);

	/**
	 * Get outer pages ratio. This value is used to start render outer 
	 * pages overview in paging control. If not rendered pages count 
	 * in side, divided by outer pages count, is higher than this value,
	 * then outer pages are rendered. Default ratio is `3.0`.
	 * @return float
	 */
	public function GetControlPagingOuterPagesDisplayRatio ();

	/**
	 * Set render control with items per page (all datagrid types).
	 * This control is always rendered by default.
	 * @param  int $renderControlCountScales
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetRenderControlCountScales ($renderControlCountScales = \MvcCore\Ext\Controllers\IDataGrid::CONTROL_DISPLAY_ALWAYS);

	/**
	 * Get render control with items per page (all datagrid types).
	 * This control is always rendered by default.
	 * @return int
	 */
	public function GetRenderControlCountScales ();

	/**
	 * Set render control with status text (all datagrid types).
	 * This control is always rendered by default.
	 * @param  int $renderControlStatus
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetRenderControlStatus ($renderControlStatus = \MvcCore\Ext\Controllers\IDataGrid::CONTROL_DISPLAY_IF_NECESSARY);

	/**
	 * Get render control with status text (all datagrid types).
	 * This control is always rendered by default.
	 * @return int
	 */
	public function GetRenderControlStatus ();

	/**
	 * Set render custom filter form (all datagrid types).
	 * This is not rendered by default. To render custom 
	 * filter form, you have to create some form instance 
	 * and give it into datagrid. Then you can enable this rendering.
	 * @param  bool $renderFilterForm
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetRenderFilterForm ($renderFilterForm);

	/**
	 * Get render custom filter form (all datagrid types).
	 * This is not rendered by default. To render custom 
	 * filter form, you have to create some form instance 
	 * and give it into datagrid. Then you can enable this rendering.
	 * @return bool
	 */
	public function GetRenderFilterForm ();


	/**
	 * Set css classes for datagrid main `<table>` element containing data rows.
	 * All previously configured css classes will be replaced with given values.
	 * @param string|\string[] $cssClasses
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetCssClassesContentTable ($cssClasses);

	/**
	 * Add css classes for datagrid main `<table>` element containing data rows.
	 * @param string|\string[] $cssClasses
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function AddCssClassesContentTable ($cssClasses);

	/**
	 * Get css classes for datagrid main `<table>` element containing data rows.
	 * @return \string[]
	 */
	public function GetCssClassesContentTable ();

	/**
	 * Set css classes for datagrid `<div>` elements wrapping top and bottom 
	 * sorting, paging and count scales controls.
	 * All previously configured css classes will be replaced with given values.
	 * @param string|\string[] $cssClasses
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetCssClassesControlsWrapper ($cssClasses);

	/**
	 * Add css classes for datagrid `<div>` elements wrapping top and bottom 
	 * sorting, paging and count scales controls.
	 * @param string|\string[] $cssClasses
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function AddCssClassesControlsWrapper ($cssClasses);

	/**
	 * Get css classes for datagrid `<div>` elements wrapping top and bottom 
	 * sorting, paging and count scales controls.
	 * @return \string[]
	 */
	public function GetCssClassesControlsWrapper ();

	/**
	 * Set css classes for datagrid sorting control.
	 * All previously configured css classes will be replaced with given values.
	 * @param string|\string[] $cssClasses
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetCssClassesControlSorting ($cssClasses);

	/**
	 * Add css classes for datagrid sorting control.
	 * @param string|\string[] $cssClasses
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function AddCssClassesControlSorting ($cssClasses);

	/**
	 * Get css classes for datagrid sorting control.
	 * @return \string[]
	 */
	public function GetCssClassesControlSorting ();

	/**
	 * Set css classes for datagrid paging control.
	 * All previously configured css classes will be replaced with given values.
	 * @param string|\string[] $cssClasses
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetCssClassesControlPaging ($cssClasses);

	/**
	 * Add css classes for datagrid paging control.
	 * @param string|\string[] $cssClasses
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function AddCssClassesControlPaging ($cssClasses);

	/**
	 * Get css classes for datagrid paging control.
	 * @return \string[]
	 */
	public function GetCssClassesControlPaging ();

	/**
	 * Set css classes for datagrid paging control button.
	 * All previously configured css classes will be replaced with given values.
	 * @param string|\string[] $cssClasses
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetCssClassesControlPagingButton ($cssClasses);

	/**
	 * Add css classes for datagrid paging control button.
	 * @param string|\string[] $cssClasses
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function AddCssClassesControlPagingButton ($cssClasses);

	/**
	 * Get css classes for datagrid paging control button.
	 * @return \string[]
	 */
	public function GetCssClassesControlPagingButton ();

	/**
	 * Set css classes for datagrid paging control current text.
	 * All previously configured css classes will be replaced with given values.
	 * @param string|\string[] $cssClasses
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetCssClassesControlPagingCurrent ($cssClasses);

	/**
	 * Add css classes for datagrid paging control current text.
	 * @param string|\string[] $cssClasses
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function AddCssClassesControlPagingCurrent ($cssClasses);

	/**
	 * Get css classes for datagrid paging control current text.
	 * @return \string[]
	 */
	public function GetCssClassesControlPagingCurrent ();

	/**
	 * Set css classes for datagrid count scales control.
	 * All previously configured css classes will be replaced with given values.
	 * @param string|\string[] $cssClasses
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetCssClassesControlCountScales ($cssClasses);

	/**
	 * Add css classes for datagrid count scales control.
	 * @param string|\string[] $cssClasses
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function AddCssClassesControlCountScales ($cssClasses);

	/**
	 * Get css classes for datagrid count scales control.
	 * @return \string[]
	 */
	public function GetCssClassesControlCountScales ();

	/**
	 * Set css classes for datagrid count scales control button(s).
	 * All previously configured css classes will be replaced with given values.
	 * @param string|\string[] $cssClasses
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetCssClassesControlCountScalesButton ($cssClasses);

	/**
	 * Add css classes for datagrid count scales control button.
	 * @param string|\string[] $cssClasses
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function AddCssClassesControlCountScalesButton ($cssClasses);

	/**
	 * Get css classes for datagrid count scales control button.
	 * @return \string[]
	 */
	public function GetCssClassesControlCountScalesButton ();
	
	/**
	 * Set css classes for datagrid count scales control current scale text.
	 * @param string|\string[] $cssClasses
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetCssClassesControlCountScalesCurrent ($cssClasses);

	/**
	 * Add css classes for datagrid count scales control current scale text.
	 * @param string|\string[] $cssClasses
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function AddCssClassesControlCountScalesCurrent ($cssClasses);

	/**
	 * Get css classes for datagrid count scales control current scale text.
	 * @return \string[]
	 */
	public function GetCssClassesControlCountScalesCurrent ();

	/**
	 * Set css classes for datagrid status text control.
	 * All previously configured css classes will be replaced with given values.
	 * @param string|\string[] $cssClasses
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetCssClassesControlStatus ($cssClasses);

	/**
	 * Add css classes for datagrid status text control.
	 * @param string|\string[] $cssClasses
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function AddCssClassesControlStatus ($cssClasses);

	/**
	 * Get css classes for datagrid status text control.
	 * @return \string[]
	 */
	public function GetCssClassesControlStatus ();


	/**
	 * Set custom datagrid base content template.
	 * Relative from `/App/Views/Scripts` without file extension.
	 * @param  string $templateGridContent
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetTemplateContent ($templateGridContent);

	/**
	 * Get custom datagrid base content template.
	 * Relative from `/App/Views/Scripts` without file extension.
	 * @return string
	 */
	public function GetTemplateContent ();
	
	/**
	 * Set custom datagrid (table type) table head template.
	 * Relative from `/App/Views/Scripts` without file extension.
	 * @param  string $templateTableHead
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetTemplateTableHead ($templateTableHead);

	/**
	 * Get custom datagrid (table type) table head template.
	 * Relative from `/App/Views/Scripts` without file extension.
	 * @return string
	 */
	public function GetTemplateTableHead ();
	
	/**
	 * Set custom datagrid (table type) table body template.
	 * Relative from `/App/Views/Scripts` without file extension.
	 * @param  string $templateTableBody
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetTemplateTableBody ($templateTableBody);

	/**
	 * Get custom datagrid (table type) table body template.
	 * Relative from `/App/Views/Scripts` without file extension.
	 * @return string
	 */
	public function GetTemplateTableBody ();

	/**
	 * Set custom datagrid (grid type) table body template.
	 * Relative from `/App/Views/Scripts` without file extension.
	 * @param  string $templateGridBody
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetTemplateGridBody ($templateGridBody);

	/**
	 * Get custom datagrid (grid type) table body template.
	 * Relative from `/App/Views/Scripts` without file extension.
	 * @return string
	 */
	public function GetTemplateGridBody ();

	/**
	 * Set custom datagrid paging control template.
	 * Relative from `/App/Views/Scripts` without file extension.
	 * @param  string $templateControlPaging
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetTemplateControlPaging ($templateControlPaging);

	/**
	 * Get custom datagrid paging control template.
	 * Relative from `/App/Views/Scripts` without file extension.
	 * @return string
	 */
	public function GetTemplateControlPaging ();

	/**
	 * Set custom datagrid sorting control template.
	 * Relative from `/App/Views/Scripts` without file extension.
	 * @param  string $templateControlSorting
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetTemplateControlSorting ($templateControlSorting);

	/**
	 * Get custom datagrid sorting control template.
	 * Relative from `/App/Views/Scripts` without file extension.
	 * @return string
	 */
	public function GetTemplateControlSorting ();

	/**
	 * Set custom datagrid items per page control template.
	 * Relative from `/App/Views/Scripts` without file extension.
	 * @param  string $templateControlCountScales
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetTemplateControlCountScales ($templateControlCountScales);

	/**
	 * Get custom datagrid items per page control template.
	 * Relative from `/App/Views/Scripts` without file extension.
	 * @return string
	 */
	public function GetTemplateControlCountScales ();

	/**
	 * Set custom datagrid status text control template.
	 * Relative from `/App/Views/Scripts` without file extension.
	 * @param  string $templateControlStatus
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetTemplateControlStatus ($templateControlStatus);

	/**
	 * Get custom datagrid status text control template.
	 * Relative from `/App/Views/Scripts` without file extension.
	 * @return string
	 */
	public function GetTemplateControlStatus ();

	/**
	 * Set custom datagrid custom filter form control template.
	 * Relative from `/App/Views/Scripts` without file extension.
	 * @param  string $templateFilterForm
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetTemplateFilterForm ($templateFilterForm);

	/**
	 * Get custom datagrid custom filter form control template.
	 * Relative from `/App/Views/Scripts` without file extension.
	 * @return string
	 */
	public function GetTemplateFilterForm ();
	
	/**
	 * Set datagrid view full class name.
	 * @param  string|\MvcCore\Ext\Controllers\DataGrids\View|\MvcCore\View $viewClass 
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetViewClass ($viewClass);

	/**
	 * Get datagrid view full class name.
	 * @return string|\MvcCore\Ext\Controllers\DataGrids\View|\MvcCore\View
	 */
	public function GetViewClass ();
}