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

namespace MvcCore\Ext\Controllers\DataGrid;

/**
 * @mixin \MvcCore\Ext\Controllers\DataGrid
 */
trait ActionMethods {

	/**
	 * Internal default action for datagrid content rendering.
	 * @template
	 * @return void
	 */
	protected function actionDefault () {
		if ($this->configRendering->GetRenderTableHeadFiltering()) 
			$this->createTableHeadFilterForm(FALSE);
		if ($this->controlFilterForm !== NULL) {
			list (
				$columnsConfigsIterator, $filteringByPropNames
			) = $this->getFormColumnsAndFiltering();
			/** @var $form \MvcCore\Ext\Form|\MvcCore\Ext\Controllers\DataGrids\Forms\IFilterForm|\MvcCore\Controller */
			$form = $this->controlFilterForm;
			$form
				->SetConfigColumns($columnsConfigsIterator)
				->SetFiltering($filteringByPropNames);
			$this->AddChildController($form, 'controlFilterForm');
			$controlFilterFormState = $form->GetDispatchState();
			if ($controlFilterFormState < \MvcCore\IController::DISPATCH_STATE_INITIALIZED)
				$form->Init(FALSE);
		}
	}

	/**
	 * Internal factory method to create table head filter form.
	 * @template
	 * @param  bool $submit 
	 * @return void
	 */
	protected function createTableHeadFilterForm ($submit = FALSE) {
		$this->checkExtendedFormClasses();
		$formId = 'grid-table-head-filtering-'.sha1(serialize([
			$this->controllerName, $this->actionName, $this->configColumns
		]));
		$actionUrl = $this->Url(
			'self', [static::URL_PARAM_ACTION => 'filter-table']
		);
		$form = new \MvcCore\Ext\Form($this);
		$this->tableHeadFilterForm = $form;
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
		$multiFiltering = ($this->filteringMode & static::FILTER_MULTIPLE_COLUMNS) != 0;
		foreach ($this->configColumns as $configColumn) {
			$propName = $configColumn->GetPropName();
			$clearResultState++;
			if (!$configColumn->GetFilter()) continue;
			$valueField = (new \MvcCore\Ext\Forms\Fields\Text)
				->SetName(implode($form::HTML_IDS_DELIMITER, ['value', $propName]))
				->SetValidators([]);
			$dbColumnName = $configColumn->GetDbColumnName();
			if (isset($this->filtering[$dbColumnName])) {
				$columnFiltering = $this->filtering[$dbColumnName];
				// head filtering coud have only `=` | `!=` | `LIKE` | `NOT LIKE` operator values:
				$fieldValue = [];
				foreach ($columnFiltering as $operator => $values) {
					$valueOperator = static::$filterFormFieldValueOperatorPrefixes[$operator];
					$fieldValue[] = $valueOperator . implode(
						$urlDelimiterValues . $valueOperator, $columnFiltering[$operator]
					);
					if (!$multiFiltering) break;
				}
				$valueField->SetValue(implode($urlDelimiterValues, $fieldValue));
			}
			$filterField = (new \MvcCore\Ext\Forms\Fields\SubmitButton)
				->SetName(implode($form::HTML_IDS_DELIMITER, ['filter', $propName]))
				->SetValue($this->GetControlText('filter'))
				->AddCssClasses('filter');
			$clearField = (new \MvcCore\Ext\Forms\Fields\SubmitButton)
				->SetCustomResultState($clearResultState)
				->SetName(implode($form::HTML_IDS_DELIMITER, ['clear', $propName]))
				->SetValue($this->GetControlText('clear'))
				->AddCssClasses('clear');
			$form->AddFields($valueField, $filterField, $clearField);
		}
		$headFilterFormState = $form->GetDispatchState();
		if ($headFilterFormState < \MvcCore\IController::DISPATCH_STATE_PRE_DISPATCHED)
			$form->PreDispatch($submit);
	}


	/**
	 * Internal submit action for table head filter form.
	 * @template
	 * @return void
	 */
	protected function actionTableFilter () {
		if (!$this->actionTableFilterSetUp()) return;
		list ($submitResult, $newFiltering) = $this->actionTableFilterSubmit();
		$this->filterFormRedirect($submitResult, $newFiltering);
	}

	/**
	 * Check if table head filter form is allowed and redirect if necessary,
	 * prepare table head filter form instance for submitting.
	 * @return bool
	 */
	protected function actionTableFilterSetUp () {
		/** @var \MvcCore\Controller $context */
		$context = $this;
		if (!$this->configRendering->GetRenderTableHeadFiltering()) {
			$redirectUrl = $this->Url('self', [static::URL_PARAM_ACTION => NULL]);
			$context::Redirect($redirectUrl, \MvcCore\IResponse::SEE_OTHER, 'Grid has not configured table heading filter.');
		}
		$this->createTableHeadFilterForm(TRUE);
		return TRUE;
	}
	
	/**
	 * Submit table head filter form and return submit success and new filtering.
	 * New filtering has to be an array with keys as database column names and values 
	 * as arrays with keys as database operators and values as validated user input values.
	 * Control form submit method has to return values in new filtering format,
	 * keys as properties names, values as arrays with operators and values.
	 * @return array [boolean $submitResult, array $newFiltering]
	 */
	protected function actionTableFilterSubmit () {
		$form = $this->tableHeadFilterForm;
		list ($result, $rawFormValues) = $form->Submit();

		if ($result === $form::RESULT_ERRORS) 
			return [FALSE, $this->filtering];
		$form->ClearSession();
		
		$multiFiltering = ($this->filteringMode & static::FILTER_MULTIPLE_COLUMNS) != 0;
		$filteringColumns = $this->getFilteringColumns();
		
		$formValues = [];
		foreach ($rawFormValues as $propName => $rawValues) {
			$rawValues = trim((string) $rawValues);
			if (mb_strlen($rawValues) === 0) continue;
			list($subjectName, $propName) = explode($form::HTML_IDS_DELIMITER, $propName);
			if ($subjectName !== 'value' || !isset($filteringColumns[$propName])) continue;
			$formValues[$propName] = $rawValues;
		}

		$newFiltering = array_merge([], $this->filtering);
		$newFiltering = $this->GetFilteringFromFilterFormValues($formValues, $newFiltering);

		$configColumnsKeys = array_keys($this->configColumns->GetArray());
		$clearingResultBase = static::$tableHeadingFilterFormClearResultBase + 1;
		if ($result >= $clearingResultBase && isset($configColumnsKeys[$result - $clearingResultBase])) {
			$clearingColumnUrlName = $configColumnsKeys[$result - $clearingResultBase];
			$clearingColumnConfig = $this->configColumns[$clearingColumnUrlName];
			$dbColumnName = $clearingColumnConfig->GetDbColumnName();
			if (isset($newFiltering[$dbColumnName]))
				unset($newFiltering[$dbColumnName]);
		}

		return [TRUE, $newFiltering];
	}

	/**
	 * @inherit
	 * @param  array $formSubmitValues 
	 * @param  array $filtering 
	 * @return array
	 */
	public function GetFilteringFromFilterFormValues (array $formSubmitValues, array $filtering = []) {
		$multiFiltering = ($this->filteringMode & static::FILTER_MULTIPLE_COLUMNS) != 0;
		$filteringColumns = $this->getFilteringColumns();
		$urlFilterOperators = $this->configUrlSegments->GetUrlFilterOperators();
		$urlDelimiterValues = $this->configUrlSegments->GetUrlDelimiterValues();
		$likeOperatorsArrFilter = ['LIKE' => 1, 'NOT LIKE' => 1];
		$likeOperatorsAndPrefixes = array_intersect_key(static::$filterFormFieldValueOperatorPrefixes, $likeOperatorsArrFilter);
		$notLikeOperatorsAndPrefixes = array_diff_key(static::$filterFormFieldValueOperatorPrefixes, $likeOperatorsArrFilter);
		foreach ($formSubmitValues as $propName => $rawValues) {
			$configColumn = $filteringColumns[$propName];
			$rawValuesArr = explode($urlDelimiterValues, $rawValues);
			$columnFilterCfg = $configColumn->GetFilter();
			$allowedOperators = $columnFilterCfg === TRUE || !is_integer($columnFilterCfg)
				? $this->allowedOperators
				: $this->getAllowedOperators($columnFilterCfg);
			$filterValues = [];
			foreach ($rawValuesArr as $rawValue) {
				$rawValue = trim($rawValue);
				if ($rawValue === '') continue;
				$containsPercentage = $this->checkFilterFormValueForSpecialLikeChar($rawValue, '%');
				$containsUnderScore = $this->checkFilterFormValueForSpecialLikeChar($rawValue, '_');
				if ($containsPercentage || $containsUnderScore) {
					$operatorsAndPrefixes = $likeOperatorsAndPrefixes;
					if ($containsPercentage === 2) $rawValue = str_replace('[%]', '%', $rawValue);
					if ($containsUnderScore === 2) $rawValue = str_replace('[_]', '_', $rawValue);
				} else {
					$operatorsAndPrefixes = $notLikeOperatorsAndPrefixes;
				}
				foreach ($operatorsAndPrefixes as $operatorKey => $valuePrefix) {
					$valuePrefixLen = mb_strlen($valuePrefix);
					if ($valuePrefixLen > 0) {
						$valuePrefixChars = mb_substr($rawValue, 0, $valuePrefixLen);
						if ($valuePrefixChars === $valuePrefix) {
							$operator = $operatorKey;
							$rawValue = mb_substr($rawValue, $valuePrefixLen);
							break;
						}
					} else {
						$operator = $operatorKey;
						break;
					}
				}
				$rawOperatorStr = $urlFilterOperators[$operator];
				if (!isset($allowedOperators[$rawOperatorStr])) continue;
				$operatorCfg = $allowedOperators[$rawOperatorStr];
				$multiple = $operatorCfg->multiple;
				$regex = $operatorCfg->regex;
				if ($regex !== NULL && !preg_match($regex, $rawValue)) continue;
				$rawValue = $this->removeUnsafeChars($rawValue);
				if (isset($filterValues[$operator])) {
					$filterValues[$operator][] = $rawValue;
				} else {
					$filterValues[$operator] = [$rawValue];
				}
				if (!$multiple && count($filterValues[$operator]) > 1)
					$filterValues[$operator] = [$filterValues[$operator][0]];
			}
			if (count($filterValues) === 0) continue;
			$columnDbName = $configColumn->GetDbColumnName();
			if (!isset($filtering[$columnDbName])) 
				$filtering[$columnDbName] = [];
			$filtering[$columnDbName] = $filterValues;
			if (!$multiFiltering) {
				$filterValuesKeys = array_keys($filterValues);
				$filterValueKey = $filterValuesKeys[0];
				$filtering = [$columnDbName => [
					$filterValueKey => $filterValues[$filterValueKey]
				]];
				break;
			}
		}
		return $filtering;
	}


	/**
	 * Internal submit action for custom filtering form.
	 * @template
	 * @return void
	 */
	protected function actionFormFilter () {
		if (!$this->actionFormFilterSetUp()) return;
		list ($submitResult, $newFiltering) = $this->actionFormFilterSubmit();
		$this->filterFormRedirect($submitResult, $newFiltering);
	}
	
	/**
	 * Check if control filter form exists and redirect if necessary,
	 * prepare control filter form instance for submitting.
	 * @return bool
	 */
	protected function actionFormFilterSetUp () {
		/** @var \MvcCore\Controller $context */
		$context = $this;
		if ($this->controlFilterForm === NULL) {
			$redirectUrl = $this->Url('self', [static::URL_PARAM_ACTION => NULL]);
			$context::Redirect($redirectUrl, \MvcCore\IResponse::SEE_OTHER, 'Grid has not configured custom filter form.');
			return FALSE;
		}
		list (
			$columnsConfigsIterator, $filteringByPropNames
		) = $this->getFormColumnsAndFiltering();
		/** @var $form \MvcCore\Ext\Form|\MvcCore\Controller */
		$form = $this->controlFilterForm;
		$this->controlFilterForm
			->SetConfigColumns($columnsConfigsIterator)
			->SetFiltering($filteringByPropNames);
		$this->AddChildController($form, 'controlFilterForm');
		$controlFilterFormState = $form->GetDispatchState();
		if ($controlFilterFormState < \MvcCore\IController::DISPATCH_STATE_INITIALIZED)
			$this->controlFilterForm->Init(TRUE);
		if ($controlFilterFormState < \MvcCore\IController::DISPATCH_STATE_PRE_DISPATCHED)
			$this->controlFilterForm->PreDispatch(TRUE);
		return TRUE;
	}
	
	/**
	 * Submit control filter form and return submit success and new filtering.
	 * New filtering has to be an array with keys as database column names and values 
	 * as arrays with keys as database operators and values as validated user input values.
	 * Control form submit method  has to return values in new filtering format,
	 * keys as properties names, values as arrays with operators and values.
	 * @return array [boolean $submitResult, array $newFiltering]
	 */
	protected function actionFormFilterSubmit () {
		$form = $this->controlFilterForm;
		list($submitResult, $formFiltering) = $form->Submit();
		if ($submitResult === $form::RESULT_ERRORS) 
			return [FALSE, $this->filtering];
		$form->ClearSession();
		$newFiltering = array_merge([], $this->filtering);
		$urlFilterOperators = $this->configUrlSegments->GetUrlFilterOperators();
		$multiFiltering = ($this->filteringMode & static::FILTER_MULTIPLE_COLUMNS) != 0;
		$filteringColumns = $this->getFilteringColumns();
		foreach ($formFiltering as $propName => $operatorAndValues) {
			if (!isset($filteringColumns[$propName])) continue;
			$configColumn = $filteringColumns[$propName];
			$columnFilterCfg = $configColumn->GetFilter();
			$allowedOperators = $columnFilterCfg === TRUE || !is_integer($columnFilterCfg)
				? $this->allowedOperators
				: $this->getAllowedOperators($columnFilterCfg);
			$columnDbName = $configColumn->GetDbColumnName();
			if ($operatorAndValues === NULL) {
				if (isset($newFiltering[$columnDbName]))
					unset($newFiltering[$columnDbName]);
				continue;
			}
			foreach ($operatorAndValues as $operator => $newValues) {
				$operatorUrlSegment = $urlFilterOperators[$operator];
				if (!isset($allowedOperators[$operatorUrlSegment])) continue;
				$allowedOperatorCfg = $allowedOperators[$operatorUrlSegment];
				if (!isset($newFiltering[$columnDbName]))
					$newFiltering[$columnDbName] = [];
				if (is_array($newValues)) {
					if (count($newValues) === 0) {
						if (isset($newFiltering[$columnDbName][$operator]))
							unset($newFiltering[$columnDbName][$operator]);
					} else if ($allowedOperatorCfg->multiple) {
						$newFiltering[$columnDbName][$operator] = $newValues;
					} else {
						$newValuesKeys = array_keys($newValues);
						$newValuesFirstKey = $newValuesKeys[0];
						$newFiltering[$columnDbName][$operator] = [
							$newValues[$newValuesFirstKey]
						];
					}
				} else if ($newValues === NULL) {
					unset($newFiltering[$columnDbName][$operator]);
				} else {
					$newFiltering[$columnDbName][$operator] = [$newValues];
				}
			}
			if (!$multiFiltering) {
				$newFiltering = [
					$columnDbName => ['=' => $formFiltering]
				];
				break;
			}
		}
		return [TRUE, $newFiltering];
	}
	

	/**
	 * Complete redirect URL from new filtering and redirect with given reason message. 
	 * New filtering has keys as database column names and values as arrays
	 * with keys as database operators and values as validated user input values.
	 * @param  bool  $submitResult 
	 * @param  array $newFiltering 
	 * @param  bool  $tableHeadFitlering
	 * @return void
	 */
	protected function filterFormRedirect ($submitResult, $newFiltering, $tableHeadFitlering = FALSE) {
		/** @var \MvcCore\Controller $context */
		$context = $this;
		$filterParams = [];
		$urlFilterOperators = $this->configUrlSegments->GetUrlFilterOperators();
		$subjValueDelim = $this->configUrlSegments->GetUrlDelimiterSubjectValue();
		$valuesDelim = $this->configUrlSegments->GetUrlDelimiterValues();
		$subjsDelim = $this->configUrlSegments->GetUrlDelimiterSubjects();
		foreach ($this->configColumns->GetArray() as $columnUrlName => $columnConfig) {
			$columnDbName = $columnConfig->GetDbColumnName();
			if (!isset($newFiltering[$columnDbName])) continue;
			$filterOperatorsAndValues = $newFiltering[$columnDbName];
			foreach ($filterOperatorsAndValues as $operator => $filterValues) {
				foreach ($filterValues as $index => $filterValue)
					$filterValues[$index] = html_entity_decode($filterValue, ENT_NOQUOTES);
				$filterUrlValues = implode($valuesDelim, $filterValues);
				$operatorUrlValue = $urlFilterOperators[$operator];
				$filterParams[] = "{$columnUrlName}{$subjValueDelim}{$operatorUrlValue}{$subjValueDelim}{$filterUrlValues}";
			}
		}
		$page = $this->page;
		$count = $this->itemsPerPage;
		if ($count === $this->itemsPerPageRouteConfig) {
			$count = NULL;
			if ($page === 1) $page = NULL;
		}
		$redirectUrl = $this->GridUrl([
			static::URL_PARAM_ACTION	=> NULL,
			'page'						=> $page,
			'count'						=> $count,
			'filter'					=> count($filterParams) > 0 
				? implode($subjsDelim, $filterParams)
				: NULL
		]);
		if ($tableHeadFitlering) {
			$redirectReason = $submitResult
				? 'Grid table heading filter form success.'
				: 'Grid table heading filter form error.';
		} else {
			$redirectReason = $submitResult
				? 'Grid control filter form success.'
				: 'Grid control filter form error.';
		}
		$context::Redirect($redirectUrl, \MvcCore\IResponse::SEE_OTHER, $redirectReason);
	}


	/**
	 * Prepare filter form filtering, keyed by model properties names 
	 * and filter form columns config iterator - only with columns, 
	 * where is filtering allowed.
	 * @return array [\MvcCore\Ext\Controllers\DataGrids\Iterators\Columns $columnsConfigs, array $formFiltering]
	 */
	protected function getFormColumnsAndFiltering () {
		$filteringColumns = $this->getFilteringColumns();
		$columnsConfigsIterator = new \MvcCore\Ext\Controllers\DataGrids\Iterators\Columns(
			$filteringColumns
		);
		$filteringByPropNames = [];
		foreach ($filteringColumns as $propName => $configColumn) {
			$columnDbName = $configColumn->GetDbColumnName();
			if (!isset($this->filtering[$columnDbName])) continue;
			$filteringByPropNames[$propName] = $this->filtering[$columnDbName];
		}
		return [$columnsConfigsIterator, $filteringByPropNames];
	}

	/**
	 * Return columns config array with records, where is filtering 
	 * allowed only, keyed by propert names, not by url names.
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column[]
	 */
	protected function getFilteringColumns () {
		$configColumns = [];
		foreach ($this->configColumns->GetArray() as $configColumn) {
			$columnFilterCfg = $configColumn->GetFilter();
			if ($columnFilterCfg === FALSE || $columnFilterCfg === NULL) continue;
			$configColumns[$configColumn->GetPropName()] = $configColumn;
		}
		return $configColumns;
	}

	/**
	 * Check classes in extensions and throw an exception about 
	 * to install an extension if any of extended class doesn't exist.
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

	/**
	 * Check if given value contains any LIKE/NOT LIKE special 
	 * character: `%` or `_`, not escaped like this: `[%]` or `[_]`.
	 * @param  string $rawValue 
	 * @param  string $specialLikeChar 
	 * @return int
	 */
	protected function checkFilterFormValueForSpecialLikeChar ($rawValue, $specialLikeChar) {
		$containsSpecialChar = 0;
		$index = 0;
		$length = mb_strlen($rawValue);
		$matchedEscapedChar = FALSE;
		while ($index < $length) {
			$specialCharPos = mb_strpos($rawValue, $specialLikeChar, $index);
			if ($specialCharPos === FALSE) break;
			$escapedSpecialCharPos = mb_strpos($rawValue, '['.$specialLikeChar.']', max(0, $index - 1));
			if ($escapedSpecialCharPos !== FALSE && $specialCharPos - 1 === $escapedSpecialCharPos) {
				$index = $specialCharPos + mb_strlen($specialLikeChar) + 1;
				$matchedEscapedChar = TRUE;
				continue;
			}
			$containsSpecialChar = $matchedEscapedChar ? 2 : 1;
			break;
		}
		return $containsSpecialChar;
	}
}