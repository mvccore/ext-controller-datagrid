<?php

namespace MvcCore\Ext\Controllers;

class DataGrid extends \MvcCore\Controller {

	use \MvcCore\Ext\Controllers\DataGrid\Props,
		\MvcCore\Ext\Controllers\DataGrid\GettersSetters;

	/**
	 * Create \MvcCore\Ext\Controllers\DataGrid instance.
	 * @param  \MvcCore\Controller|NULL $controller
	 * @return void
	 */
	public function __construct (\MvcCore\IController $controller = NULL) {
		/** @var $controller \MvcCore\Controller */
		if ($controller === NULL) {
			$controller = \MvcCore\Ext\Form::GetCallerControllerInstance();
			if ($controller === NULL) 
				$controller = \MvcCore\Application::GetInstance()->GetController();
			if ($controller === NULL) $this->throwNewInvalidArgumentException(
				'There was not possible to determinate caller controller, '
				.'where is datagrid instance created. Provide `$controller` instance explicitly '
				.'by first `\MvcCore\Ext\Controllers\DataGrid::__construct($controller);` argument.'
			);
		}
		$controller->AddChildController($this);
	}

	/**
	 * Throw new `\InvalidArgumentException` with given
	 * error message and append automatically current class name,
	 * current form id and form class type.
	 * @param  string $errorMsg 
	 * @throws \InvalidArgumentException 
	 */
	protected function throwNewInvalidArgumentException ($errorMsg) {
		$str = '['.get_class().'] ' . $errorMsg . ' ('
			. 'form id: `'.$this->id . '`, '
			. 'form type: `'.get_class($this->form).'`'
		.')';
		throw new \InvalidArgumentException($str);
	}

	/**
	 * @inheritDocs
	 * @return void
	 */
	public function Init () {
		if ($this->dispatchState > \MvcCore\IController::DISPATCH_STATE_CREATED) return;
		parent::Init();
		

	}

	/**
	 * @inheritDocs
	 * @return void
	 */
	public function PreDispatch () {
		if ($this->dispatchState >= \MvcCore\IController::DISPATCH_STATE_PRE_DISPATCHED) return;
		$this->view = new \MvcCore\Ext\Controllers\DataGrids\View;
		parent::PreDispatch();
		x($this);
	}

	/**
	 * @param string $controllerOrActionNameDashed 
	 * @param string $actionNameDashed 
	 */
	public function Render ($controllerOrActionNameDashed = NULL, $actionNameDashed = NULL): string {
		if ($this->dispatchState < \MvcCore\IController::DISPATCH_STATE_INITIALIZED)
			$this->Init();
		if ($this->dispatchState < \MvcCore\IController::DISPATCH_STATE_PRE_DISPATCHED)
			$this->PreDispatch();
		$this->dispatchState = \MvcCore\IController::DISPATCH_STATE_RENDERED;
		return $this->view->Render(__DIR__ . '/DataGrids/Views', 'grid.phtml');
	}
}
