<?php

namespace MvcCore\Ext\Controllers\DataGrids\Configs;

class Rendering {

	/**
	 * 
	 * @var bool
	 */
	protected $renderPageControl	= TRUE;
	
	/**
	 * 
	 * @var bool
	 */
	protected $renderOrderControl	= TRUE;
	
	/**
	 * 
	 * @var bool
	 */
	protected $renderCountControl	= TRUE;
	
	/**
	 * 
	 * @var bool
	 */
	protected $renderFilterForm		= FALSE;
	
	/**
	 * 
	 * @var string
	 */
	protected $templateGridContent	= './grid';
	
	/**
	 * 
	 * @var string
	 */
	protected $templatePageControl	= './page';
	
	/**
	 * 
	 * @var string
	 */
	protected $templateOrderControl	= './order';
	
	/**
	 * 
	 * @var string
	 */
	protected $templateCountControl	= './count';
	
	/**
	 * 
	 * @var string
	 */
	protected $templateFilterForm	= './filter';

	
	/**
	 * 
	 * @param  bool $renderPageControl
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetRenderPageControl ($renderPageControl) {
		$this->renderPageControl = $renderPageControl;
		return $this;
	}
	/**
	 * 
	 * @return bool
	 */
	public function GetRenderPageControl () {
		return $this->renderPageControl;
	}

	/**
	 * 
	 * @param  bool $renderOrderControl
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetRenderOrderControl ($renderOrderControl) {
		$this->renderOrderControl = $renderOrderControl;
		return $this;
	}
	/**
	 * 
	 * @return bool
	 */
	public function GetRenderOrderControl () {
		return $this->renderOrderControl;
	}

	/**
	 * 
	 * @param  bool $renderCountControl
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetRenderCountControl ($renderCountControl) {
		$this->renderCountControl = $renderCountControl;
		return $this;
	}
	/**
	 * 
	 * @return bool
	 */
	public function GetRenderCountControl () {
		return $this->renderCountControl;
	}

	/**
	 * 
	 * @param  bool $renderFilterForm
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetRenderFilterForm ($renderFilterForm) {
		$this->renderFilterForm = $renderFilterForm;
		return $this;
	}
	/**
	 * 
	 * @return bool
	 */
	public function GetRenderFilterForm () {
		return $this->renderFilterForm;
	}

	
	/**
	 * 
	 * @param  string $templateGridContent
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetTemplateGridContent ($templateGridContent) {
		$this->templateGridContent = $templateGridContent;
		return $this;
	}
	/**
	 * 
	 * @return string
	 */
	public function GetTemplateGridContent () {
		return $this->templateGridContent;
	}

	/**
	 * 
	 * @param  string $templatePageControl
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetTemplatePageControl ($templatePageControl) {
		$this->templatePageControl = $templatePageControl;
		return $this;
	}
	/**
	 * 
	 * @return string
	 */
	public function GetTemplatePageControl () {
		return $this->templatePageControl;
	}

	/**
	 * 
	 * @param  string $templateOrderControl
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetTemplateOrderControl ($templateOrderControl) {
		$this->templateOrderControl = $templateOrderControl;
		return $this;
	}
	/**
	 * 
	 * @return string
	 */
	public function GetTemplateOrderControl () {
		return $this->templateOrderControl;
	}

	/**
	 * 
	 * @param  string $templateCountControl
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetTemplateCountControl ($templateCountControl) {
		$this->templateCountControl = $templateCountControl;
		return $this;
	}
	/**
	 * 
	 * @return string
	 */
	public function GetTemplateCountControl () {
		return $this->templateCountControl;
	}

	/**
	 * 
	 * @param  string $templateFilterForm
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetTemplateFilterForm ($templateFilterForm) {
		$this->templateFilterForm = $templateFilterForm;
		return $this;
	}
	/**
	 * 
	 * @return string
	 */
	public function GetTemplateFilterForm () {
		return $this->templateFilterForm;
	}
}