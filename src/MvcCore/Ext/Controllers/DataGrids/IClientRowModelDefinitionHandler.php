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

interface IClientRowModelDefinitionHandler {
	
	/**
	 * Custom handler to define client TS row model generating.
	 * @param  string|\MvcCore\Ext\Tools\TsGenerator $generatorClass 
	 * @param  string                                $rowClass 
	 * @param  int                                   $rowClassPropsFlags 
	 * @return void
	 */
	public function __invoke ($generatorClass, $rowClass, $rowClassPropsFlags);

}