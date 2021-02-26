<?php

namespace MvcCore\Ext\Controllers\DataGrids\Configs;

class		Rendering
implements	\MvcCore\Ext\Controllers\DataGrids\Configs\IRendering {
	
	/**
	 * 
	 * @var int
	 */
	protected $type							= \MvcCore\Ext\Controllers\IDataGrid::TYPE_TABLE;

	/**
	 * 
	 * @var int
	 */
	protected $gridColumnsCount				= 3;

	/**
	 * 
	 * @var bool
	 */
	protected $renderTableHeadOrdering		= FALSE;

	/**
	 * 
	 * @var bool
	 */
	protected $renderControlOrdering		= TRUE;

	/**
	 * 
	 * @var bool
	 */
	protected $renderControlPaging			= TRUE;
	
	/**
	 * 
	 * @var bool
	 */
	protected $renderControlCountScales		= TRUE;
	
	/**
	 * 
	 * @var bool
	 */
	protected $renderFilterForm				= FALSE;
	

	/**
	 * 
	 * @var string
	 */
	protected $templateGridContent			= NULL;
	
	/**
	 * 
	 * @var string
	 */
	protected $templateTableHead			= NULL;
	
	/**
	 * 
	 * @var string
	 */
	protected $templateTableBody			= NULL;
	
	/**
	 * 
	 * @var string
	 */
	protected $templateGridHead				= NULL;
	
	/**
	 * 
	 * @var string
	 */
	protected $templateGridBody				= NULL;
	
	/**
	 * 
	 * @var string
	 */
	protected $templateControlPaging		= NULL;
	
	/**
	 * 
	 * @var string
	 */
	protected $templateControlOrdering		= NULL;
	
	/**
	 * 
	 * @var string
	 */
	protected $templateControlCountScales	= NULL;
	
	/**
	 * 
	 * @var string
	 */
	protected $templateFilterForm			= NULL;
	
	/**
	 * 
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
	 * @param  int $columnsCount
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetGridColumnsCount ($columnsCount) {
		$this->columnsCount = $columnsCount;
		return $this;
	}
	
	/**
	 * @inheritDocs
	 * @return int
	 */
	public function GetGridColumnsCount () {
		return $this->columnsCount;
	}
	
	/**
	 * @inheritDocs
	 * @param  bool $renderTableHeadOrdering
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetRenderTableHeadOrdering ($renderTableHeadOrdering) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		$this->renderTableHeadOrdering = $renderTableHeadOrdering;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return bool
	 */
	public function GetRenderTableHeadOrdering () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		return $this->renderTableHeadOrdering;
	}

	/**
	 * @inheritDocs
	 * @param  bool $renderControlOrdering
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetRenderControlOrdering ($renderControlOrdering) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		$this->renderControlOrdering = $renderControlOrdering;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return bool
	 */
	public function GetRenderControlOrdering () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		return $this->renderControlOrdering;
	}

	/**
	 * @inheritDocs
	 * @param  bool $renderControlPaging
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetRenderControlPaging ($renderControlPaging) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		$this->renderControlPaging = $renderControlPaging;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return bool
	 */
	public function GetRenderControlPaging () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		return $this->renderControlPaging;
	}

	/**
	 * @inheritDocs
	 * @param  bool $renderControlCountScales
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetRenderControlCountScales ($renderControlCountScales) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		$this->renderControlCountScales = $renderControlCountScales;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return bool
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
	 * @param  string $templateGridHead
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetTemplateGridHead ($templateGridHead) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		$this->templateGridHead = $templateGridHead;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return string
	 */
	public function GetTemplateGridHead () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		return $this->templateGridHead;
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
	 * @param  string $templateControlOrdering
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function SetTemplateControlOrdering ($templateControlOrdering) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		$this->templateControlOrdering = $templateControlOrdering;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return string
	 */
	public function GetTemplateControlOrdering () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering */
		return $this->templateControlOrdering;
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