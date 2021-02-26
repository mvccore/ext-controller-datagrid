<?php

namespace MvcCore\Ext\Controllers\DataGrids\Configs;

interface IRendering {

	/**
	 * 
	 * @param  int $type
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetType ($type = \MvcCore\Ext\Controllers\IDataGrid::TYPE_TABLE);
	
	/**
	 * 
	 * @return int
	 */
	public function GetType ();
	
	/**
	 * 
	 * @param  int $columnsCount
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetGridColumnsCount ($columnsCount);
	
	/**
	 * 
	 * @return int
	 */
	public function GetGridColumnsCount ();

	/**
	 * 
	 * @param  bool $renderTableHeadOrdering
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetRenderTableHeadOrdering ($renderTableHeadOrdering);

	/**
	 * 
	 * @return bool
	 */
	public function GetRenderTableHeadOrdering ();

	/**
	 * 
	 * @param  bool $renderControlOrdering
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetRenderControlOrdering ($renderControlOrdering);

	/**
	 * 
	 * @return bool
	 */
	public function GetRenderControlOrdering ();

	/**
	 * 
	 * @param  int $nearbyPagesCount
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetControlPagingNearbyPagesCount ($nearbyPagesCount);

	/**
	 * 
	 * @return int
	 */
	public function GetControlPagingNearbyPagesCount ();
	
	/**
	 * 
	 * @param  int $outerPagesCount
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetControlPagingOuterPagesCount ($outerPagesCount);

	/**
	 * 
	 * @return int
	 */
	public function GetControlPagingOuterPagesCount ();

	/**
	 * 
	 * @param  float $outerPagesDisplayRatio
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetControlPagingOuterPagesDisplayRatio ($outerPagesDisplayRatio);

	/**
	 * 
	 * @return float
	 */
	public function GetControlPagingOuterPagesDisplayRatio ();

	/**
	 * 
	 * @param  int $renderControlPaging
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetRenderControlPaging ($renderControlPaging) ;

	/**
	 * 
	 * @return int
	 */
	public function GetRenderControlPaging ();

	/**
	 * 
	 * @param  bool $renderControlPagingPrevAndNext
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetRenderControlPagingPrevAndNext ($renderControlPagingPrevAndNext);

	/**
	 * 
	 * @return bool
	 */
	public function GetRenderControlPagingPrevAndNext ();

	
	/**
	 * 
	 * @param  bool $renderControlPagingFirstAndLast
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetRenderControlPagingFirstAndLast ($renderControlPagingFirstAndLast);

	/**
	 * 
	 * @return bool
	 */
	public function GetRenderControlPagingFirstAndLast ();

	/**
	 * 
	 * @param  int $renderControlCountScales
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetRenderControlCountScales ($renderControlCountScales);

	/**
	 * 
	 * @return int
	 */
	public function GetRenderControlCountScales ();

	/**
	 * 
	 * @param  bool $renderFilterForm
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetRenderFilterForm ($renderFilterForm);

	/**
	 * 
	 * @return bool
	 */
	public function GetRenderFilterForm ();

	/**
	 * 
	 * @param  string $templateGridContent
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetTemplateGridContent ($templateGridContent);

	/**
	 * 
	 * @return string
	 */
	public function GetTemplateGridContent ();
	
	/**
	 * Set relative path to your custom template from directory 
	 * `/App/Views/Scripts`. Do not use any dots at the beginning.
	 * @param  string $templateTableHead
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetTemplateTableHead ($templateTableHead);

	/**
	 * 
	 * @return string
	 */
	public function GetTemplateTableHead ();
	
	/**
	 * 
	 * @param  string $templateTableBody
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetTemplateTableBody ($templateTableBody);

	/**
	 * 
	 * @return string
	 */
	public function GetTemplateTableBody ();

	/**
	 * 
	 * @param  string $templateGridHead
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetTemplateGridHead ($templateGridHead);

	/**
	 * 
	 * @return string
	 */
	public function GetTemplateGridHead ();
	
	/**
	 * 
	 * @param  string $templateGridBody
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetTemplateGridBody ($templateGridBody);

	/**
	 * 
	 * @return string
	 */
	public function GetTemplateGridBody ();

	/**
	 * 
	 * @param  string $templateControlPaging
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetTemplateControlPaging ($templateControlPaging);

	/**
	 * 
	 * @return string
	 */
	public function GetTemplateControlPaging ();

	/**
	 * 
	 * @param  string $templateControlOrdering
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetTemplateControlOrdering ($templateControlOrdering);

	/**
	 * 
	 * @return string
	 */
	public function GetTemplateControlOrdering ();

	/**
	 * 
	 * @param  string $templateControlCountScales
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetTemplateControlCountScales ($templateControlCountScales);

	/**
	 * 
	 * @return string
	 */
	public function GetTemplateControlCountScales ();

	/**
	 * 
	 * @param  string $templateFilterForm
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetTemplateFilterForm ($templateFilterForm);

	/**
	 * 
	 * @return string
	 */
	public function GetTemplateFilterForm ();
	
	/**
	 * 
	 * @param  string|\MvcCore\Ext\Controllers\DataGrids\View|\MvcCore\View $viewClass 
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function SetViewClass ($viewClass);

	/**
	 * 
	 * @return string|\MvcCore\Ext\Controllers\DataGrids\View|\MvcCore\View
	 */
	public function GetViewClass ();
}