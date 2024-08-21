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
	 * @inheritDoc
	 * @param  string $controllerOrActionNameDashed 
	 * @param  string $actionNameDashed 
	 * @return string
	 */
	public function Render ($controllerOrActionNameDashed = NULL, $actionNameDashed = NULL) {
		if (!$this->viewEnabled || !$this->DispatchStateCheck(static::DISPATCH_STATE_RENDERED)) 
			return '';
		
		// Set up view store with parent controller view store, do not overwrite existing keys:
		/** @var \MvcCore\Ext\Controllers\DataGrids\View $view */
		$view = $this->view;
		$parentCtrlView = $this->parentController->GetView();
		$view->SetUpStore($parentCtrlView, FALSE);
		$view->view = $parentCtrlView;
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

		unset($view, $this->view);
		
		$this->renderDevClientRowModelDefinition();

		$this->dispatchMoveState(static::DISPATCH_STATE_RENDERED);
		return $result;
	}

	/**
	 * Initialize row model client TypeScript code definition if necessary.
	 * @return void
	 */
	protected function renderDevClientRowModelDefinition () {
		if (
			!$this->environment->IsDevelopment() || 
			$this->handlerClientRowModelDefinition === NULL
		) return;
		$generatorClass = static::$toolsTsGeneratorClass;
		if (!class_exists($generatorClass)) throw new \RuntimeException(
			"Class `$generatorClass` not installed, please install ".
			"composer package `mvccore/ext-tool-ts-generator`."
		);
		$rowFullClassName = $this->rowClass;
		$rowClassPropsFlags = $this->rowClassPropsFlags !== 0
			? $this->rowClassPropsFlags
			: ($this->rowClassIsExtendedModel
				? $rowFullClassName::GetDefaultPropsFlags()
				: \MvcCore\IModel::PROPS_INHERIT_PROTECTED
			);
		call_user_func_array($this->handlerClientRowModelDefinition, [
			$generatorClass, $rowFullClassName, $rowClassPropsFlags
		]);
	}

}