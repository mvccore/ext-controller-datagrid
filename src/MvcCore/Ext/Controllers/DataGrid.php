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
	 * 
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Models\IGridModel $model 
	 * @param  array                                                $propsNamesAndDbColumnNames
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column[]
	 */
	public static function ParseConfigColumns (\MvcCore\Ext\Controllers\DataGrids\Models\IGridModel $model, $propsNamesAndDbColumnNames = []) {
		$modelType = new \ReflectionClass($model);
		$props = $modelType->getProperties(\ReflectionProperty::IS_PRIVATE | \ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PUBLIC);
		$toolClass = \MvcCore\Application::GetInstance()->GetToolClass();
		$attrsAnotations = $toolClass::GetAttributesAnotations();
		foreach ($props as $prop) {
			if ($prop->isStatic()) continue;
			x($prop);
			$args = \MvcCore\Tool::GetPropertyAttrsArgs(
				$model, $prop->name, [
					\MvcCore\Ext\Controllers\DataGrids\Configs\Column::class
				], true
			);
			x($args);
		}
	}

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

		// TODO: nastavit limit a offset a zpracovat nastavení sloupců, ordering a filtering
		$this->setUpOffsetLimit();
		$this->GetConfigColumns();
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
		$model = $this->GetModel(TRUE);

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
		return $this->view->Render('Grid', 'content');
	}
}
