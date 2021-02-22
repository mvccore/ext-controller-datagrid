<?php

namespace MvcCore\Ext\Controllers\DataGrids;

interface IModel {

	/**
	 * @return int
	 */
	public function GetTotalCount ();

	/**
	 * @return array|\MvcCore\Ext\Models\Db\Readers\Streams\Iterator
	 */
	public function GetPageData ();

	/**
	 * @param  array $filtering 
	 * @return \MvcCore\Ext\Controllers\DataGrids\IModel
	 */
	public function SetFiltering (array $filtering);

	/**
	 * @param  array $ordering 
	 * @return \MvcCore\Ext\Controllers\DataGrids\IModel
	 */
	public function SetOrdering (array $ordering);

}