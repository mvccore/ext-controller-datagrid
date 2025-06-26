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
	 * Grid content view type.
	 * @var int 
	 */
	const VIEW_TYPE_GRID_CONTENT		= 32;
	
	/**
	 * Grid control view type.
	 * @var int 
	 */
	const VIEW_TYPE_GRID_CONTROL		= 64;
	
	/**
	 * Grid form view type.
	 * @var int 
	 */
	const VIEW_TYPE_GRID_FORM			= 128;
	
	/**
	 * Grid view types to package view scripts paths.
	 * @var array<int,string>
	 */
	protected static $viewTypes2AppPaths= [
		self::VIEW_TYPE_GRID_CONTENT	=> '~/MvcCore/Ext/Controllers/DataGrids/Views/Content',
		self::VIEW_TYPE_GRID_CONTROL	=> '~/MvcCore/Ext/Controllers/DataGrids/Views/Controls',
		self::VIEW_TYPE_GRID_FORM		=> '~/MvcCore/Ext/Controllers/DataGrids/Views/Form',
	];

	/**
	 * Internal cache array for templates full path bases to speed up
	 * multiple method calls for `static::getGridScriptsFullPathBase();`.
	 * Key is current view class full name, value is composer package root path.
	 * @var array<string, string>
	 */
	protected static $gridScriptsFullPathBases	= [];
	
	/**
	 * Grid rendering config pointer.
	 * @var \MvcCore\Ext\Controllers\DataGrids\Configs\Rendering|NULL
	 */
	protected $configRendering			= NULL;
	
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
			self::VIEW_TYPE_GRID_CONTENT, 
			$this->configRendering->GetTemplateContent(), 
			'grid'
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
			self::VIEW_TYPE_GRID_CONTENT, 
			$this->configRendering->GetTemplateTableHead(), 
			'table-head'
		);
	}
	
	/**
	 * Render datagrid table type table body.
	 * @return string
	 */
	public function RenderGridBodyTable () {
		return $this->renderGridTemplate(
			self::VIEW_TYPE_GRID_CONTENT, 
			$this->configRendering->GetTemplateTableBody(), 
			'table-body'
		);
	}
	
	/**
	 * Render datagrid grid type table body.
	 * @return string
	 */
	public function RenderGridBodyGrid () {
		return $this->renderGridTemplate(
			self::VIEW_TYPE_GRID_CONTENT, 
			$this->configRendering->GetTemplateGridBody(), 
			'grid-body'
		);
	}
	
	/**
	 * Render datagrid separated sort control.
	 * @return string
	 */
	public function RenderGridControlSorting () {
		if (!$this->configRendering->GetRenderControlSorting()) 
			return '';
		return $this->renderGridTemplate(
			self::VIEW_TYPE_GRID_CONTROL, 
			$this->configRendering->GetTemplateControlSorting(), 
			'sorting'
		);
	}
	
	/**
	 * Render datagrid items per page control.
	 * @return string
	 */
	public function RenderGridControlCountScales () {
		if (!$this->configRendering->GetRenderControlCountScales()) 
			return '';
		return $this->renderGridTemplate(
			self::VIEW_TYPE_GRID_CONTROL, 
			$this->configRendering->GetTemplateControlCountScales(), 
			'count-scales'
		);
	}
	
	/**
	 * Render datagrid paging control.
	 * @return string
	 */
	public function RenderGridControlPaging () {
		if (!$this->configRendering->GetRenderControlPaging()) 
			return '';
		return $this->renderGridTemplate(
			self::VIEW_TYPE_GRID_CONTROL, 
			$this->configRendering->GetTemplateControlPaging(), 
			'paging'
		);
	}
	
	/**
	 * Render datagrid status text control.
	 * @return string
	 */
	public function RenderGridControlStatus () {
		if (!$this->configRendering->GetRenderControlStatus()) 
			return '';
		return $this->renderGridTemplate(
			self::VIEW_TYPE_GRID_CONTROL, 
			$this->configRendering->GetTemplateControlStatus(), 
			'status'
		);
	}
	
	/**
	 * Render datagrid custom filter form control.
	 * @return string
	 */
	public function RenderGridFilterForm () {
		if (!$this->configRendering->GetRenderFilterForm()) 
			return '';
		return $this->renderGridTemplate(
			self::VIEW_TYPE_GRID_FORM, 
			$this->configRendering->GetTemplateFilterForm(), 
			'filter'
		);
	}
	
	/**
	 * Render internal or custom template.
	 * @internal
	 * @return string
	 */
	protected function renderGridTemplate ($viewType, $configTemplate, $defaultTemplate, array $variables = []) {
		if (count($variables) > 0) {
			$currentStore = & $this->__protected['store'];
			// always overvrite existing keys:
			$this->__protected['store'] = array_merge($currentStore, $variables);
		}
		if ($configTemplate !== NULL) {
			return $this->Render(static::VIEW_TYPE_SCRIPT, $configTemplate);

		} else {
			$viewTypeWithPkg = $viewType | static::VIEW_TYPE_PACKAGE;
			/**
			 * If there is not prepared view dir full path for internal grid template
			 * by method `$view::GetTypedViewsDirFullPath()`, prepare it directly here
			 * to customize and speed up template path setup.
			 */
			if (isset($this->__protected['viewsDirsFullPaths']))
				$this->__protected['viewsDirsFullPaths'] = [];
			$viewsDirsFullPaths = & $this->__protected['viewsDirsFullPaths'];
			if (!isset($viewsDirsFullPaths[$viewTypeWithPkg])) {
				$viewsDirsFullPaths[$viewTypeWithPkg] = static::getViewPathByType(
					$this->controller->GetApplication(), $viewTypeWithPkg, TRUE
				);
			}
			return $this->Render($viewTypeWithPkg, $defaultTemplate);
		}
	}

	/**
	 * If view type contains view in package flag, remove the flag and return 
	 * path to local template, absolute or relative into this composer package.
	 * @param  \MvcCore\Application $app 
	 * @param  int                  $viewType 
	 * @param  bool                 $absolute 
	 * @return string
	 */
	protected static function getViewPathByType (
		\MvcCore\IApplication $app, $viewType, $absolute
	) {
		$viewInsidePackage = ($viewType & static::VIEW_TYPE_PACKAGE) != 0;
		if (!$viewInsidePackage) {
			return parent::getViewPathByType($app, $viewType, $absolute);
		} else {
			$viewTypeNoPkg = $viewType ^ static::VIEW_TYPE_PACKAGE;
			$packagePath = static::$viewTypes2AppPaths[$viewTypeNoPkg];
			if ($absolute && mb_strpos($packagePath, '~/') === 0) {
				$gridScriptsFullPathBase = static::getGridScriptsFullPathBase();
				$packagePath = $gridScriptsFullPathBase . mb_substr($packagePath, 1);
			}
			return $packagePath;
		}
	}

	/**
	 * Return composer package root dir by currently rendered class.
	 * Use internal static cache to speed up this method for multiple calls.
	 * @return string
	 */
	protected static function getGridScriptsFullPathBase () {
		$viewClassFullName = get_called_class();
		if (isset(static::$gridScriptsFullPathBases[$viewClassFullName]))
			return static::$gridScriptsFullPathBases[$viewClassFullName];
		$type = new \ReflectionClass($viewClassFullName);
		$gridScriptsFullPathBase = str_replace('\\', '/', dirname($type->getFileName(), 5));
		return static::$gridScriptsFullPathBases[$viewClassFullName] = $gridScriptsFullPathBase;
	}

}