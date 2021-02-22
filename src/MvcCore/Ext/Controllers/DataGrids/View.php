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
		static::$viewScriptsFullPathBase = str_replace('\\', '/', __DIR__);
	}
}