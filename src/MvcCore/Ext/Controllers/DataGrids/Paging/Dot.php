<?php

namespace MvcCore\Ext\Controllers\DataGrids\Paging;

class Dot extends \MvcCore\Ext\Controllers\DataGrids\Paging\Item {

	/**
	 * @inheritDocs
	 * @var string
	 */
	protected $text = '.';
	
	/**
	 * @inheritDocs
	 * @var string
	 */
	protected $cssClass = 'grid-page-space grid-page-space-dot';

}