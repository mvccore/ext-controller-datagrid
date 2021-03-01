<?php

namespace MvcCore\Ext\Controllers\DataGrid;

trait ActionMethods {

	/**
	 * @template
	 * @return void
	 */
	protected function actionDefault () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		if ($this->configRendering->GetRenderTableHeadFiltering()) 
			$this->createTableHeadFilterForm(FALSE);
	}

	protected function createTableHeadFilterForm ($submit = FALSE) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		$this->checkExtendedFormClasses();
		$formId = 'grid-table-head-filtering-'.sha1(serialize([
			$this->controllerName, $this->actionName, $this->configColumns
		]));
		$actionUrl = $this->Url(
			'self', [static::URL_PARAM_ACTION => 'filter-table']
		);
		$form = new \MvcCore\Ext\Form($this);
		$form
			->SetId($formId)
			->SetMethod(\MvcCore\IRequest::METHOD_POST)
			->SetEnctype($form::ENCTYPE_URLENCODED)
			->SetAction($actionUrl)
			->SetFormRenderMode($form::FORM_RENDER_MODE_NO_STRUCTURE)
			->SetFieldsRenderModeDefault($form::FIELD_RENDER_MODE_NO_LABEL)
			->Init($submit);
		$urlDelimiterValues = $this->configUrlSegments->GetUrlDelimiterValues();
		foreach ($this->configColumns as $urlName => $configColumn) {
			if (!$configColumn->GetFilter()) continue;
			$filterField = (new \MvcCore\Ext\Forms\Fields\Text)
				->SetName(implode($form::HTML_IDS_DELIMITER, ['filter', $urlName]));
			$dbColumnName = $configColumn->GetDbColumnName();
			if (isset($this->filtering[$dbColumnName]))
				$filterField->SetValue(implode(
					$urlDelimiterValues, $this->filtering[$dbColumnName]
				));
			$submitField = (new \MvcCore\Ext\Forms\Fields\SubmitButton)
				->SetName(implode($form::HTML_IDS_DELIMITER, ['submit', $urlName]))
				->SetValue($this->GetControlText('filter'));
			$form->AddFields($filterField, $submitField);
		}
		$this->tableHeadFilterForm = $form;
	}

	/**
	 * @template
	 * @return void
	 */
	protected function actionTableFilterSubmit () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */


		if (!$this->configRendering->GetRenderTableHeadFiltering()) {
			

		}
		$this->createTableHeadFilterForm(TRUE);

		x($this->tableHeadFilterForm);
		
		$selfUrl = $this->Url('self');
		xxx($selfUrl);

	}

	/**
	 * @template
	 * @return void
	 */
	protected function actionFormFilterSubmit () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		xxx("actionFormFilterSubmit");
	}


	
	/**
	 * @throws \RuntimeException 
	 * @return  void
	 */
	protected function checkExtendedFormClasses () {
		foreach (static::$formExtensionsClasses as $extensionName => $formClassFullName)
			if (!class_exists($formClassFullName)) 
				throw new \RuntimeException(
					"Please install extension `{$extensionName}` ".
					"to create datagrid filtering component."
				);
	}

}