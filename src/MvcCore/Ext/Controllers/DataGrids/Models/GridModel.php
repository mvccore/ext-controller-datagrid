<?php

namespace MvcCore\Ext\Controllers\DataGrids\Models;

trait GridModel {

	/**
	 * 
	 * @var int|NULL
	 */
	protected $offset = NULL;
	
	/**
	 * 
	 * @var int|NULL
	 */
	protected $limit = NULL;
	
	/**
	 * 
	 * @var int|NULL
	 */
	protected $totalCount = NULL;
	
	/**
	 * 
	 * @var \MvcCore\Ext\Models\Db\Readers\Streams\Iterator|array|NULL
	 */
	protected $pageData = NULL;

	/**
	 * 
	 * @var array|NULL
	 */
	protected $filtering = NULL;
	
	/**
	 * 
	 * @var array|NULL
	 */
	protected $ordering = NULL;


	/**
	 * 
	 * @param  int|NULL $offset 
	 * @return \MvcCore\Ext\Controllers\DataGrids\Models\GridModel
	 */
	public function SetOffset ($offset) {
		$this->offset = $offset;
		return $this;
	}

	/**
	 * 
	 * @param  int|NULL $limit 
	 * @return \MvcCore\Ext\Controllers\DataGrids\Models\GridModel
	 */
	public function SetLimit ($limit) {
		$this->limit = $limit;
		return $this;
	}

	/**
	 * 
	 * @param  array $filtering 
	 * @return \MvcCore\Ext\Controllers\DataGrids\Models\GridModel
	 */
	public function SetFiltering ($filtering) {
		$this->filtering = $filtering;
		return $this;
	}

	/**
	 * 
	 * @param  array $ordering 
	 * @return \MvcCore\Ext\Controllers\DataGrids\Models\GridModel
	 */
	public function SetOrdering ($ordering) {
		$this->ordering = $ordering;
		return $this;
	}
	
	/**
	 * 
	 * @return int
	 */
	public function GetTotalCount () {
		if ($this->totalCount === NULL) $this->load();
		return $this->totalCount;
	}

	/**
	 * 
	 * @return \MvcCore\Ext\Models\Db\Readers\Streams\Iterator|array|NULL
	 */
	public function GetPageData () {
		if ($this->pageData === NULL) $this->load();
		return $this->pageData;
	}

	/**
	 * @return void
	 */
	protected abstract function load ();
}
