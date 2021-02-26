<?php

namespace MvcCore\Ext\Controllers\DataGrids;

class View extends \MvcCore\View {
	
	/**
	 * 
	 * @var string|NULL
	 */
	protected $gridScriptsFullPathBase = NULL;
	
	/**
	 * 
	 * @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering|NULL
	 */
	protected $configRendering = NULL;
	
	/**
	 * 
	 * @param  string $gridScriptsFullPathBase
	 * @return \MvcCore\Ext\Controllers\DataGrids\View
	 */
	public function SetGridScriptsFullPathBase ($gridScriptsFullPathBase) {
		$this->gridScriptsFullPathBase = $gridScriptsFullPathBase;
		return $this;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function GetGridScriptsFullPathBase () {
		if ($this->gridScriptsFullPathBase === NULL)
			$this->gridScriptsFullPathBase = str_replace('\\', '/', __DIR__);
		return $this->gridScriptsFullPathBase;
	}
	
	/**
	 * 
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering $configRendering 
	 * @return \MvcCore\Ext\Controllers\DataGrids\View
	 */
	public function SetConfigRendering (\MvcCore\Ext\Controllers\DataGrids\Configs\IRendering $configRendering) {
		$this->configRendering = $configRendering;
		return $this;
	}

	/**
	 * 
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering
	 */
	public function GetConfigRendering () {
		return $this->configRendering;
	}

	/**
	 * @return string
	 */
	public function RenderGrid () {
		// Complete view script path an render it by rendering mode:
		$viewScriptPath = $this->configRendering->GetTemplateGridContent();
		if ($viewScriptPath === NULL) {
			return $this->Render('Content', 'grid', TRUE);
		} else {
			return $this->RenderScript($viewScriptPath);
		}
	}







	/**
	 * @inheritDocs
	 * @param  string $typePath     By default: `"Layouts" | "Scripts"`. It could be `"Forms" | "Forms/Fields"` etc...
	 * @param  string $relativePath
	 * @param  bool   $internalTemplate
	 * @throws \InvalidArgumentException Template not found in path: `$viewScriptFullPath`.
	 * @return string
	 */
	public function & Render ($typePath = '', $relativePath = '', $internalTemplate = FALSE) {
		/** @var $this \MvcCore\View */
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