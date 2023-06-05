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

namespace MvcCore\Ext\Controllers\DataGrids\Configs;

class Type {
	
	/**
	 * Data used on server as PHP type `string`.
	 */
	const SERVER_STRING			= 'string';
	
	/**
	 * Data used on server as PHP type `int`.
	 */
	const SERVER_INT			= 'int';
	
	/**
	 * Data used on server as PHP type `float`.
	 */
	const SERVER_FLOAT			= 'float';
	
	/**
	 * Data used on server as PHP type `bool`.
	 */
	const SERVER_BOOL			= 'bool';
	
	/**
	 * Data used on server as PHP type `\DateTime`.
	 */
	const SERVER_DATETIME		= '\DateTime';
	

	/**
	 * Data used on server as PHP type `?string` (nullable string).
	 */
	const SERVER_STRING_NULL	= '?string';
	
	/**
	 * Data used on server as PHP type `?int` (nullable int).
	 */
	const SERVER_INT_NULL		= '?int';
	
	/**
	 * Data used on server as PHP type `?float` (nullable float).
	 */
	const SERVER_FLOAT_NULL		= '?float';
	
	/**
	 * Data used on server as PHP type `?bool` (nullable bool).
	 */
	const SERVER_BOOL_NULL		= '?bool';

	/**
	 * Data used on server as PHP type `?\DateTime` (nullable \DateTime).
	 */
	const SERVER_DATETIME_NULL	= '?\DateTime';
	
	
	/**
	 * Data displayed on client side as string, it's not necessary 
	 * to define if there is defined (?)string server type.
	 */
	const CLIENT_STRING			= 'string';

	/**
	 *
	 * Data displayed on client side as integer without fraction, 
	 * it's not necessary to define if there is defined (?)integer server type.
	 */
	const CLIENT_INT			= 'int';

	/**
	 * Data displayed on client side as float with fraction digits, 
	 * it's not necessary to define if there is defined (?)float server type.
	 */
	const CLIENT_FLOAT			= 'float';

	/**
	 * Data displayed on client side as float with fraction digits 
	 * and with currency symbol, this is necessary to define with (?)float server type.
	 */
	const CLIENT_MONEY			= 'money';

	/**
	 * Data displayed on client side as 'yes' or 'no' translated string, 
	 * it's not necessary to define if there is defined (?)bool server type.
	 */
	const CLIENT_BOOL			= 'bool';

	/**
	 * Data displayed on client side as date, containing year, month and day, 
	 * this is necessary to define with (?)\DateTime server type.
	 */
	const CLIENT_DATE			= 'date';
	
	/**
	 * Data displayed on client side as date and time, containing year, month, day, 
	 * hour, minute and second (or milisecond if defined in format arguments), 
	 * this is necessary to define with (?)\DateTime server type.
	 */
	const CLIENT_DATETIME		= 'dateTime';

	/**
	 * Data displayed on client side as time, containing hour, minute and second 
	 * (or milisecond if defined in format arguments), 
	 * this is necessary to define with (?)\DateTime server type.
	 */
	const CLIENT_TIME			= 'time';
	
}