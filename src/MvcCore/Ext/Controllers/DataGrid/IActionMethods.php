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

namespace MvcCore\Ext\Controllers\DataGrid;

interface IActionMethods {

	/**
	 * Internal default action for datagrid content rendering.
	 * @template
	 * @return void
	 */
	public function DefaultInit ();

	/**
	 * Internal submit action for table head filter form.
	 * @template
	 * @return void
	 */
	public function TableFilterInit ();
	
	/**
	 * Get new filtering from filter form submit values array.
	 * @param  array $formSubmitValues 
	 * @param  array $filtering 
	 * @return array
	 */
	public function GetFilteringFromFilterFormValues (array $formSubmitValues, array $filtering = []);

	/**
	 * Internal submit action for custom filtering form.
	 * @template
	 * @return void
	 */
	public function FormFilterInit ();

}