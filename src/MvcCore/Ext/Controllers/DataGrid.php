<?php

namespace MvcCore\Ext\Controllers;

class		DataGrid 
extends		\MvcCore\Controller
implements	\MvcCore\Ext\Controllers\DataGrid\IConstants {

	use \MvcCore\Ext\Controllers\DataGrid\ConfigProps,
		\MvcCore\Ext\Controllers\DataGrid\ConfigGettersSetters,
		\MvcCore\Ext\Controllers\DataGrid\internalProps,
		\MvcCore\Ext\Controllers\DataGrid\internalGettersSetters,
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
		
		$this->GetRenderConfig();

		// TODO: nastavit limit a offset a zpracovat nastavení sloupců, ordering a filtering
		$this->setUpOffsetLimit();
		//$this->setUpColumnsConfig();
		//$this->setUpOrdering();
		//$this->setUpFiltering();

		$this->LoadModel();

		$this->view->grid = $this;
	}

	/**
	 * @throws \InvalidArgumentException 
	 * @return \MvcCore\Ext\Controllers\DataGrid
	 */
	public function LoadModel () {
		if ($this->model === NULL || $this->model instanceof \MvcCore\Ext\Controllers\DataGrids\IModel)
			throw new \InvalidArgumentException("No model defined or model doesn't implement `\\MvcCore\\Ext\\Controllers\\DataGrids\\IModel`.");
		/*$this->model->SetOffset();
		$this->model->SetLimit();
		$this->model->SetFiltering();
		$this->model->SetOrdering();
		$this->model->GetTotalCount();
		$this->model->GetPageData();*/
		return $this;
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
		return $this->view->Render('Views', 'grid');
	}
}
