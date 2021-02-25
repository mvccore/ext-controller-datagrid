<?php

namespace MvcCore\Ext\Controllers\DataGrids\Configs;

interface IColumn {
	
	/**
	 * Get data grid model property name.
	 * @return string|NULL
	 */
	public function GetPropName ();

	/**
	 * Set data grid model property name.
	 * @param  string|NULL $propName
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetPropName ($propName);
	
	/**
	 * Get database column name.
	 * @return string|NULL
	 */
	public function GetDbColumnName ();
	
	/**
	 * Set database column name.
	 * @param  string|NULL $dbColumnName
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetDbColumnName ($dbColumnName);
	
	/**
	 * Get human readable name used in grid heading.
	 * @return string|NULL
	 */
	public function GetHumanName ();
	
	/**
	 * Set human readable name used in grid heading.
	 * @param  string|NULL $humanName
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetHumanName ($humanName);
	
	/**
	 * Get URL column name when ordering or filtering.
	 * @return string|NULL
	 */
	public function GetUrlName ();
	
	/**
	 * Set URL column name when ordering or filtering.
	 * @param  string|NULL $urlName
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetUrlName ($urlName);
	
	/**
	 * Get default ordering definition with values `ASC | DESC` 
	 * or `TRUE | FALSE` to enable/disable ordering.
	 * @return string|bool|NULL
	 */
	public function GetOrder ();
	
	/**
	 * Set default ordering definition with values `ASC | DESC` 
	 * or `TRUE | FALSE` to enable/disable ordering.
	 * @param  string|bool|NULL $order
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetOrder ($order);
	
	/**
	 * Get boolean to allow column filtering.
	 * @return bool
	 */
	public function GetFilter ();
	
	/**
	 * Set boolean to allow column filtering.
	 * @param  bool $filter
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetFilter ($filter);
	
	/**
	 * Get property type(s), necessary for automatic formating.
	 * @return \string[]|NULL
	 */
	public function GetTypes ();
	
	/**
	 * Set property type(s), necessary for automatic formating.
	 * @param  \string[] $types
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetTypes ($types);
	
	/**
	 * Get property automatic formating arguments.
	 * @return array|NULL
	 */
	public function GetFormat ();
	
	/**
	 * Set property automatic formating arguments.
	 * @param  array $format
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetFormat ($format);
	
	/**
	 * Get property automatic formating view helper name.
	 * @return array|NULL
	 */
	public function GetViewHelper ();
	
	/**
	 * Set property automatic formating view helper name.
	 * @param  array $viewHelper
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column
	 */
	public function SetViewHelper ($viewHelper);
}
