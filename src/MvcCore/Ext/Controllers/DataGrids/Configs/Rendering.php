<?php

namespace MvcCore\Ext\Controllers\DataGrids\Configs;

class		Rendering
implements	\MvcCore\Ext\Controllers\DataGrids\Configs\IRendering {

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
	protected $templateGridContent	= \MvcCore\Ext\Controllers\DataGrid\IConstants::TEMPLATE_CONTENT_DEFAULT;
	
	/**
	 * 
	 * @var string
	 */
	protected $templatePageControl	= \MvcCore\Ext\Controllers\DataGrid\IConstants::TEMPLATE_CONTROL_PAGE_DEFAULT;
	
	/**
	 * 
	 * @var string
	 */
	protected $templateOrderControl	= \MvcCore\Ext\Controllers\DataGrid\IConstants::TEMPLATE_CONTROL_ORDER_DEFAULT;
	
	/**
	 * 
	 * @var string
	 */
	protected $templateCountControl	= \MvcCore\Ext\Controllers\DataGrid\IConstants::TEMPLATE_CONTROL_COUNT_DEFAULT;
	
	/**
	 * 
	 * @var string
	 */
	protected $templateFilterForm	= \MvcCore\Ext\Controllers\DataGrid\IConstants::TEMPLATE_FILTER_FORM_DEFAULT;
	
	/**
	 * 
	 * @var string|\MvcCore\Ext\Controllers\DataGrids\View|\MvcCore\View
	 */
	protected $viewClass = '\\MvcCore\\Ext\\Controllers\\DataGrids\\View';
	

	/**
	 * @inheritDocs
	 * @param  bool $renderPageControl
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetRenderPageControl ($renderPageControl) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		$this->renderPageControl = $renderPageControl;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return bool
	 */
	public function GetRenderPageControl () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		return $this->renderPageControl;
	}

	/**
	 * @inheritDocs
	 * @param  bool $renderOrderControl
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetRenderOrderControl ($renderOrderControl) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		$this->renderOrderControl = $renderOrderControl;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return bool
	 */
	public function GetRenderOrderControl () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		return $this->renderOrderControl;
	}

	/**
	 * @inheritDocs
	 * @param  bool $renderCountControl
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetRenderCountControl ($renderCountControl) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		$this->renderCountControl = $renderCountControl;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return bool
	 */
	public function GetRenderCountControl () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		return $this->renderCountControl;
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
	 * @param  string $templateGridContent
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetTemplateGridContent ($templateGridContent) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		$this->templateGridContent = $templateGridContent;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return string
	 */
	public function GetTemplateGridContent () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		return $this->templateGridContent;
	}

	/**
	 * @inheritDocs
	 * @param  string $templatePageControl
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetTemplatePageControl ($templatePageControl) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		$this->templatePageControl = $templatePageControl;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return string
	 */
	public function GetTemplatePageControl () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		return $this->templatePageControl;
	}

	/**
	 * @inheritDocs
	 * @param  string $templateOrderControl
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetTemplateOrderControl ($templateOrderControl) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		$this->templateOrderControl = $templateOrderControl;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return string
	 */
	public function GetTemplateOrderControl () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		return $this->templateOrderControl;
	}

	/**
	 * @inheritDocs
	 * @param  string $templateCountControl
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetTemplateCountControl ($templateCountControl) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		$this->templateCountControl = $templateCountControl;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return string
	 */
	public function GetTemplateCountControl () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		return $this->templateCountControl;
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