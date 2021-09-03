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

namespace MvcCore\Ext\Controllers\DataGrids\Views;

interface IReverseHelper {

	/**
	 * Set currently used datagrid instance.
	 * @param  \MvcCore\Ext\Controllers\IDataGrid $grid 
	 * @return \MvcCore\Ext\Controllers\DataGrids\Views\IReverseHelper
	 */
	public function SetGrid (\MvcCore\Ext\Controllers\IDataGrid $grid);
	
	/**
	 * Unformat value previously formatted by view helper into application value.
	 * Incoming value could be anything from user filter input,
	 * multiple values are separated by `;` (semicolon) delimitter by default.
	 * @param  string $rawUserFormatedValues Raw user filter input.
	 * @return string Returns unformated application value(s). Multiple values are separated by `;`.
	 */
	public function Unformat ($rawUserFormatedValues);
}