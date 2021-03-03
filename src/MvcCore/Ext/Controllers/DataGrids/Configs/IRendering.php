<?php

namespace MvcCore\Ext\Controllers\DataGrids\Configs;

interface IRendering {

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
	 * @param  int $columnsCount
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetGridColumnsCount ($columnsCount);
	
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
	 * Get render table head filtering fields and buttons in datagrid table type.
	 * Not rendered by default.
	 * @return bool
	 */
	public function GetRenderTableHeadFiltering ();

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
	 * There are rendered 3 nearby pages to each side by default.
	 * @param  int $nearbyPagesCount
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetControlPagingNearbyPagesCount ($nearbyPagesCount);

	/**
	 * Get rendered nearby pages count in page control.
	 * This value means how many pages will be rendered 
	 * around current page to left or to right side.
	 * There are rendered 3 nearby pages to each side by default.
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
	 * Set custom datagrid base content template.
	 * Relative from `/App/Views/Scripts` without file extension.
	 * @param  string $templateGridContent
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetTemplateGridContent ($templateGridContent);

	/**
	 * Get custom datagrid base content template.
	 * Relative from `/App/Views/Scripts` without file extension.
	 * @return string
	 */
	public function GetTemplateGridContent ();
	
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