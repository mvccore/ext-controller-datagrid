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

namespace MvcCore\Ext\Controllers\DataGrids\Forms;

/**
 * @mixin \MvcCore\Ext\Form
 */
trait TFilterForm {
	
	/**
	 * Form HTTP method.
	 * @var string
	 */
	#protected $method = 'POST';

	/**
	 * Form enctype method.
	 * @var string
	 */
	#protected $enctype = 'application/x-www-form-urlencoded';

	/**
	 * Datagrid columns configuration.
	 * @var ?\MvcCore\Ext\Controllers\DataGrids\Iterators\Columns
	 */
	protected $configColumns = NULL;

	/**
	 * Datagrid filtering parsed from URL.
	 * Keys are model properties column names, values are arrays 
	 * with operator as key and raw filtering values as values.
	 * @var ?array
	 */
	protected $filtering = NULL;

	/**
	 * Set datagrid columns configuration.
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Iterators\Columns $configColumns
	 * @return \MvcCore\Ext\Controllers\DataGrids\Forms\TFilterForm
	 */
	public function SetConfigColumns ($configColumns) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Forms\TFilterForm|\MvcCore\Ext\Form */
		$this->configColumns = $configColumns;
		return $this;
	}
	
	/**
	 * Set datagrid filtering parsed from URL.
	 * Keys are model properties column names, values are arrays 
	 * with operator as key and raw filtering values as values.
	 * @param  array $filtering
	 * @return \MvcCore\Ext\Controllers\DataGrids\Forms\TFilterForm
	 */
	public function SetFiltering ($filtering) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Forms\TFilterForm|\MvcCore\Ext\Form */
		$this->filtering = $filtering;
		return $this;
	}

	/**
	 * Create \MvcCore\Ext\Form instance.
	 * Please don't forget to configure at least $form->Id, $form->Action,
	 * any control to work with and finally any button:submit/input:submit
	 * to submit the form to any URL defined in $form->Action.
	 * @param  ?\MvcCore\Controller $grid Controller instance, where the form is created.
	 * @return void
	 */
	public function __construct (\MvcCore\Ext\Controllers\IDataGrid $grid) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Forms\TFilterForm|\MvcCore\Ext\Form */
		parent::__construct($grid);
		if ($this->action === NULL) {
			$actionUrl = $grid->GetParentController()->Url(
				$grid->GetAppRouteName(), 
				[$grid::URL_PARAM_ACTION => 'filter-form']
			);
			$this->SetAction($actionUrl);
		}
	}

	/**
	 * Returned `$values` array has to be in following filter format:
	 * - keys has to be database column names,
	 * - values has to be arrays
	 *    - keys has to be database condition operators
	 *    - values has to be validated user input values
	 * @param  array $rawRequestParams 
	 * @return array [int $result, array $values, array $errors]
	 */
	public abstract function Submit (array & $rawRequestParams = []);
}
