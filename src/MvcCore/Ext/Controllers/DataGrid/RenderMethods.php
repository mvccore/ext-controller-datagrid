<?php

namespace MvcCore\Ext\Controllers\DataGrid;

trait RenderMethods {
	
	/**
	 * @param  string $controllerOrActionNameDashed 
	 * @param  string $actionNameDashed 
	 * @return string
	 */
	public function Render ($controllerOrActionNameDashed = 'Grid', $actionNameDashed = 'content') {
		if (!$this->renderCheckDispatchState()) return '';

		// Set up view store with parent controller view store, do not overwrite existing keys:
		$this->view->SetUpStore($this->parentController->GetView(), FALSE);

		// Set up child controllers into view if any of them is named by string index:
		foreach ($this->childControllers as $ctrlKey => $childCtrl) {
			if (!is_numeric($ctrlKey) && !isset($this->view->{$ctrlKey}))
				$this->view->{$ctrlKey} = $childCtrl;
		}
		
		// Complete view script path an set up view rendering:
		$viewScriptPath = $this->GetViewScriptPath($controllerOrActionNameDashed, $actionNameDashed);
		$this->view->SetUpRender(
			$this->renderMode, $controllerOrActionNameDashed, $actionNameDashed
		);
			
		// Render this view or view with layout by render mode:
		$result = $this->view->RenderScript($viewScriptPath);
		unset($this->view);

		$this->dispatchState = \MvcCore\IController::DISPATCH_STATE_RENDERED;
		return $result;
	}
}