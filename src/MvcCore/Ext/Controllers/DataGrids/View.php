<?php

namespace MvcCore\Ext\Controllers\DataGrids;

class View extends \MvcCore\View {
	
	/**
	 * 
	 * @var string|NULL
	 */
	protected static $localFullPathBase = NULL;
	
	/**
	 * 
	 * @var string|NULL
	 */
	protected static $origFullPathBase = NULL;
	
	/**
	 * 
	 * @return string
	 */
	protected static function getLocalFullPathBase () {
		if (static::$localFullPathBase === NULL)
			static::$localFullPathBase = str_replace('\\', '/', __DIR__) . '/Views';
		return static::$localFullPathBase;
	}
	
	/**
	 * 
	 * @return void
	 */
	protected static function changeFullPathBaseTolocal () {
		static::$origFullPathBase = static::$viewScriptsFullPathBase;
		static::$viewScriptsFullPathBase = static::getLocalFullPathBase();
	}
	
	/**
	 * 
	 * @return void
	 */
	protected static function changeFullPathBaseToOrig () {
		static::$viewScriptsFullPathBase = static::$origFullPathBase;
	}
	
	/**
	 * 
	 * @param  string $relativePath
	 * @return string
	 */
	public function & RenderGridContent ($relativePath = '') {
		static::changeFullPathBaseTolocal();
		$result = $this->Render('Grid/Content', $relativePath);
		static::changeFullPathBaseToOrig();
		return $result;
	}

	/**
	 * 
	 * @param  string $relativePath
	 * @return string
	 */
	public function & RenderGridControl ($relativePath = '') {
		static::changeFullPathBaseTolocal();
		$result = $this->Render('Grid/Controls', $relativePath);
		static::changeFullPathBaseToOrig();
		return $result;
	}

	/**
	 * 
	 * @param  string $relativePath
	 * @return string
	 */
	public function & RenderGridForm ($relativePath = '') {
		static::changeFullPathBaseTolocal();
		$result = $this->Render('Grid/Form', $relativePath);
		static::changeFullPathBaseToOrig();
		return $result;
	}
}