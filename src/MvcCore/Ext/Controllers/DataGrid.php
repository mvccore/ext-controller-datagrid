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

namespace MvcCore\Ext\Controllers;

class		DataGrid 
extends		\MvcCore\Controller
implements	\MvcCore\Ext\Controllers\IDataGrid {

	use \MvcCore\Ext\Controllers\DataGrid\ConfigProps,
		\MvcCore\Ext\Controllers\DataGrid\ConfigGettersSetters,
		\MvcCore\Ext\Controllers\DataGrid\InternalProps,
		\MvcCore\Ext\Controllers\DataGrid\InternalGettersSetters,
		\MvcCore\Ext\Controllers\DataGrid\ActionMethods,
		\MvcCore\Ext\Controllers\DataGrid\ColumnsParsingMethods,
		\MvcCore\Ext\Controllers\DataGrid\InitMethods,
		\MvcCore\Ext\Controllers\DataGrid\PreDispatchMethods,
		\MvcCore\Ext\Controllers\DataGrid\RenderMethods;

	/**
	 * MvcCore Extension - Controller - DataGrid - version:
	 * Comparison by PHP function version_compare();
	 * @see http://php.net/manual/en/function.version-compare.php
	 */
	const VERSION = '5.2.13';
}