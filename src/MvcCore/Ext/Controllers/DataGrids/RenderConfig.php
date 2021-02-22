<?php

namespace MvcCore\Ext\Controllers\DataGrids;

class RenderConfig {

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
	protected $templatePageControl	= './Controls/page.phtml';
	
	/**
	 * 
	 * @var string
	 */
	protected $templateOrderControl	= './Controls/order.phtml';
	
	/**
	 * 
	 * @var string
	 */
	protected $templateCountControl	= './Controls/count.phtml';
	
	/**
	 * 
	 * @var string
	 */
	protected $templateFilterForm	= './Form/filter.phtml';

	/**
	 * @param  bool $renderPageControl
	 * @return \MvcCore\Ext\Controllers\DataGrids\RenderConfig
	 */
	public function SetRenderPageControl ($renderPageControl) {
		$this->renderPageControl = $renderPageControl;
		return $this;
	}
	/**
	 * @return bool
	 */
	public function GetRenderPageControl () {
		return $this->renderPageControl;
	}

	/**
	 * @param  bool $renderOrderControl
	 * @return \MvcCore\Ext\Controllers\DataGrids\RenderConfig
	 */
	public function SetRenderOrderControl ($renderOrderControl) {
		$this->renderOrderControl = $renderOrderControl;
		return $this;
	}
	/**
	 * @return bool
	 */
	public function GetRenderOrderControl () {
		return $this->renderOrderControl;
	}

	/**
	 * @param  bool $renderCountControl
	 * @return \MvcCore\Ext\Controllers\DataGrids\RenderConfig
	 */
	public function SetRenderCountControl ($renderCountControl) {
		$this->renderCountControl = $renderCountControl;
		return $this;
	}
	/**
	 * @return bool
	 */
	public function GetRenderCountControl () {
		return $this->renderCountControl;
	}

	/**
	 * @param  bool $renderFilterForm
	 * @return \MvcCore\Ext\Controllers\DataGrids\RenderConfig
	 */
	public function SetRenderFilterForm ($renderFilterForm) {
		$this->renderFilterForm = $renderFilterForm;
		return $this;
	}
	/**
	 * @return bool
	 */
	public function GetRenderFilterForm () {
		return $this->renderFilterForm;
	}

	/**
	 * @param  string $templatePageControl
	 * @return \MvcCore\Ext\Controllers\DataGrids\RenderConfig
	 */
	public function SetTemplatePageControl ($templatePageControl) {
		$this->templatePageControl = $templatePageControl;
		return $this;
	}
	/**
	 * @return string
	 */
	public function GetTemplatePageControl () {
		return $this->templatePageControl;
	}

	/**
	 * @param  string $templateOrderControl
	 * @return \MvcCore\Ext\Controllers\DataGrids\RenderConfig
	 */
	public function SetTemplateOrderControl ($templateOrderControl) {
		$this->templateOrderControl = $templateOrderControl;
		return $this;
	}
	/**
	 * @return string
	 */
	public function GetTemplateOrderControl () {
		return $this->templateOrderControl;
	}

	/**
	 * @param  string $templateCountControl
	 * @return \MvcCore\Ext\Controllers\DataGrids\RenderConfig
	 */
	public function SetTemplateCountControl ($templateCountControl) {
		$this->templateCountControl = $templateCountControl;
		return $this;
	}
	/**
	 * @return string
	 */
	public function GetTemplateCountControl () {
		return $this->templateCountControl;
	}

	/**
	 * @param  string $templateFilterForm
	 * @return \MvcCore\Ext\Controllers\DataGrids\RenderConfig
	 */
	public function SetTemplateFilterForm ($templateFilterForm) {
		$this->templateFilterForm = $templateFilterForm;
		return $this;
	}
	/**
	 * @return string
	 */
	public function GetTemplateFilterForm () {
		return $this->templateFilterForm;
	}
}