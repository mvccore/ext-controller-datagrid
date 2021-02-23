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
	 * @param  string $urlPrefixPage
	 * @return \MvcCore\Ext\Controllers\DataGrids\UrlConfig
	 */
	public function SetUrlPrefixPage ($urlPrefixPage) {
		$this->urlPrefixPage = $urlPrefixPage;
		return $this;
	}
	/**
	 * 
	 * @return string
	 */
	public function GetUrlPrefixPage () {
		return $this->urlPrefixPage;
	}

	/**
	 * 
	 * @param  string $urlPrefixCount
	 * @return \MvcCore\Ext\Controllers\DataGrids\UrlConfig
	 */
	public function SetUrlPrefixCount ($urlPrefixCount) {
		$this->urlPrefixCount = $urlPrefixCount;
		return $this;
	}
	/**
	 * 
	 * @return string
	 */
	public function GetUrlPrefixCount () {
		return $this->urlPrefixCount;
	}

	/**
	 * 
	 * @param  string $urlPrefixOrder
	 * @return \MvcCore\Ext\Controllers\DataGrids\UrlConfig
	 */
	public function SetUrlPrefixOrder ($urlPrefixOrder) {
		$this->urlPrefixOrder = $urlPrefixOrder;
		return $this;
	}
	/**
	 * 
	 * @return string
	 */
	public function GetUrlPrefixOrder () {
		return $this->urlPrefixOrder;
	}

	/**
	 * 
	 * @param  string $urlPrefixFilter
	 * @return \MvcCore\Ext\Controllers\DataGrids\UrlConfig
	 */
	public function SetUrlPrefixFilter ($urlPrefixFilter) {
		$this->urlPrefixFilter = $urlPrefixFilter;
		return $this;
	}
	/**
	 * 
	 * @return string
	 */
	public function GetUrlPrefixFilter () {
		return $this->urlPrefixFilter;
	}

	/**
	 * 
	 * @param  string $urlSuffixOrderAsc
	 * @return \MvcCore\Ext\Controllers\DataGrids\UrlConfig
	 */
	public function SetUrlSuffixOrderAsc ($urlSuffixOrderAsc) {
		$this->urlSuffixOrderAsc = $urlSuffixOrderAsc;
		return $this;
	}
	/**
	 * 
	 * @return string
	 */
	public function GetUrlSuffixOrderAsc () {
		return $this->urlSuffixOrderAsc;
	}

	/**
	 * 
	 * @param  string $urlSuffixOrderDesc
	 * @return \MvcCore\Ext\Controllers\DataGrids\UrlConfig
	 */
	public function SetUrlSuffixOrderDesc ($urlSuffixOrderDesc) {
		$this->urlSuffixOrderDesc = $urlSuffixOrderDesc;
		return $this;
	}
	/**
	 * 
	 * @return string
	 */
	public function GetUrlSuffixOrderDesc () {
		return $this->urlSuffixOrderDesc;
	}

	/**
	 * 
	 * @param  string $urlDelimiterSections
	 * @return \MvcCore\Ext\Controllers\DataGrids\UrlConfig
	 */
	public function SetUrlDelimiterSections ($urlDelimiterSections) {
		$this->urlDelimiterSections = $urlDelimiterSections;
		return $this;
	}
	/**
	 * 
	 * @return string
	 */
	public function GetUrlDelimiterSections () {
		return $this->urlDelimiterSections;
	}

	/**
	 * 
	 * @param  string $urlDelimiterPrefix
	 * @return \MvcCore\Ext\Controllers\DataGrids\UrlConfig
	 */
	public function SetUrlDelimiterPrefix ($urlDelimiterPrefix) {
		$this->urlDelimiterPrefix = $urlDelimiterPrefix;
		return $this;
	}
	/**
	 * 
	 * @return string
	 */
	public function GetUrlDelimiterPrefix () {
		return $this->urlDelimiterPrefix;
	}

	/**
	 * 
	 * @param  string $urlDelimiterSubjects
	 * @return \MvcCore\Ext\Controllers\DataGrids\UrlConfig
	 */
	public function SetUrlDelimiterSubjects ($urlDelimiterSubjects) {
		$this->urlDelimiterSubjects = $urlDelimiterSubjects;
		return $this;
	}
	/**
	 * 
	 * @return string
	 */
	public function GetUrlDelimiterSubjects () {
		return $this->urlDelimiterSubjects;
	}

	/**
	 * 
	 * @param  string $urlDelimiterValues
	 * @return \MvcCore\Ext\Controllers\DataGrids\UrlConfig
	 */
	public function SetUrlDelimiterValues ($urlDelimiterValues) {
		$this->urlDelimiterValues = $urlDelimiterValues;
		return $this;
	}
	/**
	 * 
	 * @return string
	 */
	public function GetUrlDelimiterValues () {
		return $this->urlDelimiterValues;
	}

}