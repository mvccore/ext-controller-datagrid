<?php

/**
 * MvcCore
 *
 * This source file is subject to the BSD 3 License
 * For the full copyright and license information, please view
 * the LICENSE.md file that are distributed with this source code.
 *
 * @copyright	Copyright (c) 2016 Tom Flidr (https://github.com/mvccore)
 * @license		https://mvccore.github.io/docs/mvccore/5.0.0/LICENSE.md
 */

namespace MvcCore\Ext\Controllers\DataGrid;

/**
 * @mixin \MvcCore\Ext\Controllers\DataGrid
 */
trait RenderMethods {
	
	/**
	 * @inheritDocs
	 * @param  string $controllerOrActionNameDashed 
	 * @param  string $actionNameDashed 
	 * @return string
	 */
	public function Render ($controllerOrActionNameDashed = NULL, $actionNameDashed = NULL) {
		if (!$this->renderCheckDispatchState()) return '';

		// Set up view store with parent controller view store, do not overwrite existing keys:
		/** @var \MvcCore\Ext\Controllers\DataGrids\View $view */
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

		// Render grid template and sub templates:
		$result = $view->RenderGrid();

		// Render this view or view with layout by render mode:
		unset($view, $this->view);

		$this->dispatchState = \MvcCore\IController::DISPATCH_STATE_RENDERED;
		return $result;
	}
}