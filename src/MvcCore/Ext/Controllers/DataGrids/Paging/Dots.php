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

class Dots extends \MvcCore\Ext\Controllers\DataGrids\Paging\Dot {

	/**
	 * @inheritDoc
	 * @var string
	 */
	protected $text = ''; // \u{2026}
	
	/**
	 * @inheritDoc
	 * @var string
	 */
	protected $cssClass = 'grid-page-space grid-page-space-dots';
	
	/**
	 * @inheritDoc
	 * @param ?string         $url 
	 * @param string|int|null $text 
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
		$this->text = json_decode("\u2026"); // "\u2026", mb_chr(8230)
		parent::__construct($url, $text, $current, $isPrev, $isNext, $isFirst, $isLast);

	}
	
}