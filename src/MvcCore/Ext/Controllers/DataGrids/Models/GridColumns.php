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

namespace MvcCore\Ext\Controllers\DataGrids\Models;

/**
 * This mixin is always used in grid model class
 * to be able to complete columns automatically from
 * model class context or in any custom way from any 
 * custom row model class.
 * @mixin \MvcCore\Model|\MvcCore\Ext\Models\Db\Model
 */
trait GridColumns {

	/**
	 * Return array of datagrid columns config.
	 * This method is called by datagrid component to parse decorated 
	 * model properties to complete datagrid columns configuration automatically.
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column[]
	 */
	public function GetConfigColumns () {
		return $this->grid->ParseConfigColumns();
	}

}
