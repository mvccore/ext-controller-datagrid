<?php

namespace MvcCore\Ext\Controllers\DataGrids;

class UrlConfig {

	protected $urlPrefixes = array(
	'page'    => '',
	'count'    => '',
	'order'    => 'order',
	'filter'  => 'filter',
	);

	protected $urlSufixes = array(
	'orderAsc'  => 'a',
	'orderDesc'  => 'd',
	);

	protected $urlDelimiters = array(
	'sections'      => '/',
	'subjects'      => '-',
	'values'      => '.',
	'multipleValues'  => '.',
	);

}