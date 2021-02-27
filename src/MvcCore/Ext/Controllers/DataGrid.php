<?php

namespace MvcCore\Ext\Controllers;

class		DataGrid 
extends		\MvcCore\Controller
implements	\MvcCore\Ext\Controllers\IDataGrid {

	use \MvcCore\Ext\Controllers\DataGrid\ConfigProps,
		\MvcCore\Ext\Controllers\DataGrid\ConfigGettersSetters,
		\MvcCore\Ext\Controllers\DataGrid\InternalProps,
		\MvcCore\Ext\Controllers\DataGrid\InternalGettersSetters,
		\MvcCore\Ext\Controllers\DataGrid\ActionMethods,
		\MvcCore\Ext\Controllers\DataGrid\InitMethods,
		\MvcCore\Ext\Controllers\DataGrid\PreDispatchMethods,
		\MvcCore\Ext\Controllers\DataGrid\RenderMethods;

	/**
	 * MvcCore Extension - Controller - DataGrid - version:
	 * Comparison by PHP function version_compare();
	 * @see http://php.net/manual/en/function.version-compare.php
	 */
	const VERSION = '5.0.0';

}
