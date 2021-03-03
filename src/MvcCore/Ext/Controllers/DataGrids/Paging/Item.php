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

namespace MvcCore\Ext\Controllers\DataGrids\Paging;

class Item {
	
	/**
	 * Paging link URL.
	 * @var string|NULL
	 */
	protected $url = NULL;

	/**
	 * Paging link text.
	 * @var string|NULL
	 */
	protected $text = NULL;

	/**
	 * Current page boolean.
	 * @var bool
	 */
	protected $current = FALSE;

	/**
	 * Page link with previous page text.
	 * @var bool
	 */
	protected $prev = FALSE;

	/**
	 * Page link with next page text.
	 * @var bool
	 */
	protected $next = FALSE;

	/**
	 * Page link with first page text.
	 * @var bool
	 */
	protected $first = FALSE;

	/**
	 * Page link with last page text.
	 * @var bool
	 */
	protected $last = FALSE;
	
	/**
	 * Page link css class.
	 * @var string|NULL
	 */
	protected $cssClass = NULL;

	/**
	 * Create paging item instance.
	 * @param string|NULL     $url 
	 * @param string|int|NULL $text 
	 * @param bool            $current 
	 * @param bool            $isPrev 
	 * @param bool            $isNext 
	 * @param bool            $isFirst 
	 * @param bool            $isLast 
	 */
	public function __construct (
		$url = NULL, $text = NULL, 
		$current = FALSE, 
		$isPrev = FALSE, $isNext = FALSE,
		$isFirst = FALSE, $isLast = FALSE
	) {
		$this->url = $url;
		if ($text !== NULL) $this->text = (string) $text;
		$this->current = $current;
		$this->prev = $isPrev;
		$this->next = $isNext;
		$this->first = $isFirst;
		$this->last = $isLast;
	}
	
	/**
	 * Get paging link URL.
	 * @return string|NULL
	 */
	public function GetUrl () {
		return $this->url;
	}
	
	/**
	 * Get paging link text.
	 * @return string|NULL
	 */
	public function GetText () {
		return $this->text;
	}
	
	/**
	 * Set `TRUE` if paging item is current page.
	 * @param  bool $current
	 * @return \MvcCore\Ext\Controllers\DataGrids\Paging\Item
	 */
	public function SetIsCurrent ($current) {
		$this->current = $current;
		return $this;
	}

	/**
	 * Get `TRUE` if paging item is current page.
	 * @return bool
	 */
	public function IsCurrent () {
		return $this->current;
	}
	
	/**
	 * Set `TRUE` if paging item contains previous page text.
	 * @param  bool $prev
	 * @return \MvcCore\Ext\Controllers\DataGrids\Paging\Item
	 */
	public function SetIsPrev ($prev) {
		$this->prev = $prev;
		return $this;
	}

	/**
	 * Get `TRUE` if paging item contains previous page text.
	 * @return bool
	 */
	public function IsPrev () {
		return $this->prev;
	}
	
	/**
	 * Set `TRUE` if paging item contains next page text.
	 * @param  bool $next
	 * @return \MvcCore\Ext\Controllers\DataGrids\Paging\Item
	 */
	public function SetIsNext ($next) {
		$this->next = $next;
		return $this;
	}

	/**
	 * Get `TRUE` if paging item contains next page text.
	 * @return bool
	 */
	public function IsNext () {
		return $this->next;
	}

	/**
	 * Set `TRUE` if paging item contains first page text.
	 * @param  bool $first
	 * @return \MvcCore\Ext\Controllers\DataGrids\Paging\Item
	 */
	public function SetIsFirst ($first) {
		$this->first = $first;
		return $this;
	}

	/**
	 * Get `TRUE` if paging item contains first page text.
	 * @return bool
	 */
	public function IsFirst () {
		return $this->first;
	}
	
	/**
	 * Set `TRUE` if paging item contains last page text.
	 * @param  bool $last
	 * @return \MvcCore\Ext\Controllers\DataGrids\Paging\Item
	 */
	public function SetIsLast ($last) {
		$this->last = $last;
		return $this;
	}

	/**
	 * Get `TRUE` if paging item contains last page text.
	 * @return bool
	 */
	public function IsLast () {
		return $this->last;
	}

	/**
	 * Set paging item css class atribute.
	 * @param  string|NULL $cssClass
	 * @return \MvcCore\Ext\Controllers\DataGrids\Paging\Item
	 */
	public function SetCssClass ($cssClass) {
		$this->cssClass;
		return $this;
	}

	/**
	 * Get paging item css class atribute.
	 * @return string|NULL
	 */
	public function GetCssClass () {
		return $this->cssClass;
	}
}