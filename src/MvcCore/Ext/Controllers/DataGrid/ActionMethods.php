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
	public function ActionDefault () {
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
		$actionUrl = parent::Url(
			$this->appRouteName, [static::URL_PARAM_ACTION => 'filter-table']
		);
		$form = new \MvcCore\Ext\Form($this);
		$this->tableHeadFilterForm = $form;
		$form
			->SetId($formId)
			->SetMethod(\MvcCore\IRequest::METHOD_POST)
			->SetEnctype($form::ENCTYPE_URLENCODED)
			->SetAction($actionUrl)
			->SetFormRenderMode($form::FORM_RENDER_MODE_NO_STRUCTURE)
			->SetFieldsRenderModeDefault($form::FIELD_RENDER_MODE_NO_LABEL);
		$clearBtnResultState = static::$tableHeadingFilterFormClearResultBase;
		$multiFiltering = ($this->filteringMode & static::FILTER_MULTIPLE_COLUMNS) != 0;
		$viewExists = $this->view !== NULL;
		$this->view = $this->createView(TRUE);
		foreach ($this->configColumns as $configColumn) {
			$clearBtnResultState++;
			if ($configColumn->GetDisabled() || !$configColumn->GetFilter()) continue;
			$fields = $this->createTableHeadFilterFormColumnFields(
				$configColumn, $clearBtnResultState, $multiFiltering
			);
			$form->AddFields($fields);
		}
		if (!$viewExists) $this->view = NULL;
		$controlFilterFormState = $form->GetDispatchState();
		if ($controlFilterFormState < \MvcCore\IController::DISPATCH_STATE_INITIALIZED)
			$form->Init($submit);
	}

	/**
	 * Internal factory method to create table head filter form column fields.
	 * @template
	 * @param  \MvcCore\Ext\Controllers\DataGrids\Configs\IColumn $configColumn
	 * @param  int                                                $clearBtnResultState
	 * @param  bool                                               $multiFiltering
	 * @return array
	 */
	protected function createTableHeadFilterFormColumnFields (
		\MvcCore\Ext\Controllers\DataGrids\Configs\IColumn $configColumn, 
		$clearBtnResultState,
		$multiFiltering
	) {
		$form = $this->tableHeadFilterForm;
		$propName = $configColumn->GetPropName();
		$valueField = (new \MvcCore\Ext\Forms\Fields\Text)
			->SetName(implode($form::HTML_IDS_DELIMITER, ['value', $propName]))
			->SetValidators([]);
		$dbColumnName = $configColumn->GetDbColumnName();
		$viewHelperName = $configColumn->GetViewHelper();
		list ($useViewHelper, $viewHelper) = $this->getFilteringViewHelper($viewHelperName);
		if (isset($this->filtering[$dbColumnName])) {
			$columnFiltering = $this->filtering[$dbColumnName];
			// head filtering coud have only `=` | `!=` | `LIKE` | `NOT LIKE` operator values:
			$fieldValue = [];
			$columnFilter = $configColumn->GetFilter();
			$columnAllowNullFilter = is_int($columnFilter) && ($columnFilter & self::FILTER_ALLOW_NULL) != 0;
			foreach ($columnFiltering as $operator => $values) {
				$valueOperator = static::$filterFormFieldValueOperatorPrefixes[$operator];
				if (!$multiFiltering) 
					$values = [$values[0]];
				foreach ($values as $index => $value) {
					if (strtolower($value) === 'null') {
						if (!$columnAllowNullFilter) {
							unset($values[$index]);
							continue;
						}
					} else if ($useViewHelper) {
						$values[$index] = call_user_func_array(
							[$viewHelper, $viewHelperName], 
							array_merge([$value], $configColumn->GetFormat() ?: [])
						);
					}
				}
				$fieldValue[] = $valueOperator . implode(
					$this->filterFormValuesDelimiter . $valueOperator, $values
				);
				if (!$multiFiltering) 
					break;
			}
			$valueField->SetValue(implode($this->filterFormValuesDelimiter, $fieldValue));
		}
		$filterField = (new \MvcCore\Ext\Forms\Fields\SubmitButton)
			->SetName(implode($form::HTML_IDS_DELIMITER, ['filter', $propName]))
			->SetValue($this->GetControlText('filter'))
			->AddCssClasses('filter');
		$clearField = (new \MvcCore\Ext\Forms\Fields\SubmitButton)
			->SetCustomResultState($clearBtnResultState)
			->SetName(implode($form::HTML_IDS_DELIMITER, ['clear', $propName]))
			->SetValue($this->GetControlText('clear'))
			->AddCssClasses('clear');
		return [$valueField, $filterField, $clearField];
	}


	/**
	 * Internal submit action for table head filter form.
	 * @template
	 * @return void
	 */
	public function ActionTableFilter () {
		if (!$this->actionTableFilterSetUp()) return;
		$headFilterFormState = $this->tableHeadFilterForm->GetDispatchState();
		if ($headFilterFormState < \MvcCore\IController::DISPATCH_STATE_PRE_DISPATCHED)
			$this->tableHeadFilterForm->PreDispatch(TRUE);
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
			$redirectUrl = parent::Url($this->appRouteName, [static::URL_PARAM_ACTION => NULL]);
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
		
		$filteringColumns = $this->getFilteringColumns();
		
		$formValues = [];
		foreach ($rawFormValues as $propName => $rawValues) {
			$rawValues = $rawValues ?: '';
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
		$likeOperatorsArrFilter = ['LIKE' => 1, 'NOT LIKE' => 1];
		$likeOperatorsAndPrefixes = array_intersect_key(static::$filterFormFieldValueOperatorPrefixes, $likeOperatorsArrFilter);
		$notLikeOperatorsAndPrefixes = array_diff_key(static::$filterFormFieldValueOperatorPrefixes, $likeOperatorsArrFilter);
		$viewExists = $this->view !== NULL;
		$this->view = $this->createView(TRUE);
		foreach ($formSubmitValues as $propName => $rawValues) {
			if (!isset($filteringColumns[$propName])) continue;
			$configColumn = $filteringColumns[$propName];
			$viewHelperName = $configColumn->GetViewHelper();
			list ($useViewHelper, $viewHelper) = $this->getFilteringViewHelper($viewHelperName);
			$rawValuesArr = explode($this->filterFormValuesDelimiter, $rawValues);
			$columnFilterCfg = $configColumn->GetFilter();
			$allowedOperators = is_integer($columnFilterCfg)
				? $this->columnsAllowedOperators[$configColumn->GetPropName()]
				: $this->defaultAllowedOperators;
			$columnAllowNullFilter = is_int($columnFilterCfg) && ($columnFilterCfg & self::FILTER_ALLOW_NULL) != 0;
			$columnTypes = $configColumn->GetTypes();
			$filterValues = [];
			foreach ($rawValuesArr as $rawValue) {
				// remove unknown characters
				$rawValue = $this->removeUnknownChars($rawValue);
				if ($rawValue === NULL) continue;
				$valueIsStringNull = strtolower($rawValue) === 'null';
				if ($useViewHelper && !$valueIsStringNull) {
					$rawValue = call_user_func_array(
						[$viewHelper, 'Unformat'],
						array_merge([$rawValue], $configColumn->GetFormat() ?: [])
					);
					if ($rawValue === NULL) continue;
				}
				$rawValueToCheckType = $rawValue;
				// complete possible operator prefixes from submitted value
				$containsPercentage = $this->checkFilterFormValueForSpecialLikeChar($rawValue, '%');
				$containsUnderScore = $this->checkFilterFormValueForSpecialLikeChar($rawValue, '_');
				if ($containsPercentage || $containsUnderScore) {
					if (($containsPercentage & 1) !== 0 || ($containsUnderScore & 1) !== 0) {
						$operatorsAndPrefixes = $likeOperatorsAndPrefixes;
						if ($containsPercentage) $rawValueToCheckType = str_replace('%', '', $rawValueToCheckType);
						if ($containsUnderScore) $rawValueToCheckType = str_replace('_', '', $rawValueToCheckType);
					} else {
						$operatorsAndPrefixes = $notLikeOperatorsAndPrefixes;
						if (($containsPercentage & 2) !== 0) $rawValue = str_replace('[%]', '%', $rawValue);
						if (($containsUnderScore & 2) !== 0) $rawValue = str_replace('[_]', '_', $rawValue);
					}
				} else {
					$operatorsAndPrefixes = $notLikeOperatorsAndPrefixes;
				}
				// complete operator value from submitted value
				foreach ($operatorsAndPrefixes as $operatorKey => $valuePrefix) {
					$valuePrefixLen = mb_strlen($valuePrefix);
					if ($valuePrefixLen > 0) {
						$valuePrefixChars = mb_substr($rawValue, 0, $valuePrefixLen);
						if ($valuePrefixChars === $valuePrefix) {
							$operator = $operatorKey;
							$rawValue = mb_substr($rawValue, $valuePrefixLen);
							$rawValueToCheckType = mb_substr($rawValueToCheckType, $valuePrefixLen);
							break;
						}
					} else {
						if (
							(is_bool($columnFilterCfg) && $columnFilterCfg) ||
							($columnFilterCfg & \MvcCore\Ext\Controllers\IDataGrid::FILTER_ALLOW_EQUALS) != 0
						) {
							$operator = $operatorKey;
						} else if (
							($columnFilterCfg & \MvcCore\Ext\Controllers\IDataGrid::FILTER_ALLOW_LIKE_ANYWHERE) != 0 ||
							($columnFilterCfg & \MvcCore\Ext\Controllers\IDataGrid::FILTER_ALLOW_LIKE_RIGHT_SIDE) != 0 ||
							($columnFilterCfg & \MvcCore\Ext\Controllers\IDataGrid::FILTER_ALLOW_LIKE_LEFT_SIDE) != 0
						) {
							$operator = 'LIKE';
						} else {
							x($columnFilterCfg);
							throw new \InvalidArgumentException(
								"Unknown filter configuration for column `{$propName}` to automatically submit column filtering."
							);
						}
						break;
					}
				}
				// check if operator is allowed
				$rawOperatorStr = $urlFilterOperators[$operator];
				if (!isset($allowedOperators[$rawOperatorStr])) continue;
				// check if operator configuration allowes submitted value form
				$operatorCfg = $allowedOperators[$rawOperatorStr];
				$multiple = $operatorCfg->multiple;
				$regex = $operatorCfg->regex;
				if ($regex !== NULL && !preg_match($regex, $rawValue)) continue;
				// check value by configured types
				if ($valueIsStringNull) {
					if ($columnAllowNullFilter) {
						$rawValue = 'null';
					} else {
						continue;
					}
				} else if (is_array($columnTypes) && count($columnTypes) > 0) {
					$typeValidationSuccess = FALSE;
					foreach ($columnTypes as $columnType) {
						$typeValidationSuccessLocal = $this->validateRawFilterValueByType(
							$rawValueToCheckType, $columnType
						);
						if ($typeValidationSuccessLocal) {
							$typeValidationSuccess = TRUE;
							break;
						}
					}
					if (!$typeValidationSuccess) continue;
				}
				// set up filtering value
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
		if (!$viewExists) $this->view = NULL;
		return $filtering;
	}

	/**
	 * Internal submit action for custom filtering form.
	 * @template
	 * @return void
	 */
	public function ActionFormFilter () {
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
			$redirectUrl = parent::Url($this->appRouteName, [static::URL_PARAM_ACTION => NULL]);
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
			$allowedOperators = is_integer($columnFilterCfg)
				? $this->columnsAllowedOperators[$configColumn->GetPropName()]
				: $this->defaultAllowedOperators;
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
		$filterParams = [];
		$urlFilterOperators = $this->configUrlSegments->GetUrlFilterOperators();
		$subjValueDelim = $this->configUrlSegments->GetUrlDelimiterSubjectValue();
		$valuesDelim = $this->configUrlSegments->GetUrlDelimiterValues();
		$subjsDelim = $this->configUrlSegments->GetUrlDelimiterSubjects();
		foreach ($newFiltering as $columnDbName => $filterOperatorsAndValues) {
			$columnConfig = $this->configColumns->GetByDbColumnName($columnDbName);
			$columnUrlName = $columnConfig->GetUrlName();
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
			static::URL_PARAM_PAGE		=> $page,
			static::URL_PARAM_COUNT		=> $count,
			static::URL_PARAM_FILTER	=> count($filterParams) > 0 
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
		/** @var \MvcCore\Controller $this */
		$this::Redirect($redirectUrl, \MvcCore\IResponse::SEE_OTHER, $redirectReason);
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
			if (
				(
					$this->ignoreDisabledColumns || 
					(!$this->ignoreDisabledColumns && !$configColumn->GetDisabled())
				) && (
					is_bool($columnFilterCfg) || 
					(is_int($columnFilterCfg) && $columnFilterCfg !== 0)
				)
			) {
				$configColumns[$configColumn->GetPropName()] = $configColumn;
			}
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
	 * character: `%` or `_` or escaped like this: `[%]` or `[_]`.
	 * Returns `0` if no special char `%` or `_` matched.
	 * Returns `1` if special char `%` or `_` matched in raw form only, not escaped.
	 * Returns `2` if special char `%` or `_` matched in escaped form only.
	 * Returns `1 | 2` if special char `%` or `_` matched in both forms.
	 * @param  string $rawValue 
	 * @param  string $specialLikeChar 
	 * @return int
	 */
	protected function checkFilterFormValueForSpecialLikeChar ($rawValue, $specialLikeChar) {
		$containsSpecialChar = 0;
		$index = 0;
		$length = mb_strlen($rawValue);
		$matchedEscapedChar = 0;
		while ($index < $length) {
			$specialCharPos = mb_strpos($rawValue, $specialLikeChar, $index);
			if ($specialCharPos === FALSE) break;
			$escapedSpecialCharPos = mb_strpos($rawValue, '['.$specialLikeChar.']', max(0, $index - 1));
			if ($escapedSpecialCharPos !== FALSE && $specialCharPos - 1 === $escapedSpecialCharPos) {
				$index = $specialCharPos + mb_strlen($specialLikeChar) + 1;
				$matchedEscapedChar = 2;
				continue;
			}
			$index = $specialCharPos + 1;
			$containsSpecialChar = 1;
			break;
		}
		return $containsSpecialChar | $matchedEscapedChar;
	}

	/**
	 * Get cached filtering view helper instance by name.
	 * @param  string $viewHelperName 
	 * @return array  [bool, \MvcCore\Ext\Controllers\DataGrids\Views\IReverseHelper]
	 */
	protected function getFilteringViewHelper ($viewHelperName) {
		if (array_key_exists($viewHelperName, $this->filteringViewHelpersCache)) {
			$viewHelper = $this->filteringViewHelpersCache[$viewHelperName];
			return [$viewHelper !== NULL, $viewHelper];
		}
		if ($viewHelperName === NULL) {
			$viewHelper = NULL;
		} else {
			if ($this->view === NULL)
				$this->view = $this->createView(TRUE);
			$viewHelper = $this->view->GetHelper($viewHelperName, FALSE) ;
		}
		$useViewHelper = $viewHelper instanceof \MvcCore\Ext\Controllers\DataGrids\Views\IReverseHelper;
		if ($useViewHelper) {
			$viewHelper->SetGrid($this);
		} else {
			$viewHelper = NULL;
		}
		$this->filteringViewHelpersCache[$viewHelperName] = $viewHelper;
		return [$useViewHelper, $viewHelper];
	}
}