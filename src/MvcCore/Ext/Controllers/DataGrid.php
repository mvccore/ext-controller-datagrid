<?php

namespace MvcCore\Ext\Controllers;

class		DataGrid 
extends		\MvcCore\Controller
implements	\MvcCore\Ext\Controllers\DataGrid\IConstants {

	use \MvcCore\Ext\Controllers\DataGrid\ConfigProps,
		\MvcCore\Ext\Controllers\DataGrid\ConfigGettersSetters,
		\MvcCore\Ext\Controllers\DataGrid\InternalProps,
		\MvcCore\Ext\Controllers\DataGrid\InternalGettersSetters,
		\MvcCore\Ext\Controllers\DataGrid\InitMethods,
		\MvcCore\Ext\Controllers\DataGrid\PreDispatchMethods;

	
	/**
	 * @inheritDocs
	 * @return void
	 */
	public function PreDispatch () {
		if ($this->dispatchState >= \MvcCore\IController::DISPATCH_STATE_PRE_DISPATCHED) return;
		if (!$this->viewEnabled) return parent::PreDispatch();
		$this->view = new \MvcCore\Ext\Controllers\DataGrids\View;
		parent::PreDispatch();
		
		$this->GetConfigRendering();
		$this->setUpOffsetLimit();
		$this->GetConfigColumns();
		$this->setUpOrdering();
		$this->setUpFiltering();

		if (!$this->LoadModel()) return;

		// set up view props:
		$this->view->grid = $this;
		$this->view->totalCount = $this->totalCount;
		$this->view->pageData = $this->pageData;
	}

	/**
	 * @param  string $controllerOrActionNameDashed 
	 * @param  string $actionNameDashed 
	 * @return string
	 */
	public function Render ($controllerOrActionNameDashed = NULL, $actionNameDashed = NULL): string {
		if ($this->dispatchState < \MvcCore\IController::DISPATCH_STATE_INITIALIZED)
			$this->Init();
		if ($this->dispatchState < \MvcCore\IController::DISPATCH_STATE_PRE_DISPATCHED)
			$this->PreDispatch();
		$this->dispatchState = \MvcCore\IController::DISPATCH_STATE_RENDERED;
		return $this->view->Render('Grid', 'content');
	}
}
