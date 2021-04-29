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

namespace MvcCore\Ext\Controllers\DataGrids;

class View extends \MvcCore\View {
	
	/**
	 * Internal templates full path base.
	 * @var string|NULL
	 */
	protected $gridScriptsFullPathBase = NULL;
	
	/**
	 * Grid rendering config pointer.
	 * @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering|NULL
	 */
	protected $configRendering = NULL;
	
	/**
	 * Set custom internal templates full path base.
	 * @internal
	 * @param  string $gridScriptsFullPathBase
	 * @return \MvcCore\Ext\Controllers\DataGrids\View
	 */
	public function SetGridScriptsFullPathBase ($gridScriptsFullPathBase) {
		$this->gridScriptsFullPathBase = $gridScriptsFullPathBase;
		return $this;
	}
	
	/**
	 * Get custom internal templates full path base.
	 * @internal
	 * @return string
	 */
	public function GetGridScriptsFullPathBase () {
		if ($this->gridScriptsFullPathBase === NULL)
			$this->gridScriptsFullPathBase = str_replace('\\', '/', __DIR__) . '/Views';
		return $this->gridScriptsFullPathBase;
	}
	
	/**
	 * Set datagrid rendering config.
	 * This internal method is always called from datagrid component.
	 * @internal
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $configRendering 
	 * @return \MvcCore\Ext\Controllers\DataGrids\View
	 */
	public function SetConfigRendering (\MvcCore\Ext\Controllers\DataGrids\Configs\IRendering $configRendering) {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $configRendering */
		$this->configRendering = $configRendering;
		return $this;
	}

	/**
	 * Get datagrid rendering config.
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function GetConfigRendering () {
		return $this->configRendering;
	}

	/**
	 * Render datagrid base content.
	 * @return string
	 */
	public function RenderGrid () {
		return $this->renderGridTemplate(
			$this->configRendering->GetTemplateContent(), 'Content', 'grid'
		);
	}
	
	/**
	 * Render datagrid table type table head.
	 * @return string
	 */
	public function RenderGridHeadTable () {
		if (!$this->configRendering->GetRenderTableHead()) 
			return '';
		return $this->renderGridTemplate(
			$this->configRendering->GetTemplateTableHead(), 'Content', 'table-head'
		);
	}
	
	/**
	 * Render datagrid table type table body.
	 * @return string
	 */
	public function RenderGridBodyTable () {
		return $this->renderGridTemplate(
			$this->configRendering->GetTemplateTableBody(), 'Content', 'table-body'
		);
	}
	
	/**
	 * Render datagrid grid type table body.
	 * @return string
	 */
	public function RenderGridBodyGrid () {
		return $this->renderGridTemplate(
			$this->configRendering->GetTemplateGridBody(), 'Content', 'grid-body'
		);
	}
	
	/**
	 * Render datagrid separated sort control.
	 * @return string
	 */
	public function RenderGridControlSorting () {
		if (!$this->configRendering->GetRenderControlSorting()) 
			return '';
		ob_start();
		echo $this->renderGridTemplate(
			$this->configRendering->GetTemplateControlSorting(), 'Controls', 'sorting'
		);
		return ob_get_clean();
	}
	
	/**
	 * Render datagrid items per page control.
	 * @return string
	 */
	public function RenderGridControlCountScales () {
		if (!$this->configRendering->GetRenderControlCountScales()) 
			return '';
		ob_start();
		echo $this->renderGridTemplate(
			$this->configRendering->GetTemplateControlCountScales(), 'Controls', 'count-scales'
		);
		return ob_get_clean();
	}
	
	/**
	 * Render datagrid paging control.
	 * @return string
	 */
	public function RenderGridControlPaging () {
		if (!$this->configRendering->GetRenderControlPaging()) 
			return '';
		ob_start();
		echo $this->renderGridTemplate(
			$this->configRendering->GetTemplateControlPaging(), 'Controls', 'paging'
		);
		return ob_get_clean();
	}
	
	/**
	 * Render datagrid custom filter form control.
	 * @return string
	 */
	public function RenderGridFilterForm () {
		return $this->renderGridTemplate(
			$this->configRendering->GetTemplateFilterForm(), 'form', 'filter'
		);
	}
	
	/**
	 * Render internal or custom template.
	 * @internal
	 * @return string
	 */
	protected function renderGridTemplate ($configTemplate, $typePath, $defaultTemplate) {
		return $configTemplate === NULL
			? $this->Render($typePath, $defaultTemplate, TRUE)
			: $this->RenderScript($configTemplate);
	}

	/**
	 * @inheritDocs
	 * @param  string $typePath     By default: `"Layouts" | "Scripts"`. It could be `"Forms" | "Forms/Fields"` etc...
	 * @param  string $relativePath
	 * @param  bool   $internalTemplate
	 * @throws \InvalidArgumentException Template not found in path: `$viewScriptFullPath`.
	 * @return string
	 */
	public function & Render ($typePath, $relativePath, $internalTemplate = FALSE) {
		/** @var \MvcCore\View $this */
		if (!$internalTemplate)
			$typePath = static::$scriptsDir;
		$result = '';

		if ($internalTemplate) {
			$viewScriptFullPath = implode('/', [
				static::GetGridScriptsFullPathBase(),
				$typePath,
				$relativePath . static::$extension
			]);
		} else {
			$relativePath = $this->correctRelativePath(
				$typePath, $relativePath
			);
			$viewScriptFullPath = static::GetViewScriptFullPath($typePath, $relativePath);
		}

		if (!file_exists($viewScriptFullPath)) {
			throw new \InvalidArgumentException(
				"[".get_class()."] Template not found in path: `{$viewScriptFullPath}`."
			);
		}

		$renderedFullPaths = & $this->__protected['renderedFullPaths'];
		$renderedFullPaths[] = $viewScriptFullPath;
		// get render mode
		list($renderMode) = $this->__protected['renderArgs'];
		$renderModeWithOb = ($renderMode & \MvcCore\IView::RENDER_WITH_OB_FROM_ACTION_TO_LAYOUT) != 0;

		// if render mode is default - start output buffering
		if ($renderModeWithOb)
			ob_start();
		// render the template with local variables from the store
		$result = call_user_func(function ($viewPath, $controller, $helpers) {
			extract($helpers, EXTR_SKIP);
			unset($helpers);
			extract($this->__protected['store'], EXTR_SKIP);
			include($viewPath);
		}, $viewScriptFullPath, $this->controller, $this->__protected['helpers']);

		// if render mode is default - get result from output buffer and return the result,
		// if render mode is continuous - result is sent to client already, so return empty string only.
		if ($renderModeWithOb) {
			$result = ob_get_clean();
			\array_pop($renderedFullPaths); // unset last
			return $result;
		} else {
			$result = '';
			\array_pop($renderedFullPaths); // unset last
			return $result;
		}
	}

}