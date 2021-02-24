<?php

namespace MvcCore\Ext\Controllers\DataGrids;

class View extends \MvcCore\View {
	
	/**
	 * @inheritDocs
	 * @var string|NULL
	 */
	protected static $viewScriptsFullPathBase = NULL;

	/**
	 * @inheritDocs
	 * @return void
	 */
	protected static function initViewScriptsFullPathBase () {
		static::$viewScriptsFullPathBase = str_replace('\\', '/', __DIR__) . '/Views';
	}
	
	/**
	 * @inheritDocs
	 * @param  string $relativePath
	 * @return string
	 */
	public function & RenderControl ($relativePath = '') {
		//return $this->Render(static::$scriptsDir, $relativePath);
		return $this->Render('Controls', $relativePath);
	}

	public function & Render ($typePath = '', $relativePath = '') {
		if ($relativePath === 'Grid/content') {
			return parent::Render('Grid', 'content');
		} else {
			return parent::Render($typePath, $relativePath);	
		}
	}
}