<?php

namespace MvcCore\Ext\Controllers\DataGrids;

class UrlConfig {

	/**
	 * 
	 * @var string
	 */
	protected $urlPrefixPage = '';
	
	/**
	 * 
	 * @var string
	 */
	protected $urlPrefixCount = '';
	
	/**
	 * 
	 * @var string
	 */
	protected $urlPrefixOrder = 'order';
	
	/**
	 * 
	 * @var string
	 */
	protected $urlPrefixFilter = 'filter';
	
	/**
	 * 
	 * @var string
	 */
	protected $urlSuffixOrderAsc = 'a';
	
	/**
	 * 
	 * @var string
	 */
	protected $urlSuffixOrderDesc = 'd';
	
	/**
	 * 
	 * @var string
	 */
	protected $urlDelimiterSections = '/';
	
	/**
	 * 
	 * @var string
	 */
	protected $urlDelimiterPrefix = '-';
	
	/**
	 * 
	 * @var string
	 */
	protected $urlDelimiterSubjects = '-';
	
	/**
	 * 
	 * @var string
	 */
	protected $urlDelimiterValues = '|';
	
	/**
	 * 
	 * @var string|NULL
	 */
	protected $routePattern = NULL;

	/**
	 * 
	 * @param  string $urlPrefixPage
	 * @return \MvcCore\Ext\Controllers\DataGrids\UrlConfig
	 */
	public function SetUrlPrefixPage ($urlPrefixPage) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\UrlConfig */
		$this->urlPrefixPage = $urlPrefixPage;
		return $this;
	}
	/**
	 * 
	 * @return string
	 */
	public function GetUrlPrefixPage () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\UrlConfig */
		return $this->urlPrefixPage;
	}

	/**
	 * 
	 * @param  string $urlPrefixCount
	 * @return \MvcCore\Ext\Controllers\DataGrids\UrlConfig
	 */
	public function SetUrlPrefixCount ($urlPrefixCount) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\UrlConfig */
		$this->urlPrefixCount = $urlPrefixCount;
		return $this;
	}
	/**
	 * 
	 * @return string
	 */
	public function GetUrlPrefixCount () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\UrlConfig */
		return $this->urlPrefixCount;
	}

	/**
	 * 
	 * @param  string $urlPrefixOrder
	 * @return \MvcCore\Ext\Controllers\DataGrids\UrlConfig
	 */
	public function SetUrlPrefixOrder ($urlPrefixOrder) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\UrlConfig */
		$this->urlPrefixOrder = $urlPrefixOrder;
		return $this;
	}
	/**
	 * 
	 * @return string
	 */
	public function GetUrlPrefixOrder () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\UrlConfig */
		return $this->urlPrefixOrder;
	}

	/**
	 * 
	 * @param  string $urlPrefixFilter
	 * @return \MvcCore\Ext\Controllers\DataGrids\UrlConfig
	 */
	public function SetUrlPrefixFilter ($urlPrefixFilter) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\UrlConfig */
		$this->urlPrefixFilter = $urlPrefixFilter;
		return $this;
	}
	/**
	 * 
	 * @return string
	 */
	public function GetUrlPrefixFilter () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\UrlConfig */
		return $this->urlPrefixFilter;
	}

	/**
	 * 
	 * @param  string $urlSuffixOrderAsc
	 * @return \MvcCore\Ext\Controllers\DataGrids\UrlConfig
	 */
	public function SetUrlSuffixOrderAsc ($urlSuffixOrderAsc) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\UrlConfig */
		$this->urlSuffixOrderAsc = $urlSuffixOrderAsc;
		return $this;
	}
	/**
	 * 
	 * @return string
	 */
	public function GetUrlSuffixOrderAsc () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\UrlConfig */
		return $this->urlSuffixOrderAsc;
	}

	/**
	 * 
	 * @param  string $urlSuffixOrderDesc
	 * @return \MvcCore\Ext\Controllers\DataGrids\UrlConfig
	 */
	public function SetUrlSuffixOrderDesc ($urlSuffixOrderDesc) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\UrlConfig */
		$this->urlSuffixOrderDesc = $urlSuffixOrderDesc;
		return $this;
	}
	/**
	 * 
	 * @return string
	 */
	public function GetUrlSuffixOrderDesc () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\UrlConfig */
		return $this->urlSuffixOrderDesc;
	}

	/**
	 * 
	 * @param  string $urlDelimiterSections
	 * @return \MvcCore\Ext\Controllers\DataGrids\UrlConfig
	 */
	public function SetUrlDelimiterSections ($urlDelimiterSections) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\UrlConfig */
		$this->urlDelimiterSections = $urlDelimiterSections;
		return $this;
	}
	/**
	 * 
	 * @return string
	 */
	public function GetUrlDelimiterSections () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\UrlConfig */
		return $this->urlDelimiterSections;
	}

	/**
	 * 
	 * @param  string $urlDelimiterPrefix
	 * @return \MvcCore\Ext\Controllers\DataGrids\UrlConfig
	 */
	public function SetUrlDelimiterPrefix ($urlDelimiterPrefix) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\UrlConfig */
		$this->urlDelimiterPrefix = $urlDelimiterPrefix;
		return $this;
	}
	/**
	 * 
	 * @return string
	 */
	public function GetUrlDelimiterPrefix () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\UrlConfig */
		return $this->urlDelimiterPrefix;
	}

	/**
	 * 
	 * @param  string $urlDelimiterSubjects
	 * @return \MvcCore\Ext\Controllers\DataGrids\UrlConfig
	 */
	public function SetUrlDelimiterSubjects ($urlDelimiterSubjects) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\UrlConfig */
		$this->urlDelimiterSubjects = $urlDelimiterSubjects;
		return $this;
	}
	/**
	 * 
	 * @return string
	 */
	public function GetUrlDelimiterSubjects () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\UrlConfig */
		return $this->urlDelimiterSubjects;
	}

	/**
	 * 
	 * @param  string $urlDelimiterValues
	 * @return \MvcCore\Ext\Controllers\DataGrids\UrlConfig
	 */
	public function SetUrlDelimiterValues ($urlDelimiterValues) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\UrlConfig */
		$this->urlDelimiterValues = $urlDelimiterValues;
		return $this;
	}
	/**
	 * 
	 * @return string
	 */
	public function GetUrlDelimiterValues () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\UrlConfig */
		return $this->urlDelimiterValues;
	}
	
	/**
	 * 
	 * @param  string|NULL $routePattern
	 * @return \MvcCore\Ext\Controllers\DataGrids\UrlConfig
	 */
	public function SetRoutePattern ($routePattern) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\UrlConfig */
		$this->routePattern = $routePattern;
		return $this;
	}
	/**
	 * 
	 * @return string
	 */
	public function GetRoutePattern () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\UrlConfig */
		if ($this->routePattern === NULL) {
			$secDelim = $this->urlDelimiterSections;
			$prefDelim = $this->urlDelimiterPrefix;
			$page	= mb_strlen($this->urlPrefixPage) > 0	? $secDelim . $this->urlPrefixPage	. $prefDelim : $secDelim;
			$count	= mb_strlen($this->urlPrefixCount) > 0	? $secDelim . $this->urlPrefixCount	. $prefDelim : $secDelim;
			$order	= mb_strlen($this->urlPrefixOrder) > 0	? $secDelim . $this->urlPrefixOrder	. $prefDelim : $secDelim;
			$filter	= mb_strlen($this->urlPrefixFilter) > 0	? $secDelim . $this->urlPrefixFilter. $prefDelim : $secDelim;
			// `/<page>[/<count>][/order-<order>][/filter-<filter>]`
			$this->routePattern = "[{$page}<page>][{$count}<count>][{$order}<order>][{$filter}<filter>]";
		}
		return $this->routePattern;
	}
}