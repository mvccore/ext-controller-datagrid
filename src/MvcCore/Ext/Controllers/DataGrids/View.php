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
			$this->gridScriptsFullPathBase = str_replace('\\', '/', __DIR__) . '/Views';
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
		return $this->renderGridTemplate(
			$this->configRendering->GetTemplateGridContent(), 'Content', 'grid'
		);
	}
	
	/**
	 * @return string
	 */
	public function RenderGridHeadTable () {
		if (!$this->configRendering->GetRenderTableHeadOrdering()) 
			return '';
		return $this->renderGridTemplate(
			$this->configRendering->GetTemplateTableHead(), 'Content', 'table-head'
		);
	}
	
	/**
	 * @return string
	 */
	public function RenderGridBodyTable () {
		return $this->renderGridTemplate(
			$this->configRendering->GetTemplateTableBody(), 'Content', 'table-body'
		);
	}
	
	/**
	 * @return string
	 */
	public function RenderGridBodyGrid () {
		return $this->renderGridTemplate(
			$this->configRendering->GetTemplateGridBody(), 'Content', 'grid-body'
		);
	}
	
	/**
	 * @return string
	 */
	public function RenderGridControlOrdering () {
		if (!$this->configRendering->GetRenderControlOrdering()) 
			return '';
		ob_start();
		echo $this->renderGridTemplate(
			$this->configRendering->GetTemplateControlOrdering(), 'Controls', 'ordering'
		);
		return ob_get_clean();
	}
	
	/**
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