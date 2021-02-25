<?php

namespace MvcCore\Ext\Controllers\DataGrids\Configs;

class		UrlSegments 
implements	\MvcCore\Ext\Controllers\DataGrids\Configs\IUrlSegments {

	/**
	 * URL prefix to define which URL section is used for page number.
	 * If empty, there is used first section by default.
	 * @var string
	 */
	protected $urlPrefixPage = '';
	
	/**
	 * URL prefix to define which URL section is used for items per page count.
	 * If empty, there is used second section by default.
	 * @var string
	 */
	protected $urlPrefixCount = '';
	
	/**
	 * URL prefix to define which URL section is used for ordering.
	 * @var string
	 */
	protected $urlPrefixOrder = 'order';
	
	/**
	 * URL prefix to define which URL section is used for filtering.
	 * @var string
	 */
	protected $urlPrefixFilter = 'filter';
	
	/**
	 * URL suffix to define ascendent order direction.
	 * @var string
	 */
	protected $urlSuffixOrderAsc = 'a';
	
	/**
	 * URL suffix to define descendent order direction.
	 * @var string
	 */
	protected $urlSuffixOrderDesc = 'd';
	
	/**
	 * URL delimiter between sections.
	 * @var string
	 */
	protected $urlDelimiterSections = '/';
	
	/**
	 * URL delimiter for each section where is defined prefix, between prefix and rest of the section.
	 * @var string
	 */
	protected $urlDelimiterPrefix = '-';
	
	/**
	 * URL delimiter for ordering and filtering, between each pair of grid column with value(s).
	 * @var string
	 */
	protected $urlDelimiterSubjects = '~';
	
	/**
	 * URL delimiter for ordering and filtering, between grid column name and (first) value.
	 * @var string
	 */
	protected $urlDelimiterSubjectValue = '-';
	
	/**
	 * URL delimiter for filtering, between multiple filtering values.
	 * @var string
	 */
	protected $urlDelimiterValues = ',';
	
	/**
	 * URL route pattern, automatically completed from configuration properties above.
	 * @var string|NULL
	 */
	protected $routePattern = NULL;


	/**
	 * @inheritDocs
	 * @param  string $urlPrefixPage
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlPrefixPage ($urlPrefixPage) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments */
		$this->urlPrefixPage = $urlPrefixPage;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return string
	 */
	public function GetUrlPrefixPage () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments */
		return $this->urlPrefixPage;
	}

	/**
	 * @inheritDocs
	 * @param  string $urlPrefixCount
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlPrefixCount ($urlPrefixCount) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments */
		$this->urlPrefixCount = $urlPrefixCount;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return string
	 */
	public function GetUrlPrefixCount () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments */
		return $this->urlPrefixCount;
	}

	/**
	 * @inheritDocs
	 * @param  string $urlPrefixOrder
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlPrefixOrder ($urlPrefixOrder) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments */
		$this->urlPrefixOrder = $urlPrefixOrder;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return string
	 */
	public function GetUrlPrefixOrder () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments */
		return $this->urlPrefixOrder;
	}

	/**
	 * @inheritDocs
	 * @param  string $urlPrefixFilter
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlPrefixFilter ($urlPrefixFilter) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments */
		$this->urlPrefixFilter = $urlPrefixFilter;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return string
	 */
	public function GetUrlPrefixFilter () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments */
		return $this->urlPrefixFilter;
	}

	/**
	 * @inheritDocs
	 * @param  string $urlSuffixOrderAsc
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlSuffixOrderAsc ($urlSuffixOrderAsc) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments */
		$this->urlSuffixOrderAsc = $urlSuffixOrderAsc;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return string
	 */
	public function GetUrlSuffixOrderAsc () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments */
		return $this->urlSuffixOrderAsc;
	}

	/**
	 * @inheritDocs
	 * @param  string $urlSuffixOrderDesc
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlSuffixOrderDesc ($urlSuffixOrderDesc) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments */
		$this->urlSuffixOrderDesc = $urlSuffixOrderDesc;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return string
	 */
	public function GetUrlSuffixOrderDesc () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments */
		return $this->urlSuffixOrderDesc;
	}

	/**
	 * @inheritDocs
	 * @param  string $urlDelimiterSections
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlDelimiterSections ($urlDelimiterSections) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments */
		$this->urlDelimiterSections = $urlDelimiterSections;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return string
	 */
	public function GetUrlDelimiterSections () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments */
		return $this->urlDelimiterSections;
	}

	/**
	 * @inheritDocs
	 * @param  string $urlDelimiterPrefix
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlDelimiterPrefix ($urlDelimiterPrefix) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments */
		$this->urlDelimiterPrefix = $urlDelimiterPrefix;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return string
	 */
	public function GetUrlDelimiterPrefix () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments */
		return $this->urlDelimiterPrefix;
	}

	/**
	 * @inheritDocs
	 * @param  string $urlDelimiterSubjects
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlDelimiterSubjects ($urlDelimiterSubjects) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments */
		$this->urlDelimiterSubjects = $urlDelimiterSubjects;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return string
	 */
	public function GetUrlDelimiterSubjects () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments */
		return $this->urlDelimiterSubjects;
	}
	
	/**
	 * @inheritDocs
	 * @param  string $urlDelimiterSubjectValue
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlDelimiterSubjectValue ($urlDelimiterSubjectValue) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments */
		$this->urlDelimiterSubjectValue = $urlDelimiterSubjectValue;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return string
	 */
	public function GetUrlDelimiterSubjectValue () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments */
		return $this->urlDelimiterSubjectValue;
	}

	/**
	 * @inheritDocs
	 * @param  string $urlDelimiterValues
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlDelimiterValues ($urlDelimiterValues) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments */
		$this->urlDelimiterValues = $urlDelimiterValues;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return string
	 */
	public function GetUrlDelimiterValues () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments */
		return $this->urlDelimiterValues;
	}
	
	/**
	 * @inheritDocs
	 * @param  string|NULL $routePattern
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetRoutePattern ($routePattern) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments */
		$this->routePattern = $routePattern;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @internal
	 * @return string
	 */
	public function GetRoutePattern () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments */
		if ($this->routePattern === NULL) {
			$secDelim = $this->urlDelimiterSections;
			$prefDelim = $this->urlDelimiterPrefix;
			$page	= mb_strlen($this->urlPrefixPage) > 0	? $secDelim . $this->urlPrefixPage	. $prefDelim : $secDelim;
			$count	= mb_strlen($this->urlPrefixCount) > 0	? $secDelim . $this->urlPrefixCount	. $prefDelim : $secDelim;
			$order	= mb_strlen($this->urlPrefixOrder) > 0	? $secDelim . $this->urlPrefixOrder	. $prefDelim : $secDelim;
			$filter	= mb_strlen($this->urlPrefixFilter) > 0	? $secDelim . $this->urlPrefixFilter. $prefDelim : $secDelim;
			// `/<page>[/<count>][/order-<order>][/filter-<filter>]/`
			$this->routePattern = "[{$page}<page>][{$count}<count>][{$order}<order>][{$filter}<filter>]/";
		}
		return $this->routePattern;
	}
}