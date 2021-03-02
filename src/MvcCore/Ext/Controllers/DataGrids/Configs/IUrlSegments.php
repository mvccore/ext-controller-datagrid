<?php

namespace MvcCore\Ext\Controllers\DataGrids\Configs;

interface IUrlSegments {

	/**
	 * 
	 * @param  string $urlPrefixPage
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlPrefixPage ($urlPrefixPage);

	/**
	 * 
	 * @return string
	 */
	public function GetUrlPrefixPage ();

	/**
	 * 
	 * @param  string $urlPrefixCount
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlPrefixCount ($urlPrefixCount);

	/**
	 * 
	 * @return string
	 */
	public function GetUrlPrefixCount ();

	/**
	 * 
	 * @param  string $urlPrefixSort
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlPrefixSort ($urlPrefixSort);

	/**
	 * 
	 * @return string
	 */
	public function GetUrlPrefixSort ();

	/**
	 * 
	 * @param  string $urlPrefixFilter
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlPrefixFilter ($urlPrefixFilter);

	/**
	 * 
	 * @return string
	 */
	public function GetUrlPrefixFilter ();

	/**
	 * 
	 * @param  string $urlSuffixSortAsc
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlSuffixSortAsc ($urlSuffixSortAsc);

	/**
	 * 
	 * @return string
	 */
	public function GetUrlSuffixSortAsc ();

	/**
	 * 
	 * @param  string $urlSuffixSortDesc
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlSuffixSortDesc ($urlSuffixSortDesc);

	/**
	 * 
	 * @return string
	 */
	public function GetUrlSuffixSortDesc ();

	/**
	 * 
	 * @param  string $urlDelimiterSections
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlDelimiterSections ($urlDelimiterSections);

	/**
	 * 
	 * @return string
	 */
	public function GetUrlDelimiterSections ();

	/**
	 * 
	 * @param  string $urlDelimiterPrefix
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlDelimiterPrefix ($urlDelimiterPrefix);

	/**
	 * 
	 * @return string
	 */
	public function GetUrlDelimiterPrefix ();

	/**
	 * 
	 * @param  string $urlDelimiterSubjects
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlDelimiterSubjects ($urlDelimiterSubjects);

	/**
	 * 
	 * @return string
	 */
	public function GetUrlDelimiterSubjects ();
	
	/**
	 * 
	 * @param  string $urlDelimiterSubjectValue
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlDelimiterSubjectValue ($urlDelimiterSubjectValue);

	/**
	 * 
	 * @return string
	 */
	public function GetUrlDelimiterSubjectValue ();

	/**
	 * 
	 * @param  string $urlDelimiterValues
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlDelimiterValues ($urlDelimiterValues);

	/**
	 * 
	 * @return string
	 */
	public function GetUrlDelimiterValues ();
	
	/**
	 * 
	 * @param  array $urlFilterOperators
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlFilterOperators ($urlFilterOperators);

	/**
	 * 
	 * @return array
	 */
	public function GetUrlFilterOperators ();

	/**
	 * 
	 * @param  string|NULL $routePattern
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetRoutePattern ($routePattern);

	/**
	 * 
	 * @param  int    $sortingMode 
	 * @param  int    $filteringMode 
	 * @return string
	 */
	public function GetRoutePattern ($sortingMode = \MvcCore\Ext\Controllers\IDataGrid::SORT_DISABLED, $filteringMode = \MvcCore\Ext\Controllers\IDataGrid::FILTER_DISABLED);
}