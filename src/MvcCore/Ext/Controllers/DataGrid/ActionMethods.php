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
		$clearResultState = static::$tableHeadingFilterFormClearResultBase;
		foreach ($this->configColumns as $urlName => $configColumn) {
			$clearResultState++;
			if (!$configColumn->GetFilter()) continue;
			$valueField = (new \MvcCore\Ext\Forms\Fields\Text)
				->SetName(implode($form::HTML_IDS_DELIMITER, ['value', $urlName]))
				->SetValidators([]);
			$dbColumnName = $configColumn->GetDbColumnName();
			if (isset($this->filtering[$dbColumnName])) {
				$columnFiltering = $this->filtering[$dbColumnName];
				// head filtering coud have only equal operator values:
				if (isset($columnFiltering['=']))
					$valueField->SetValue(implode(
						$urlDelimiterValues, $columnFiltering['=']
					));
			}
			$filterField = (new \MvcCore\Ext\Forms\Fields\SubmitButton)
				->SetName(implode($form::HTML_IDS_DELIMITER, ['filter', $urlName]))
				->SetValue($this->GetControlText('filter'));
			$clearField = (new \MvcCore\Ext\Forms\Fields\SubmitButton)
				->SetCustomResultState($clearResultState)
				->SetName(implode($form::HTML_IDS_DELIMITER, ['clear', $urlName]))
				->SetValue($this->GetControlText('clear'));
			$form->AddFields($valueField, $filterField, $clearField);
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
			$redirectUrl = $this->Url('self', [static::URL_PARAM_ACTION => NULL]);
			self::Redirect($redirectUrl, \MvcCore\IResponse::SEE_OTHER, 'Grid has not configured table heading filter.');
		}
		$this->createTableHeadFilterForm(TRUE);
		$form = $this->tableHeadFilterForm;
		list ($result, $values) = $form->Submit();
		$subjValueDelim = $this->configUrlSegments->GetUrlDelimiterSubjectValue();
		$valuesDelim = $this->configUrlSegments->GetUrlDelimiterValues();
		$subjsDelim = $this->configUrlSegments->GetUrlDelimiterSubjects();
		$currentFilterDbNames = array_merge([], $this->filtering);
		$redirectReason = 'Grid table heading filter error.';
		if ($result !== $form::RESULT_ERRORS) {
			$redirectReason = 'Grid table heading filter success.';
			foreach ($values as $filteringName => $rawValues) {
				$safeStringValues = $this->removeUnsafeChars($rawValues);
				if ($safeStringValues === NULL) continue;
				list($subjectName, $urlName) = explode($form::HTML_IDS_DELIMITER, $filteringName);
				if ($subjectName !== 'value' || !isset($this->configColumns[$urlName])) continue;
				$configColumn = $this->configColumns[$urlName];
				$safeStringValuesArr = explode($valuesDelim, $safeStringValues);
				$values = [];
				foreach ($safeStringValuesArr as $safeStringValue) {
					$safeStringValue = trim($safeStringValue);
					if ($safeStringValue !== '') $values[] = $safeStringValue;
				}
				$currentFilterDbNames[$configColumn->GetDbColumnName()] = $values;
			}
			$configColumnsKeys = array_keys($this->configColumns->GetArray());
			$clearingResultBase = static::$tableHeadingFilterFormClearResultBase + 1;
			if ($result >= $clearingResultBase && isset($configColumnsKeys[$result - $clearingResultBase])) {
				$clearingColumnUrlName = $configColumnsKeys[$result - $clearingResultBase];
				$clearingColumnConfig = $this->configColumns[$clearingColumnUrlName];
				unset($currentFilterDbNames[$clearingColumnConfig->GetDbColumnName()]);
			}
		}
		$form->ClearSession();
		$filterParams = [];
		foreach ($this->configColumns->GetArray() as $columnUrlName => $columnConfig) {
			$columnDbName = $columnConfig->GetDbColumnName();
			if (!isset($currentFilterDbNames[$columnDbName])) continue;
			$filterValues = $currentFilterDbNames[$columnDbName];
			$filterUrlValues = implode($valuesDelim, $filterValues);
			$filterParams[] = "{$columnUrlName}{$subjValueDelim}{$filterUrlValues}";
		}
		$redirectUrl = $this->GridUrl([
			static::URL_PARAM_ACTION	=> NULL,
			'filter'					=> implode($subjsDelim, $filterParams)
		]);
		self::Redirect($redirectUrl, \MvcCore\IResponse::SEE_OTHER, $redirectReason);
	}

	/**
	 * @template
	 * @return void
	 */
	protected function actionFormFilterSubmit () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		if (!$this->configRendering->GetRenderFilterForm()) {
			$redirectUrl = $this->Url('self', [static::URL_PARAM_ACTION => NULL]);
			self::Redirect($redirectUrl, \MvcCore\IResponse::SEE_OTHER, 'Grid has not configured custom filter form.');
		}

		// TODO
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