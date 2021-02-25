<?php

namespace MvcCore\Ext\Controllers\DataGrid;

trait RenderMethods {
	
	/**
	 * @param  string $controllerOrActionNameDashed 
	 * @param  string $actionNameDashed 
	 * @return string
	 */
	public function Render ($controllerOrActionNameDashed = NULL, $actionNameDashed = NULL) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		if (!$this->renderCheckDispatchState()) return '';

		// Set up view store with parent controller view store, do not overwrite existing keys:
		/** @var $view \MvcCore\Ext\Controllers\DataGrids\View */
		$view = $this->view;
		$view->SetUpStore($this->parentController->GetView(), FALSE);

		// Set up child controllers into view if any of them is named by string index:
		foreach ($this->childControllers as $ctrlKey => $childCtrl) {
			if (!is_numeric($ctrlKey) && !isset($view->{$ctrlKey}))
				$view->{$ctrlKey} = $childCtrl;
		}
		
		// Set up view rendering:
		$view->SetUpRender(
			$this->renderMode, $controllerOrActionNameDashed, $actionNameDashed
		);

		// Complete view script path an render it by rendering mode:
		$viewScriptPath = $this->configRendering->GetTemplateGridContent();
		if ($viewScriptPath === \MvcCore\Ext\Controllers\DataGrid\IConstants::TEMPLATE_CONTENT_DEFAULT) {
			$result = $view->RenderGridContent($viewScriptPath);

		} else {
			$firstChar = mb_substr($viewScriptPath, 0, 1);
			if ($firstChar === '/')
				$viewScriptPath = ltrim($viewScriptPath, '/');
			if ($firstChar !== '/' && $firstChar !== '.')
				$viewScriptPath = $this->GetViewScriptPath(
					$this->parentController->GetControllerName(), 
					$viewScriptPath
				);
			$result = $view->RenderScript($viewScriptPath);
		}

		// Render this view or view with layout by render mode:
		unset($view, $view);

		$this->dispatchState = \MvcCore\IController::DISPATCH_STATE_RENDERED;
		return $result;
	}
}