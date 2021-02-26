<?php

namespace MvcCore\Ext\Controllers\DataGrids\Paging;

class Page extends \MvcCore\Ext\Controllers\DataGrids\Paging\Item {
	
	/**
	 * @inheritDocs
	 * @var string
	 */
	protected $cssClass = 'grid-page-link';
	
	/**
	 * @inheritDocs
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
		parent::__construct($url, $text, $current, $isPrev, $isNext, $isFirst, $isLast);
		if ($current) 
			$this->cssClass .= ' grid-page-current';
	}
}