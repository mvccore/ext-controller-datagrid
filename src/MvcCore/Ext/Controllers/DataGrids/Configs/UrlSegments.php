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

class		UrlSegments 
implements	\MvcCore\Ext\Controllers\DataGrids\Configs\IUrlSegments {

	/**
	 * URL prefix to define which URL section is used for page number.
	 * Default value is empty string.
	 * If empty, there is used first section by default.
	 * @var string
	 */
	protected $urlPrefixPage = '';
	
	/**
	 * URL prefix to define which URL section is used for items per page count.
	 * Default value is empty string.
	 * If empty, there is used second section by default.
	 * @var string
	 */
	protected $urlPrefixCount = '';
	
	/**
	 * URL prefix to define which URL section is used for sorting.
	 * Default value is `sort`.
	 * @var string
	 */
	protected $urlPrefixSort = 'sort';
	
	/**
	 * URL prefix to define which URL section is used for filtering.
	 * Default value is `filter`.
	 * @var string
	 */
	protected $urlPrefixFilter = 'filter';
	
	/**
	 * URL suffix to define ascendent sort direction.
	 * Default value is `a`.
	 * @var string
	 */
	protected $urlSuffixSortAsc = 'a';
	
	/**
	 * URL suffix to define descendent sort direction.
	 * Default value is `d`.
	 * @var string
	 */
	protected $urlSuffixSortDesc = 'd';
	
	/**
	 * URL delimiter between sections.
	 * Default value is `/`.
	 * @var string
	 */
	protected $urlDelimiterSections = '/';
	
	/**
	 * URL delimiter for each section where is defined prefix, 
	 * between prefix and rest of the section.
	 * Default value is `-`.
	 * @var string
	 */
	protected $urlDelimiterPrefix = '-';
	
	/**
	 * URL delimiter for sorting and filtering, 
	 * between each pair of grid column with value(s).
	 * Default value is `~`.
	 * @var string
	 */
	protected $urlDelimiterSubjects = '~';
	
	/**
	 * URL delimiter for sorting and filtering.
	 * In sorting   - between grid column name and sort direction suffix.
	 * In filtering - between grid column name, operator and (first) value.
	 * Default value is `-`.
	 * @var string
	 */
	protected $urlDelimiterSubjectValue = '-';
	
	/**
	 * URL delimiter for filtering, between multiple filtering values.
	 * Default value is `,`.
	 * @var string
	 */
	protected $urlDelimiterValues = ',';

	/**
	 * Allowed URL filter operators.
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
	 * @var array
	 */
	protected $urlFilterOperators = [
		'='			=> 'is',
		'!='		=> 'not',
		'LIKE'		=> 'as',
		'NOT LIKE'	=> 'not-as',
		'<'			=> 'lt',
		'>'			=> 'gt',
		'<='		=> 'lte',
		'>='		=> 'gte',
	];
	
	/**
	 * URL route pattern. This is automatically completed 
	 * from other configuration properties of this object.
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
	 * @param  string $urlPrefixSort
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlPrefixSort ($urlPrefixSort) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments */
		$this->urlPrefixSort = $urlPrefixSort;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return string
	 */
	public function GetUrlPrefixSort () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments */
		return $this->urlPrefixSort;
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
	 * @param  string $urlSuffixSortAsc
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlSuffixSortAsc ($urlSuffixSortAsc) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments */
		$this->urlSuffixSortAsc = $urlSuffixSortAsc;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return string
	 */
	public function GetUrlSuffixSortAsc () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments */
		return $this->urlSuffixSortAsc;
	}

	/**
	 * @inheritDocs
	 * @param  string $urlSuffixSortDesc
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlSuffixSortDesc ($urlSuffixSortDesc) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments */
		$this->urlSuffixSortDesc = $urlSuffixSortDesc;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return string
	 */
	public function GetUrlSuffixSortDesc () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments */
		return $this->urlSuffixSortDesc;
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
	 * @param  array $urlFilterOperators
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlFilterOperators ($urlFilterOperators) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments */
		$this->urlFilterOperators = $urlFilterOperators;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @return array
	 */
	public function GetUrlFilterOperators () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments */
		return $this->urlFilterOperators;
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
	 * @param  int    $sortingMode 
	 * @param  int    $filteringMode
	 * @return string
	 */
	public function GetRoutePattern ($sortingMode = \MvcCore\Ext\Controllers\IDataGrid::SORT_DISABLED, $filteringMode = \MvcCore\Ext\Controllers\IDataGrid::FILTER_DISABLED) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments */
		if ($this->routePattern === NULL) {
			$secDelim = $this->urlDelimiterSections;
			$prefDelim = $this->urlDelimiterPrefix;
			$page	= mb_strlen($this->urlPrefixPage) > 0	? $secDelim . $this->urlPrefixPage	. $prefDelim : $secDelim;
			$count	= mb_strlen($this->urlPrefixCount) > 0	? $secDelim . $this->urlPrefixCount	. $prefDelim : $secDelim;
			$sort	= mb_strlen($this->urlPrefixSort) > 0	? $secDelim . $this->urlPrefixSort	. $prefDelim : $secDelim;
			$filter	= mb_strlen($this->urlPrefixFilter) > 0	? $secDelim . $this->urlPrefixFilter. $prefDelim : $secDelim;
			// `/<page>[/<count>][/sort-<sort>][/filter-<filter>]/`
			$this->routePattern = "[{$page}<page>][{$count}<count>]";
			if ($sortingMode)	$this->routePattern .= "[{$sort}<sort>]";
			if ($filteringMode)	$this->routePattern .= "[{$filter}<filter>]";
			$this->routePattern .= "/";
		}
		return $this->routePattern;
	}
}