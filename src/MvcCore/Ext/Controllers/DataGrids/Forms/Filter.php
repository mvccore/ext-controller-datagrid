<?php

class Grid_Forms_Filter extends Grid_ChildsConstructor
{
	protected $renderedResult;

	protected $form;
	protected $formElements = array();

	public function submit ()
	{
		$rawRequestParams = $this->request->getParams();
		unset(
			$rawRequestParams['controller'],
			$rawRequestParams['action'],
			$rawRequestParams['module'],
			$rawRequestParams['document'],
			$rawRequestParams[$this->routeParam]
		);

		foreach ($this->filterOptions as $filterKey => $filterOptions) {
			if (!isset($filterOptions['name']) || !isset($filterOptions['renderInFormAs'])) continue;
			$renderInFormAs = $filterOptions['renderInFormAs'];
			$submitCompletionMethodName = 'completeFilterWithSubmittedValuesFor' . ucfirst($renderInFormAs);
			$this->$submitCompletionMethodName($filterKey, $filterOptions, $rawRequestParams);
		}

		// add default values for url building
		foreach ($this->filterDefault as $filterKey => $filterValuesDefault) {
			if (!isset($this->grid->filter[$filterKey])) {
				$this->grid->filter[$filterKey] = $filterValuesDefault;
			}
		}

		// important for url builder comparing
		ksort($this->grid->filter);

		$this->grid->initUrlBuilder();

		$redirectUrl = $this->grid->getUrl(
			array(),
			'filter'
		);

		$this->_redirect($redirectUrl);
	}

	public function render ()
	{
		if (!$this->grid->renderBooleans->filterForm) return;

		if (gettype($this->renderResult) != 'string') {

			$params = new stdClass;

			$this->setupFilterForm();

			$params->rawControlId = $this->getRawContainerId();
			$params->form = $this->form;
			$params->formElements = $this->formElements;
			$params->filterOptions = $this->filterOptions;
			$params->grid = $this->grid;

			$this->renderResult = $this->renderView(
				$this->templates->filterForm,
				$params
			);

		}

		return $this->completeRawContainerIds($this->renderResult);
	}

	protected function setupFilterForm ()
	{
		$this->form = new Zend_Form;

		foreach ($this->filterOptions as $filterKey => $filterOptions) {
			if (!isset($filterOptions['name']) || !isset($filterOptions['renderInFormAs'])) continue;

			$this->formElements[$filterKey] = new stdClass;
			$this->formElements[$filterKey]->mainLabelText = $filterOptions['name'];
			$this->formElements[$filterKey]->controls = array();

			$renderInFormAs = $filterOptions['renderInFormAs'];
			$elementsSetupMethodName = 'setupFilterForm' . ucfirst($renderInFormAs);

			$this->$elementsSetupMethodName($filterKey);
		}
	}
	
	/* form elements creators **************************************************/

	protected function setupFilterFormCheckboxes ($filterKey = '')
	{
		$options = $this->filterOptions[$filterKey]['choosingValues'];
		
		foreach ($options as $optionIndex => $option) {

			$filterSubKey = $filterKey . $option['value'];

			$this->form->addElement('checkbox', $filterSubKey, array(
				'class' => 'checkbox',
				'id'	=> $this->getRawContainerElementId($filterSubKey),
			));
			$checkboxElement = $this->form->getElement($filterSubKey);
			$checkboxElement
				->removeDecorator('label')
				->removeDecorator('DtDdWrapper')
				->removeDecorator('HtmlTag')
			;

			if (in_array($option['value'], $this->modelFilter[$filterKey])) {
				$checkboxElement->setChecked(TRUE);
			}
			
			$this->formElements[$filterKey]->controls[] = (object) array(
				'labelText'		=> $option['key'],
				'controlName'	=> $filterSubKey,
			);

		}
	}

	protected function setupFilterFormCheckbox ($filterKey = '')
	{
		$this->form->addElement('checkbox', $filterKey, array(
			'class' => 'checkbox',
			'id'	=> $this->getRawContainerElementId($filterKey),
		));
		$checkboxElement = $this->form->getElement($filterKey);
		$checkboxElement
			->removeDecorator('label')
			->removeDecorator('DtDdWrapper')
			->removeDecorator('HtmlTag')
		;

		$checkboxElement->setChecked($this->modelFilter[$filterKey][0]);

		$this->formElements[$filterKey]->controls[] = (object) array(
			'labelText'		=> $option['key'],
			'controlName'	=> $filterKey,
		);
	}

	protected function setupFilterFormSelect ($filterKey = '')
	{
		$options = $this->filterOptions[$filterKey]['choosingValues'];
		
		foreach ($options as $optionIndex => $option) {
			if (isset($option['value']) && $option['key']) {
				$selectOptions[$option['value']] = $option['key'];
			}
		}

		if (!$selectOptions) {
			$selectOptions = $options;
		}

		$selectOptions = array_merge(array('' => '--- choose option ---'), $selectOptions);

		$this->form->addElement('select', $filterKey, array(
			'multiOptions'		=> $selectOptions,
			'disableTranslator'	=> false,
			'decorators'		=> array(
				'ViewHelper',
				'Errors',
			),
			'class'		=> 'select',
			'id'		=> $this->getRawContainerElementId($filterKey),
		));

		if (isset($this->modelFilter[$filterKey]) && count($this->modelFilter[$filterKey]) == 1) {
			$this->form->getElement($filterKey)->setValue($this->modelFilter[$filterKey][0]);
		}

		$this->formElements[$filterKey]->controls[] = (object) array(
			'controlName'	=> $filterKey,
		);
	}

	protected function setupFilterFormMultiselect ($filterKey = '')
	{
		$options = $this->filterOptions[$filterKey]['choosingValues'];

		foreach ($options as $optionIndex => $option) {
			if (isset($option['value']) && $option['key']) {
				$selectOptions[$option['value']] = $option['key'];
			}
		}

		if (!$selectOptions) {
			$selectOptions = $options;
		}

		$this->form->addElement('multiselect', $filterKey, array(
			'multiOptions'		=> $selectOptions,
			'disableTranslator'	=> false,
			'decorators'		=> array(
				'ViewHelper',
				'Errors',
			),
			'class'		=> 'multiselect',
			'id'		=> $this->getRawContainerElementId($filterKey),
		));
		
		if (isset($this->modelFilter[$filterKey]) && count($this->modelFilter[$filterKey]) > 0) {
			$this->form->getElement($filterKey)->setValue($this->modelFilter[$filterKey]);
		}

		$this->formElements[$filterKey]->controls[] = (object) array(
			'controlName'	=> $filterKey,
		);
	}

	protected function setupFilterFormTextInput ($filterKey = '')
	{
		$this->form->addElement('text', $filterKey, array(
			'filters'	=> array('StringTrim', 'StripTags'),
			'decorators'=> array(
				'ViewHelper',
				'Errors',
			),
			'maxlength' => 255,
			'class'		=>'text',
			'id'		=> $this->getRawContainerElementId($filterKey),
		));

		if (isset($this->modelFilter[$filterKey]) && count($this->modelFilter[$filterKey]) == 1) {
			// translate the value back to human form if necessary
			$valueTranslator = $this->filterOptions[$filterKey]['valueTranslator'];
			$translatedValue = $valueTranslator($this->modelFilter[$filterKey][0], TRUE);
			$this->form->getElement($filterKey)->setValue($translatedValue);
		}

		$this->formElements[$filterKey]->controls[] = (object) array(
			'controlName'	=> $filterKey,
		);
	}

	protected function setupFilterFormTextInputsInterval ($filterKey = '')
	{
		// translate the value back to human form if necessary
		$filterValues = array('', '');
		if (isset($this->modelFilter[$filterKey]) && count($this->modelFilter[$filterKey]) > 0) {
			$filterValues = array_merge($this->modelFilter[$filterKey], $filterValues);
			$valueTranslator = $this->filterOptions[$filterKey]['valueTranslator'];
			foreach ($filterValues as $iterator => $filterValue) {
				if (isset($this->filterDefault[$filterKey][$iterator]) && $filterValue == $this->filterDefault[$filterKey][$iterator]) {
					$filterValues[$iterator] = '';
				} else {
					$filterValues[$iterator] = $valueTranslator($filterValues[$iterator], TRUE);
				}
			}
		}
		
		$intevalLabelsTexts = array('from', 'to');
		for ($i = 0; $i < 2; $i += 1) {

			$filterSubKey = $filterKey . $i;

			$this->form->addElement('text', $filterSubKey, array(
				'filters'	=> array('StringTrim', 'StripTags'),
				'decorators'=> array(
					'ViewHelper',
					'Errors',
				),
				'maxlength'	=> 255,
				'class'		=> 'text',
				'id'		=> $this->getRawContainerElementId($filterSubKey),
				'value'		=> $filterValues[$i],
			));

			$this->formElements[$filterKey]->controls[] = (object) array(
				'labelText'		=> $intevalLabelsTexts[$i],
				'controlName'	=> $filterSubKey,
			);
		}
	}

	/* submit $this->filter element values completion methods ******************/

	protected function completeFilterWithSubmittedValuesForCheckboxes ($filterKey, $filterOptions, $rawRequestParams)
	{
		$options = $this->filterOptions[$filterKey]['choosingValues'];

		$newValues = array();
		foreach ($options as $optionIndex => $option) {
			$filterSubKey = $filterKey . $option['value'];

			if (isset($rawRequestParams[$filterSubKey]) && (boolean) $rawRequestParams[$filterSubKey]) {
				$newValues[] = $option['value'];
			}
			
		}

		if ($newValues) {
			$this->grid->filter[$filterKey] = $newValues;
		} else {
			if (isset($this->grid->filterDefault[$filterKey])) {
				$this->grid->filter[$filterKey] = $this->grid->filterDefault[$filterKey];
			} else {
				unset($this->grid->filter[$filterKey]);
			}
		}
	}

	protected function completeFilterWithSubmittedValuesForCheckbox ($filterKey, $filterOptions, $rawRequestParams)
	{
		if (isset($rawRequestParams[$filterKey])) {
			$this->grid->filter[$filterKey] = array($rawRequestParams[$filterKey]);
		} else {
			if (isset($this->grid->filterDefault[$filterKey])) {
				$this->grid->filter[$filterKey] = $this->grid->filterDefault[$filterKey];
			} else {
				unset($this->grid->filter[$filterKey]);
			}
		}
	}

	protected function completeFilterWithSubmittedValuesForSelect ($filterKey, $filterOptions, $rawRequestParams)
	{
		if (isset($rawRequestParams[$filterKey]) && strlen($rawRequestParams[$filterKey]) > 0) {
			$valueTranslator = $filterOptions['valueTranslator'];
			$filterValue = $valueTranslator($rawRequestParams[$filterKey], FALSE);
			$this->grid->filter[$filterKey] = array($filterValue);
		} else {
			if (isset($this->grid->filterDefault[$filterKey])) {
				$this->grid->filter[$filterKey] = $this->grid->filterDefault[$filterKey];
			} else {
				unset($this->grid->filter[$filterKey]);
			}
		}
	}

	protected function completeFilterWithSubmittedValuesForMultiselect ($filterKey, $filterOptions, $rawRequestParams)
	{
		if (isset($rawRequestParams[$filterKey]) && count($rawRequestParams[$filterKey]) > 0) {

			$valueTranslator = $filterOptions['valueTranslator'];
			$filterValues = $rawRequestParams[$filterKey];

			foreach ($filterValues as $iterator => $filterValue) {
				$filterValues[$iterator] = $valueTranslator($filterValue, FALSE);
			}

			$this->grid->filter[$filterKey] = $filterValues;
		} else {
			if (isset($this->grid->filterDefault[$filterKey])) {
				$this->grid->filter[$filterKey] = $this->grid->filterDefault[$filterKey];
			} else {
				unset($this->grid->filter[$filterKey]);
			}
		}
	}

	protected function completeFilterWithSubmittedValuesForTextInput ($filterKey, $filterOptions, $rawRequestParams)
	{
		if (isset($rawRequestParams[$filterKey]) && strlen($rawRequestParams[$filterKey]) > 0) {
			$valueTranslator = $filterOptions['valueTranslator'];
			$filterValue = $valueTranslator($rawRequestParams[$filterKey], FALSE);
			$this->grid->filter[$filterKey] = array($filterValue);
		} else {
			if (isset($this->grid->filterDefault[$filterKey])) {
				$this->grid->filter[$filterKey] = $this->grid->filterDefault[$filterKey];
			} else {
				unset($this->grid->filter[$filterKey]);
			}
		}
	}

	protected function completeFilterWithSubmittedValuesForTextInputsInterval ($filterKey, $filterOptions, $rawRequestParams)
	{
		$valueTranslator = $filterOptions['valueTranslator'];
		for ($i = 0; $i < 2; $i += 1) {

			$filterSubKey = $filterKey . $i;
			if (isset($rawRequestParams[$filterSubKey]) && strlen($rawRequestParams[$filterSubKey]) > 0) {
				$valueTranslator = $filterOptions['valueTranslator'];
				$filterValue = $valueTranslator($rawRequestParams[$filterSubKey], FALSE);
				$this->grid->filter[$filterKey][$i] = $filterValue;
			} else {
				if (isset($this->grid->filterDefault[$filterKey][$i])) {
					$this->grid->filter[$filterKey][$i] = $this->grid->filterDefault[$filterKey][$i];
				} else {
					unset($this->grid->filter[$filterKey][$i]);
				}
			}

		}
	}
}

