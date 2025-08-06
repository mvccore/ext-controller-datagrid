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

/**
 * @phpstan-type Filtering array<string, array<string, array<mixed>>>
 */
interface IInitMethods {

	/**
	 * Parse, check and filter all sorting columns and directions.
	 * Remove what is not allowed and normalize values.
	 * @param  string $rawSorting 
	 * @param  bool   $invalidValueFound 
	 * @return array<string,bool|string|null>
	 */
	public function ParseSorting ($rawSorting, &$invalidValueFound = FALSE);

	/**
	 * Parse, check and filter all filtering columns, operators and values.
	 * Remove what is not allowed, unformat values back to system values.
	 * @param  string $rawFiltering 
	 * @param  bool   $invalidValueFound
	 * @return Filtering
	 */
	public function ParseFiltering ($rawFiltering, & $invalidValueFound = FALSE);

}