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
	 * @var string|NULL
	 */
	protected static $origScriptsFullPathBase = NULL;
	
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
	 * @return void
	 */
	protected static function changeScriptsFullPathBaseToGrid () {
		static::$origScriptsFullPathBase = static::$viewScriptsFullPathBase;
		static::$viewScriptsFullPathBase = static::GetGridScriptsFullPathBase();
	}
	
	/**
	 * 
	 * @return void
	 */
	protected static function changeScriptsFullPathBaseToOrig () {
		static::$viewScriptsFullPathBase = static::$origScriptsFullPathBase;
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
		static::changeScriptsFullPathBaseToGrid();
		$result = $this->Render('Views/Content', $relativePath);
		static::changeScriptsFullPathBaseToOrig();
		return $result;
	}

	/**
	 * 
	 * @param  string $relativePath
	 * @return string
	 */
	public function & RenderGridControl ($relativePath = '') {
		$defaultTemplate = in_array($relativePath, $this->defaultControlTemplates, TRUE);
		if ($defaultTemplate) static::changeScriptsFullPathBaseToGrid();
		$result = $this->Render('Views/Controls', $relativePath);
		if ($defaultTemplate) static::changeScriptsFullPathBaseToOrig();
		return $result;
	}

	/**
	 * 
	 * @param  string $relativePath
	 * @return string
	 */
	public function & RenderGridForm ($relativePath = '') {
		$defaultTemplate = in_array($relativePath, $this->defaultFilterFormTemplates, TRUE);
		if ($defaultTemplate) static::changeScriptsFullPathBaseToGrid();
		$result = $this->Render('Views/Form', $relativePath);
		if ($defaultTemplate) static::changeScriptsFullPathBaseToOrig();
		return $result;
	}
}