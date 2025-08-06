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
implements	\MvcCore\Ext\Controllers\DataGrids\Configs\IUrlSegments,
			\JsonSerializable {

	/**
	 * URL prefix to define which URL section is used for page number.
	 * Default value is empty string.
	 * If empty, there is used first section by default.
	 * @jsonSerialize
	 * @var string
	 */
	#[JsonSerialize]
	protected $urlPrefixPage = '';
	
	/**
	 * URL prefix to define which URL section is used for items per page count.
	 * Default value is empty string.
	 * If empty, there is used second section by default.
	 * @jsonSerialize
	 * @var string
	 */
	#[JsonSerialize]
	protected $urlPrefixCount = '';
	
	/**
	 * URL prefix to define which URL section is used for sorting.
	 * Default value is `sort`.
	 * @jsonSerialize
	 * @var string
	 */
	#[JsonSerialize]
	protected $urlPrefixSort = 'sort';
	
	/**
	 * URL prefix to define which URL section is used for filtering.
	 * Default value is `filter`.
	 * @jsonSerialize
	 * @var string
	 */
	#[JsonSerialize]
	protected $urlPrefixFilter = 'filter';
	
	/**
	 * URL suffix to define ascendent sort direction.
	 * Default value is `a`.
	 * @jsonSerialize
	 * @var string
	 */
	#[JsonSerialize]
	protected $urlSuffixSortAsc = 'a';
	
	/**
	 * URL suffix to define descendent sort direction.
	 * Default value is `d`.
	 * @jsonSerialize
	 * @var string
	 */
	#[JsonSerialize]
	protected $urlSuffixSortDesc = 'd';
	
	/**
	 * URL delimiter between sections.
	 * Default value is `/`.
	 * @jsonSerialize
	 * @var string
	 */
	#[JsonSerialize]
	protected $urlDelimiterSections = '/';
	
	/**
	 * URL delimiter for each section where is defined prefix, 
	 * between prefix and rest of the section.
	 * Default value is `-`.
	 * @jsonSerialize
	 * @var string
	 */
	#[JsonSerialize]
	protected $urlDelimiterPrefix = '-';
	
	/**
	 * URL delimiter for sorting and filtering, 
	 * between each pair of grid column with value(s).
	 * Default value is `~`.
	 * @jsonSerialize
	 * @var string
	 */
	#[JsonSerialize]
	protected $urlDelimiterSubjects = '~';
	
	/**
	 * URL delimiter for sorting and filtering.
	 * In sorting   - between grid column name and sort direction suffix.
	 * In filtering - between grid column name, operator and (first) value.
	 * Default value is `-`.
	 * @jsonSerialize
	 * @var string
	 */
	#[JsonSerialize]
	protected $urlDelimiterSubjectValue = '-';
	
	/**
	 * URL delimiter for filtering, between multiple filtering values.
	 * Default value is `;`.
	 * @jsonSerialize
	 * @var string
	 */
	#[JsonSerialize]
	protected $urlDelimiterValues = ';';

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
	 * @jsonSerialize
	 * @var array
	 */
	#[JsonSerialize]
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
	 * @inheritDoc
	 * @param  string $urlPrefixPage
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlPrefixPage ($urlPrefixPage) {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments $this */
		$this->urlPrefixPage = $urlPrefixPage;
		return $this;
	}

	/**
	 * @inheritDoc
	 * @return string
	 */
	public function GetUrlPrefixPage () {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments $this */
		return $this->urlPrefixPage;
	}

	/**
	 * @inheritDoc
	 * @param  string $urlPrefixCount
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlPrefixCount ($urlPrefixCount) {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments $this */
		$this->urlPrefixCount = $urlPrefixCount;
		return $this;
	}

	/**
	 * @inheritDoc
	 * @return string
	 */
	public function GetUrlPrefixCount () {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments $this */
		return $this->urlPrefixCount;
	}

	/**
	 * @inheritDoc
	 * @param  string $urlPrefixSort
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlPrefixSort ($urlPrefixSort) {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments $this */
		$this->urlPrefixSort = $urlPrefixSort;
		return $this;
	}

	/**
	 * @inheritDoc
	 * @return string
	 */
	public function GetUrlPrefixSort () {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments $this */
		return $this->urlPrefixSort;
	}

	/**
	 * @inheritDoc
	 * @param  string $urlPrefixFilter
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlPrefixFilter ($urlPrefixFilter) {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments $this */
		$this->urlPrefixFilter = $urlPrefixFilter;
		return $this;
	}

	/**
	 * @inheritDoc
	 * @return string
	 */
	public function GetUrlPrefixFilter () {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments $this */
		return $this->urlPrefixFilter;
	}

	/**
	 * @inheritDoc
	 * @param  string $urlSuffixSortAsc
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlSuffixSortAsc ($urlSuffixSortAsc) {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments $this */
		$this->urlSuffixSortAsc = $urlSuffixSortAsc;
		return $this;
	}

	/**
	 * @inheritDoc
	 * @return string
	 */
	public function GetUrlSuffixSortAsc () {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments $this */
		return $this->urlSuffixSortAsc;
	}

	/**
	 * @inheritDoc
	 * @param  string $urlSuffixSortDesc
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlSuffixSortDesc ($urlSuffixSortDesc) {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments $this */
		$this->urlSuffixSortDesc = $urlSuffixSortDesc;
		return $this;
	}

	/**
	 * @inheritDoc
	 * @return string
	 */
	public function GetUrlSuffixSortDesc () {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments $this */
		return $this->urlSuffixSortDesc;
	}

	/**
	 * @inheritDoc
	 * @param  string $urlDelimiterSections
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlDelimiterSections ($urlDelimiterSections) {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments $this */
		$this->urlDelimiterSections = $urlDelimiterSections;
		return $this;
	}

	/**
	 * @inheritDoc
	 * @return string
	 */
	public function GetUrlDelimiterSections () {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments $this */
		return $this->urlDelimiterSections;
	}

	/**
	 * @inheritDoc
	 * @param  string $urlDelimiterPrefix
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlDelimiterPrefix ($urlDelimiterPrefix) {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments $this */
		$this->urlDelimiterPrefix = $urlDelimiterPrefix;
		return $this;
	}

	/**
	 * @inheritDoc
	 * @return string
	 */
	public function GetUrlDelimiterPrefix () {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments $this */
		return $this->urlDelimiterPrefix;
	}

	/**
	 * @inheritDoc
	 * @param  string $urlDelimiterSubjects
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlDelimiterSubjects ($urlDelimiterSubjects) {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments $this */
		$this->urlDelimiterSubjects = $urlDelimiterSubjects;
		return $this;
	}

	/**
	 * @inheritDoc
	 * @return string
	 */
	public function GetUrlDelimiterSubjects () {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments $this */
		return $this->urlDelimiterSubjects;
	}
	
	/**
	 * @inheritDoc
	 * @param  string $urlDelimiterSubjectValue
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlDelimiterSubjectValue ($urlDelimiterSubjectValue) {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments $this */
		$this->urlDelimiterSubjectValue = $urlDelimiterSubjectValue;
		return $this;
	}

	/**
	 * @inheritDoc
	 * @return string
	 */
	public function GetUrlDelimiterSubjectValue () {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments $this */
		return $this->urlDelimiterSubjectValue;
	}

	/**
	 * @inheritDoc
	 * @param  string $urlDelimiterValues
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlDelimiterValues ($urlDelimiterValues) {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments $this */
		$this->urlDelimiterValues = $urlDelimiterValues;
		return $this;
	}

	/**
	 * @inheritDoc
	 * @return string
	 */
	public function GetUrlDelimiterValues () {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments $this */
		return $this->urlDelimiterValues;
	}
	
	/**
	 * @inheritDoc
	 * @param  array $urlFilterOperators
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetUrlFilterOperators ($urlFilterOperators) {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments $this */
		$this->urlFilterOperators = $urlFilterOperators;
		return $this;
	}

	/**
	 * @inheritDoc
	 * @return array<string, string>
	 */
	public function GetUrlFilterOperators () {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments $this */
		return $this->urlFilterOperators;
	}

	/**
	 * @inheritDoc
	 * @param  string|NULL $routePattern
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments
	 */
	public function SetRoutePattern ($routePattern) {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments $this */
		$this->routePattern = $routePattern;
		return $this;
	}

	/**
	 * @inheritDoc
	 * @internal
	 * @param  int    $sortingMode 
	 * @param  int    $filteringMode
	 * @return string
	 */
	public function GetRoutePattern ($sortingMode = \MvcCore\Ext\Controllers\IDataGrid::SORT_DISABLED, $filteringMode = \MvcCore\Ext\Controllers\IDataGrid::FILTER_DISABLED) {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Configs\UrlSegments $this */
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

	/**
	 * Return data for JSON serialization.
	 * @return array|mixed
	 */
	#[\ReturnTypeWillChange]
	public function jsonSerialize () {
		return JsonSerialize::Serialize($this, \ReflectionProperty::IS_PROTECTED);
	}
}