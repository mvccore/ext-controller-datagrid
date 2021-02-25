<?php

namespace MvcCore\Ext\Controllers\DataGrids;

class PagingItem {
	

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
	protected $empty = FALSE;

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
	 * @param string|NULL     $url 
	 * @param string|int|NULL $text 
	 * @param bool            $current 
	 * @param bool            $isPrev 
	 * @param bool            $isNext 
	 */
	public function __construct ($url = NULL, $text = NULL, $current = FALSE, $isPrev = FALSE, $isNext = FALSE) {
		$this->url = $url;
		$this->text = (string) $text;
		$this->current = $current;
		$this->empty = $url === NULL;
		$this->prev = $isPrev;
		$this->next = $isNext;
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
	 * @return bool
	 */
	public function IsCurrent () {
		return $this->current;
	}
	
	/**
	 * 
	 * @return bool
	 */
	public function IsEmpty () {
		return $this->empty;
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
	 * @return bool
	 */
	public function IsNext () {
		return $this->next;
	}
}