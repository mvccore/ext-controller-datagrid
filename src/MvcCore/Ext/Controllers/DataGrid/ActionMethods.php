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
		if ($this->configRendering->GetRenderFilterForm()) {
			$this->controlFilterForm
				->SetConfigColumns($this->configColumns)
				->SetFiltering($this->filtering);
			$this->AddChildController($this->controlFilterForm, 'controlFilterForm');
			$controlFilterFormState = $this->controlFilterForm->GetDispatchState();
			if ($controlFilterFormState < \MvcCore\IController::DISPATCH_STATE_INITIALIZED)
				$this->controlFilterForm->Init(FALSE);
		}
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
		$urlFilterOperators = $this->configUrlSegments->GetUrlFilterOperators();
		$currentFilterDbNames = array_merge([], $this->filtering);
		$redirectReason = 'Grid table heading filter error.';
		if ($result !== $form::RESULT_ERRORS) {
			$redirectReason = 'Grid table heading filter success.';
			$multiFiltering = ($this->filteringMode & static::FILTER_MULTIPLE_COLUMNS) != 0;
			foreach ($values as $filteringName => $rawValues) {
				$safeStringValues = $this->removeUnsafeChars($rawValues);
				if ($safeStringValues === NULL) continue;
				list($subjectName, $urlName) = explode($form::HTML_IDS_DELIMITER, $filteringName);
				if ($subjectName !== 'value' || !isset($this->configColumns[$urlName])) continue;
				$configColumn = $this->configColumns[$urlName];
				$columnFilterCfg = $configColumn->GetFilter();
				if ($columnFilterCfg === FALSE || $columnFilterCfg === NULL) continue;
				$allowedOperators = $columnFilterCfg === TRUE || !is_integer($columnFilterCfg)
					? $this->allowedOperators
					: $this->getAllowedOperators($columnFilterCfg);
				$safeStringValuesArr = explode($valuesDelim, $safeStringValues);
				$values = [];
				foreach ($safeStringValuesArr as $safeStringValue) {
					$safeStringValue = trim($safeStringValue);
					if ($safeStringValue !== '') 
						$values[] = $safeStringValue;
				}
				if (count($values) === 0) continue;
				$columnDbName = $configColumn->GetDbColumnName();
				if (!isset($currentFilterDbNames[$columnDbName]))
					$currentFilterDbNames[$columnDbName] = [];
				$currentFilterDbNames[$columnDbName]['='] = $values;
				if (!$multiFiltering) {
					$currentFilterDbNames = [
						$columnDbName => ['=' => $values]
					];
					break;
				}
			}
			$configColumnsKeys = array_keys($this->configColumns->GetArray());
			$clearingResultBase = static::$tableHeadingFilterFormClearResultBase + 1;
			if ($result >= $clearingResultBase && isset($configColumnsKeys[$result - $clearingResultBase])) {
				$clearingColumnUrlName = $configColumnsKeys[$result - $clearingResultBase];
				$clearingColumnConfig = $this->configColumns[$clearingColumnUrlName];
				$dbColumnName = $clearingColumnConfig->GetDbColumnName();
				if (isset($currentFilterDbNames[$dbColumnName]['=']))
					unset($currentFilterDbNames[$dbColumnName]['=']);
			}
		}
		$form->ClearSession();
		$filterParams = [];
		foreach ($this->configColumns->GetArray() as $columnUrlName => $columnConfig) {
			$columnDbName = $columnConfig->GetDbColumnName();
			if (!isset($currentFilterDbNames[$columnDbName])) continue;
			$filterOperatorsAndValues = $currentFilterDbNames[$columnDbName];
			foreach ($filterOperatorsAndValues as $operator => $filterValues) {
				$filterUrlValues = implode($valuesDelim, $filterValues);
				$operatorUrlValue = $urlFilterOperators[$operator];
				$filterParams[] = "{$columnUrlName}{$subjValueDelim}{$operatorUrlValue}{$subjValueDelim}{$filterUrlValues}";
			}
		}
		$redirectUrl = $this->GridUrl([
			static::URL_PARAM_ACTION	=> NULL,
			'filter'					=> count($filterParams) > 0 
				? implode($subjsDelim, $filterParams)
				: NULL
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
		/** @var $this \MvcCore\Ext\Form|\MvcCore\Ext\Controllers\DataGrids\Forms\IFilterForm */
		$form = $this->controlFilterForm;
		$form
			->SetConfigColumns($this->configColumns)
			->SetFiltering($this->filtering);
		$this->AddChildController($form, 'controlFilterForm');
		$controlFilterFormState = $form->GetDispatchState();
		if ($controlFilterFormState < \MvcCore\IController::DISPATCH_STATE_INITIALIZED)
			$form->Init(TRUE);

		list($result, $values) = $form->Submit();
		$subjValueDelim = $this->configUrlSegments->GetUrlDelimiterSubjectValue();
		$valuesDelim = $this->configUrlSegments->GetUrlDelimiterValues();
		$subjsDelim = $this->configUrlSegments->GetUrlDelimiterSubjects();
		$urlFilterOperators = $this->configUrlSegments->GetUrlFilterOperators();
		$currentFilterDbNames = array_merge([], $this->filtering);
		$redirectReason = 'Grid control filter form error.';
		if ($result !== $form::RESULT_ERRORS) {
			$redirectReason = 'Grid table heading filter success.';
			$multiFiltering = ($this->filteringMode & static::FILTER_MULTIPLE_COLUMNS) != 0;
			foreach ($values as $urlName => $operatorAndValues) {
				if (!isset($this->configColumns[$urlName])) continue;
				$configColumn = $this->configColumns[$urlName];
				$columnFilterCfg = $configColumn->GetFilter();
				if ($columnFilterCfg === FALSE || $columnFilterCfg === NULL) continue;
				$allowedOperators = $columnFilterCfg === TRUE || !is_integer($columnFilterCfg)
					? $this->allowedOperators
					: $this->getAllowedOperators($columnFilterCfg);
				$columnDbName = $configColumn->GetDbColumnName();
				if ($operatorAndValues === NULL) {
					if (isset($currentFilterDbNames[$columnDbName]))
						unset($currentFilterDbNames[$columnDbName]);
					continue;
				}
				$values = [];
				foreach ($operatorAndValues as $operator => $values) {
					if (!isset($currentFilterDbNames[$columnDbName]))
						$currentFilterDbNames[$columnDbName] = [];
					if (is_array($values)) {
						if (count($values) === 0) {
							if (isset($currentFilterDbNames[$columnDbName][$operator]))
								unset($currentFilterDbNames[$columnDbName][$operator]);
							continue;
						} else {
							$currentFilterDbNames[$columnDbName][$operator] = $values;
						}
					} else if ($values === NULL) {
						unset($currentFilterDbNames[$columnDbName][$operator]);
					} else {
						$currentFilterDbNames[$columnDbName][$operator] = [$values];
					}
				}
				if (!$multiFiltering) {
					$currentFilterDbNames = [
						$columnDbName => ['=' => $values]
					];
					break;
				}
			}
			
		}
		$form->ClearSession();
		$filterParams = [];
		foreach ($this->configColumns->GetArray() as $columnUrlName => $columnConfig) {
			$columnDbName = $columnConfig->GetDbColumnName();
			if (!isset($currentFilterDbNames[$columnDbName])) continue;
			$filterOperatorsAndValues = $currentFilterDbNames[$columnDbName];
			foreach ($filterOperatorsAndValues as $operator => $filterValues) {
				$filterUrlValues = implode($valuesDelim, $filterValues);
				$operatorUrlValue = $urlFilterOperators[$operator];
				$filterParams[] = "{$columnUrlName}{$subjValueDelim}{$operatorUrlValue}{$subjValueDelim}{$filterUrlValues}";
			}
		}
		$redirectUrl = $this->GridUrl([
			static::URL_PARAM_ACTION	=> NULL,
			'filter'					=> count($filterParams) > 0 
				? implode($subjsDelim, $filterParams)
				: NULL
		]);
		self::Redirect($redirectUrl, \MvcCore\IResponse::SEE_OTHER, $redirectReason);
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