<?php

class Grid_Forms_Count extends Grid_ChildsConstructor
{
	protected $renderedResult;

	protected $form;
	protected $elementName = 'count';

	public function submit ()
	{
		$rawValue = $this->request->getParam($this->elementName);

		$value = (int) preg_replace("#[^0-9]#", "", $rawValue);

		if (
			($value > $this->countPerPageMax && $this->countPerPageMax > 0) ||
			($value === 0 && $this->countPerPageMax > 0)
		) {
			$value = $this->countPerPageMax;
		}
		$this->grid->countPerPage = $value;

		$this->grid->initUrlBuilder();

		$redirectUrl = $this->grid->getUrl(
			$this->grid->countPerPage,
			'count'
		);

		$this->_redirect($redirectUrl);
	}

	public function render ()
	{
		if (!$this->grid->renderBooleans->countForm) return;

		if (gettype($this->renderResult) != 'string') {

			$params = new stdClass;

			$this->setupFormElements();

			$params->rawControlId = $this->getRawContainerId();
			$params->form = $this->form;
			$params->elementName = $this->elementName;
			$params->grid = $this->grid;

			$this->renderResult = $this->renderView(
				$this->templates->countForm,
				$params
			);

		}

		return $this->completeRawContainerIds($this->renderResult);
	}

	protected function setupFormElements ()
	{
		$this->form = new Zend_Form;

		
		// render as input
		$this->form->addElement('text', $this->elementName, array(
			'filters'	=> array('StringTrim', 'StripTags'),
			'decorators'=> array(
				'ViewHelper',
				'Errors',
			),
			'maxlength' => 11,
			'class'		=>'text',
			'id'		=> $this->getRawContainerElementId($this->elementName),
			'value'		=> $this->countPerPage,
		));
		
		/*
		// render as select
		$countScale = array();
		foreach ($this->countScale as $key => $value) {
			$countScale[(string) $value] = $value;
		}
		if ($this->countPerPageMax === 0) {
			$countScale['0'] = 'all';
		}
		$this->form->addElement('select', $this->elementName, array(
			'multiOptions'		=> $countScale,
			'disableTranslator'	=> false,
			'decorators'		=> array(
				'ViewHelper',
				'Errors',
			),
			'class'		=> 'select',
			'id'		=> $this->getRawContainerElementId($this->elementName),
			'value'		=> $this->countPerPage,
		));
		*/
	}

}

