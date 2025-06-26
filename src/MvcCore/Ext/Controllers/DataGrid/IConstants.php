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

interface IConstants {

	/**
	 * Default row model class.
	 * @var string
	 */
	const ROW_MODEL_CLASS_DEFAULT		= '\MvcCore\Ext\Controllers\DataGrids\Models\GridRow';


	/**
	 * Grid action name for table filter submit.
	 * @var string
	 */
	const GRID_ACTION_FILTER_TABLE		= 'filter-table';
	
	/**
	 * Grid action name for custom filter form submit.
	 * @var string
	 */
	const GRID_ACTION_FILTER_FORM		= 'filter-form';
	
	/**
	 * Default grid action name.
	 * @var string
	 */
	const GRID_ACTION_DEFAULT			= 'default';


	/**
	 * URL and form input null value.
	 * @var string
	 */
	const NULL_STRING_VALUE				= 'null';

	
	/**
	 * Current route param name. It could be used in rewrite 
	 * section or in query string like:
	 * `/any/path[/<grid>]` or `/any/path?grid=...`
	 * @var string
	 */
	const URL_PARAM_GRID				= 'grid';
	
	/**
	 * Query string param name defining datagrid internal action.
	 * @var string
	 */
	const URL_PARAM_ACTION				= 'grid-action';
	
	/**
	 * Query string param name defining datagrid internal debuging.
	 * @var string
	 */
	const URL_PARAM_DEBUG				= 'grid-debug';
	
	/**
	 * Grid routing param name to define page.
	 * @var string
	 */
	const URL_PARAM_PAGE				= 'page';
	
	/**
	 * Grid routing param name to define items per page.
	 * @var string
	 */
	const URL_PARAM_COUNT				= 'count';
	
	/**
	 * Grid routing param name to define sorting.
	 * @var string
	 */
	const URL_PARAM_SORT				= 'sort';
	
	/**
	 * Grid routing param name to define filtering.
	 * @var string
	 */
	const URL_PARAM_FILTER				= 'filter';
	
	/**
	 * Grid routing param name to accept/not accept current sorting or filtering.
	 * @var string
	 */
	const URL_PARAM_CLEAR				= 'clear';
	

	/**
	 * Default `10` items per page.
	 * @var int
	 */
	const ITEMS_PER_PAGE_DEFAULT		= 10;

	/**
	 * Default count control scales - `[10,100,1000,0]`. 
	 * Last zero value means unlimited items option.
	 * @var string
	 */
	const COUNTS_SCALE_DEFAULT			= '10,100,1000,0'; // PHP 5.4 compatible


	/**
	 * Standard table datagrid type.
	 * @var int
	 */
	const TYPE_TABLE					= 1;
	
	/**
	 * Griw with variable columns count datagrid type.
	 * @var int
	 */
	const TYPE_GRID						= 2;


	/**
	 * Datagrid sorting disabled.
	 * @var int
	 */
	const SORT_DISABLED					= 0;
	
	/**
	 * Datagrid sorting enabled for single column.
	 * @var int
	 */
	const SORT_SINGLE_COLUMN			= 1;
	
	/**
	 * Datagrid sorting enabled for multiple columns.
	 * @var int
	 */
	const SORT_MULTIPLE_COLUMNS			= 2;

	
	/**
	 * Datagrid filtering disabled.
	 * @var int
	 */
	const FILTER_DISABLED				= 0;
	
	/**
	 * Datagrid filtering enabled for single column.
	 * @var int
	 */
	const FILTER_SINGLE_COLUMN			= 1;
	
	/**
	 * Datagrid filtering enabled for multiple columns.
	 * @var int
	 */
	const FILTER_MULTIPLE_COLUMNS		= 2;
	
	/**
	 * Datagrid filtering enabled with operators `=`, `!=`.
	 * @var int
	 */
	const FILTER_ALLOW_EQUALS			= 4;
	
	/**
	 * Datagrid filtering enabled with operators `<`, `>`, `<=`, `>=`.
	 * @var int
	 */
	const FILTER_ALLOW_RANGES			= 8;
	
	/**
	 * Datagrid filtering enabled `LIKE` operator with `%` or `_` from right side.
	 * @var int
	 */
	const FILTER_ALLOW_LIKE_RIGHT_SIDE	= 16;
	
	/**
	 * Datagrid filtering enabled `LIKE` operator with `%` or `_` from left side.
	 * @var int
	 */
	const FILTER_ALLOW_LIKE_LEFT_SIDE	= 32;
	
	/**
	 * Datagrid filtering enabled `LIKE` operator with `%` or `_` from any side.
	 * @var int
	 */
	const FILTER_ALLOW_LIKE_ANYWHERE	= 64;
	
	/**
	 * Datagrid filtering enabled for `null` values.
	 * @var int
	 */
	const FILTER_ALLOW_NULL				= 128;
	
	/**
	 * Datagrid filtering enabled for `null` values.
	 * @var int
	 */
	const FILTER_ALLOW_NOT_NULL			= 256;
	
	/**
	 * Datagrid filtering enabled for all features including null values.
	 * @var int
	 */
	const FILTER_ALLOW_ALL_WITH_NULL	= 252; // 4|8|16|32|64|128
	
	/**
	 * Datagrid filtering enabled for all features except null values.
	 * @var int
	 */
	const FILTER_ALLOW_ALL_NOT_NULL		= 380; // 4|8|16|32|64|256

	/**
	 * Default filtering mode flags - single column only with equals and ranges operators.
	 * @var int
	 */
	const FILTER_ALLOW_DEFAULT			= 13; // 1|4|8


	/**
	 * Never display this control.
	 * @var int
	 */
	const CONTROL_DISPLAY_NEVER			= 0;
	
	/**
	 * Display control if necessary.
	 * @var int
	 */
	const CONTROL_DISPLAY_IF_NECESSARY	= 1;

	/**
	 * Display control every time.
	 * @var int
	 */
	const CONTROL_DISPLAY_ALWAYS		= 2;
}
