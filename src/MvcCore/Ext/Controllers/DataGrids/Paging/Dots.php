<?php

namespace MvcCore\Ext\Controllers\DataGrids\Paging;

class Dots extends \MvcCore\Ext\Controllers\DataGrids\Paging\Dot {

	/**
	 * @inheritDocs
	 * @var string
	 */
	protected $text = '&hellip;';
	
	/**
	 * @inheritDocs
	 * @var string
	 */
	protected $cssClass = 'grid-page-space grid-page-space-dots';
}