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
	 * @param  string $urlPrefixOrder
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlPrefixOrder ($urlPrefixOrder);

	/**
	 * 
	 * @return string
	 */
	public function GetUrlPrefixOrder ();

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
	 * @param  string $urlSuffixOrderAsc
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlSuffixOrderAsc ($urlSuffixOrderAsc);

	/**
	 * 
	 * @return string
	 */
	public function GetUrlSuffixOrderAsc ();

	/**
	 * 
	 * @param  string $urlSuffixOrderDesc
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlSuffixOrderDesc ($urlSuffixOrderDesc);

	/**
	 * 
	 * @return string
	 */
	public function GetUrlSuffixOrderDesc ();

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
	 * @param  string|NULL $routePattern
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetRoutePattern ($routePattern);

	/**
	 * 
	 * @return string
	 */
	public function GetRoutePattern ();
}