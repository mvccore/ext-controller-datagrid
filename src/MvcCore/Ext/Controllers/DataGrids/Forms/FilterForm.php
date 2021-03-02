<?php

namespace MvcCore\Ext\Controllers\DataGrids\Forms;

trait FilterForm {
	
	/**
	 * Form HTTP method.
	 * @var string
	 */
	protected $method = 'POST';

	/**
	 * Form enctype method.
	 * @var string
	 */
	protected $enctype = 'application/x-www-form-urlencoded';

	/**
	 * Datagrid columns configuration.
	 * @var \MvcCore\Ext\Controllers\DataGrids\Iterators\Columns|NULL
	 */
	protected $configColumns = NULL;

	/**
	 * Datagrid filtering parsed from URL.
	 * @var array|NULL
	 */
	protected $filtering = NULL;

	/**
	 * Set datagrid columns configuration.
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Iterators\Columns $configColumns
	 * @return \MvcCore\Ext\Controllers\DataGrids\Forms\FilterForm
	 */
	public function SetConfigColumns ($configColumns) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Forms\FilterForm|\MvcCore\Ext\Form */
		$this->configColumns = $configColumns;
		return $this;
	}
	
	/**
	 * Set datagrid filtering parsed from URL.
	 * Keys are database column names, values are arrays 
	 * with operator as key and raw filtering values as values.
	 * @param  array $filtering
	 * @return \MvcCore\Ext\Controllers\DataGrids\Forms\FilterForm
	 */
	public function SetFiltering ($filtering) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Forms\FilterForm|\MvcCore\Ext\Form */
		$this->filtering = $filtering;
		return $this;
	}

	/**
	 * Create \MvcCore\Ext\Form instance.
	 * Please don't forget to configure at least $form->Id, $form->Action,
	 * any control to work with and finally any button:submit/input:submit
	 * to submit the form to any URL defined in $form->Action.
	 * @param  \MvcCore\Controller|NULL $grid Controller instance, where the form is created.
	 * @return void
	 */
	public function __construct (\MvcCore\Ext\Controllers\IDataGrid $grid) {
		parent::__construct($grid);
		if ($this->action === NULL) {
			$actionUrl = $grid->Url(
				'self', [$grid::URL_PARAM_ACTION => 'filter-form']
			);
			$this->SetAction($actionUrl);
		}
	}
}
