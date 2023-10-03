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

class Page extends \MvcCore\Ext\Controllers\DataGrids\Paging\Item {
	
	/**
	 * @inheritDoc
	 * @param int|NULL        $offset 
	 * @param string|NULL     $url 
	 * @param string|int|NULL $text 
	 * @param bool            $current 
	 * @param bool            $isPrev 
	 * @param bool            $isNext 
	 * @param bool            $isFirst 
	 * @param bool            $isLast 
	 */
	public function __construct (
		$offset = NULL, $url = NULL, $text = NULL, 
		$current = FALSE, 
		$isPrev = FALSE, $isNext = FALSE,
		$isFirst = FALSE, $isLast = FALSE
	) {
		parent::__construct($offset, $url, $text, $current, $isPrev, $isNext, $isFirst, $isLast);
		$this->cssClass = $current
			? static::PAGE_CSS_CLASS_BUTTON . ' ' . static::PAGE_CSS_CLASS_CURRENT
			: static::PAGE_CSS_CLASS_BUTTON;
	}
}