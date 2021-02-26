<?php

namespace MvcCore\Ext\Controllers\DataGrids\Paging;

class Item {
	
	/**
	 * 
	 * @var string|NULL
	 */
	protected $url = NULL;

	/**
	 * 
	 * @var string|NULL
	 */
	protected $text = NULL;

	/**
	 * 
	 * @var bool
	 */
	protected $current = FALSE;

	/**
	 * 
	 * @var bool
	 */
	protected $prev = FALSE;

	/**
	 * 
	 * @var bool
	 */
	protected $next = FALSE;

	/**
	 * 
	 * @var bool
	 */
	protected $first = FALSE;

	/**
	 * 
	 * @var bool
	 */
	protected $last = FALSE;
	
	/**
	 * 
	 * @var string|NULL
	 */
	protected $cssClass = NULL;

	/**
	 * 
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
		$this->text = (string) $text;
		$this->current = $current;
		$this->prev = $isPrev;
		$this->next = $isNext;
		$this->first = $isFirst;
		$this->last = $isLast;
	}
	
	/**
	 * 
	 * @return string|NULL
	 */
	public function GetUrl () {
		return $this->url;
	}
	
	/**
	 * 
	 * @return string|NULL
	 */
	public function GetText () {
		return $this->text;
	}
	
	/**
	 * 
	 * @param  bool $current
	 * @return \MvcCore\Ext\Controllers\DataGrids\Paging\Item
	 */
	public function SetIsCurrent ($current) {
		$this->current = $current;
		return $this;
	}

	/**
	 * 
	 * @return bool
	 */
	public function IsCurrent () {
		return $this->current;
	}
	
	/**
	 * 
	 * @param  bool $prev
	 * @return \MvcCore\Ext\Controllers\DataGrids\Paging\Item
	 */
	public function SetIsPrev ($prev) {
		$this->prev = $prev;
		return $this;
	}

	/**
	 * 
	 * @return bool
	 */
	public function IsPrev () {
		return $this->prev;
	}
	
	/**
	 * 
	 * @param  bool $next
	 * @return \MvcCore\Ext\Controllers\DataGrids\Paging\Item
	 */
	public function SetIsNext ($next) {
		$this->next = $next;
		return $this;
	}

	/**
	 * 
	 * @return bool
	 */
	public function IsNext () {
		return $this->next;
	}

	/**
	 * 
	 * @param  bool $first
	 * @return \MvcCore\Ext\Controllers\DataGrids\Paging\Item
	 */
	public function SetIsFirst ($first) {
		$this->first = $first;
		return $this;
	}

	/**
	 * 
	 * @return bool
	 */
	public function IsFirst () {
		return $this->first;
	}
	
	/**
	 * 
	 * @param  bool $last
	 * @return \MvcCore\Ext\Controllers\DataGrids\Paging\Item
	 */
	public function SetIsLast ($last) {
		$this->last = $last;
		return $this;
	}

	/**
	 * 
	 * @return bool
	 */
	public function IsLast () {
		return $this->last;
	}

	/**
	 * 
	 * @param  string|NULL $cssClass
	 * @return \MvcCore\Ext\Controllers\DataGrids\Paging\Item
	 */
	public function SetCssClass ($cssClass) {
		$this->cssClass;
		return $this;
	}

	/**
	 * 
	 * @return string|NULL
	 */
	public function GetCssClass () {
		return $this->cssClass;
	}
}