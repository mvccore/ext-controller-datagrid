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

interface IUrlSegments {

	/**
	 * Set URL prefix to define which URL section is used for page number.
	 * Default value is empty string.
	 * If empty, there is used first section by default.
	 * @param  string $urlPrefixPage
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlPrefixPage ($urlPrefixPage);

	/**
	 * Get URL prefix to define which URL section is used for page number.
	 * Default value is empty string.
	 * If empty, there is used first section by default.
	 * @return string
	 */
	public function GetUrlPrefixPage ();

	/**
	 * Set URL prefix to define which URL section is used for items per page count.
	 * Default value is empty string.
	 * If empty, there is used second section by default.
	 * @param  string $urlPrefixCount
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlPrefixCount ($urlPrefixCount);

	/**
	 * Get URL prefix to define which URL section is used for items per page count.
	 * Default value is empty string.
	 * If empty, there is used second section by default.
	 * @return string
	 */
	public function GetUrlPrefixCount ();

	/**
	 * Set URL prefix to define which URL section is used for sorting.
	 * Default value is `sort`.
	 * @param  string $urlPrefixSort
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlPrefixSort ($urlPrefixSort);

	/**
	 * Get URL prefix to define which URL section is used for sorting.
	 * Default value is `sort`.
	 * @return string
	 */
	public function GetUrlPrefixSort ();

	/**
	 * Set URL prefix to define which URL section is used for filtering.
	 * Default value is `filter`.
	 * @param  string $urlPrefixFilter
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlPrefixFilter ($urlPrefixFilter);

	/**
	 * Get URL prefix to define which URL section is used for filtering.
	 * Default value is `filter`.
	 * @return string
	 */
	public function GetUrlPrefixFilter ();

	/**
	 * Set URL suffix to define ascendent sort direction.
	 * Default value is `a`.
	 * @param  string $urlSuffixSortAsc
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlSuffixSortAsc ($urlSuffixSortAsc);

	/**
	 * Get URL suffix to define ascendent sort direction.
	 * Default value is `a`.
	 * @return string
	 */
	public function GetUrlSuffixSortAsc ();

	/**
	 * Set URL suffix to define descendent sort direction.
	 * Default value is `d`.
	 * @param  string $urlSuffixSortDesc
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlSuffixSortDesc ($urlSuffixSortDesc);

	/**
	 * Get URL suffix to define descendent sort direction.
	 * Default value is `d`.
	 * @return string
	 */
	public function GetUrlSuffixSortDesc ();

	/**
	 * Set URL delimiter between sections.
	 * Default value is `/`.
	 * @param  string $urlDelimiterSections
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlDelimiterSections ($urlDelimiterSections);

	/**
	 * Get URL delimiter between sections.
	 * Default value is `/`.
	 * @return string
	 */
	public function GetUrlDelimiterSections ();

	/**
	 * Set URL delimiter for each section where is defined prefix, 
	 * between prefix and rest of the section.
	 * Default value is `-`.
	 * @param  string $urlDelimiterPrefix
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlDelimiterPrefix ($urlDelimiterPrefix);

	/**
	 * Get URL delimiter for each section where is defined prefix, 
	 * between prefix and rest of the section.
	 * Default value is `-`.
	 * @return string
	 */
	public function GetUrlDelimiterPrefix ();

	/**
	 * Set URL delimiter for sorting and filtering, 
	 * between each pair of grid column with value(s).
	 * Default value is `~`.
	 * @param  string $urlDelimiterSubjects
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlDelimiterSubjects ($urlDelimiterSubjects);

	/**
	 * Get URL delimiter for sorting and filtering, 
	 * between each pair of grid column with value(s).
	 * Default value is `~`.
	 * @return string
	 */
	public function GetUrlDelimiterSubjects ();
	
	/**
	 * Set URL delimiter for sorting and filtering.
	 * In sorting   - between grid column name and sort direction suffix.
	 * In filtering - between grid column name, operator and (first) value.
	 * Default value is `-`.
	 * @param  string $urlDelimiterSubjectValue
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlDelimiterSubjectValue ($urlDelimiterSubjectValue);

	/**
	 * Get URL delimiter for sorting and filtering.
	 * In sorting   - between grid column name and sort direction suffix.
	 * In filtering - between grid column name, operator and (first) value.
	 * Default value is `-`.
	 * @return string
	 */
	public function GetUrlDelimiterSubjectValue ();

	/**
	 * Set URL delimiter for filtering, between multiple filtering values.
	 * Default value is `,`.
	 * @param  string $urlDelimiterValues
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlDelimiterValues ($urlDelimiterValues);

	/**
	 * Get URL delimiter for filtering, between multiple filtering values.
	 * Default value is `,`.
	 * @return string
	 */
	public function GetUrlDelimiterValues ();
	
	/**
	 * Set allowed URL filter operators.
	 * Keys are database operators, values are url operator texts.
	 * ```
	 *    '='        => 'is',
	 *    '!='       => 'not',
	 *    'LIKE'     => 'as',
	 *    'NOT LIKE' => 'not-as',
	 *    '<'        => 'lt',
	 *    '>'        => 'gt',
	 *    '<='       => 'lte',
	 *    '>='       => 'gte',
	 * ```
	 * @param  array $urlFilterOperators
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlFilterOperators ($urlFilterOperators);

	/**
	 * Get allowed URL filter operators.
	 * Keys are database operators, values are url operator texts.
	 * There are allowed operators by default:
	 * ```
	 *    '='        => 'is',
	 *    '!='       => 'not',
	 *    'LIKE'     => 'as',
	 *    'NOT LIKE' => 'not-as',
	 *    '<'        => 'lt',
	 *    '>'        => 'gt',
	 *    '<='       => 'lte',
	 *    '>='       => 'gte',
	 * ```
	 * @return array
	 */
	public function GetUrlFilterOperators ();

	/**
	 * Set URL route pattern. This is automatically completed 
	 * from other configuration properties of this object.
	 * @param  string|NULL $routePattern
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetRoutePattern ($routePattern);

	/**
	 * Get URL route pattern. This is automatically completed 
	 * from other configuration properties of this object.
	 * @param  int    $sortingMode 
	 * @param  int    $filteringMode 
	 * @return string
	 */
	public function GetRoutePattern ($sortingMode = \MvcCore\Ext\Controllers\IDataGrid::SORT_DISABLED, $filteringMode = \MvcCore\Ext\Controllers\IDataGrid::FILTER_DISABLED);
}