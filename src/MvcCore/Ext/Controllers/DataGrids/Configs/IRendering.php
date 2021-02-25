<?php

namespace MvcCore\Ext\Controllers\DataGrids\Configs;

interface IRendering {

	/**
	 * 
	 * @param  bool $renderPageControl
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetRenderPageControl ($renderPageControl) ;

	/**
	 * 
	 * @return bool
	 */
	public function GetRenderPageControl ();

	/**
	 * 
	 * @param  bool $renderOrderControl
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetRenderOrderControl ($renderOrderControl);

	/**
	 * 
	 * @return bool
	 */
	public function GetRenderOrderControl ();

	/**
	 * 
	 * @param  bool $renderCountControl
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetRenderCountControl ($renderCountControl);

	/**
	 * 
	 * @return bool
	 */
	public function GetRenderCountControl ();

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
	 * 
	 * @param  string $templatePageControl
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetTemplatePageControl ($templatePageControl);

	/**
	 * 
	 * @return string
	 */
	public function GetTemplatePageControl ();

	/**
	 * 
	 * @param  string $templateOrderControl
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetTemplateOrderControl ($templateOrderControl);

	/**
	 * 
	 * @return string
	 */
	public function GetTemplateOrderControl ();

	/**
	 * 
	 * @param  string $templateCountControl
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetTemplateCountControl ($templateCountControl);

	/**
	 * 
	 * @return string
	 */
	public function GetTemplateCountControl ();

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