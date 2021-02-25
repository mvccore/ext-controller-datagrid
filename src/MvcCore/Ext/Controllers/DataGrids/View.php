<?php

namespace MvcCore\Ext\Controllers\DataGrids;

class View extends \MvcCore\View {
	
	/**
	 * 
	 * @var string|NULL
	 */
	protected static $gridScriptsFullPathBase = NULL;
	
	/**
	 * 
	 * @var \string[]
	 */
	protected $defaultContentTemplates = [];
	
	/**
	 * 
	 * @var \string[]
	 */
	protected $defaultControlTemplates = [];
	
	/**
	 * 
	 * @var \string[]
	 */
	protected $defaultFilterFormTemplates = [];
	
	/**
	 * 
	 * @return string
	 */
	protected static function GetGridScriptsFullPathBase () {
		if (static::$gridScriptsFullPathBase === NULL)
			static::$gridScriptsFullPathBase = str_replace('\\', '/', __DIR__);
		return static::$gridScriptsFullPathBase;
	}
	
	/**
	 * @param  string $gridScriptsFullPathBase
	 * @return string
	 */
	protected static function SetGridScriptsFullPathBase ($gridScriptsFullPathBase) {
		return static::$gridScriptsFullPathBase = $gridScriptsFullPathBase;
	}
	
	/**
	 * 
	 * @internal
	 * @param  \string[] $defaultContentTemplates
	 * @return \MvcCore\Ext\Controllers\DataGrids\View
	 */
	public function SetDefaultContentTemplates ($defaultContentTemplates) {
		$this->defaultContentTemplates = $defaultContentTemplates;
		return $this;
	}
	
	/**
	 * 
	 * @internal
	 * @param  \string[] $defaultControlTemplates
	 * @return \MvcCore\Ext\Controllers\DataGrids\View
	 */
	public function SetDefaultControlTemplates ($defaultControlTemplates) {
		$this->defaultControlTemplates = $defaultControlTemplates;
		return $this;
	}
	
	/**
	 * 
	 * @internal
	 * @param  \string[] $defaultFilterFormTemplates
	 * @return \MvcCore\Ext\Controllers\DataGrids\View
	 */
	public function SetDefaultFilterFormTemplates ($defaultFilterFormTemplates) {
		$this->defaultFilterFormTemplates = $defaultFilterFormTemplates;
		return $this;
	}
	
	/**
	 * 
	 * @param  string $relativePath
	 * @return string
	 */
	public function & RenderGridContent ($relativePath = '') {
		$internalTemplate = in_array($relativePath, $this->defaultContentTemplates, TRUE);
		$result = $this->Render('Views/Content', $relativePath, $internalTemplate);
		return $result;
	}

	/**
	 * 
	 * @param  string $relativePath
	 * @return string
	 */
	public function & RenderGridControl ($relativePath = '') {
		$internalTemplate = in_array($relativePath, $this->defaultControlTemplates, TRUE);
		$result = $this->Render('Views/Controls', $relativePath, $internalTemplate);
		return $result;
	}

	/**
	 * 
	 * @param  string $relativePath
	 * @return string
	 */
	public function & RenderGridForm ($relativePath = '') {
		$internalTemplate = in_array($relativePath, $this->defaultFilterFormTemplates, TRUE);
		$result = $this->Render('Views/Form', $relativePath, $internalTemplate);
		return $result;
	}

	/**
	 * @inheritDocs
	 * @param  string      $typePath     By default: `"Layouts" | "Scripts"`. It could be `"Forms" | "Forms/Fields"` etc...
	 * @param  string      $relativePath
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