<?php

class Grid_ChildsConstructor extends Website_Components_Grid
{

	protected $grid;

	protected $controlNameIterator = 0;
	protected $controlName = '';


	public function  __construct (Website_Components_Grid $grid)
	{
		/**
		 * set up all protected values from parent instance
		 * into child instance with the same value as parent instance
		 * to make one big grid instance (to use more grid instances in one web page)
		 */
		$grid->grid = $grid;
		$this->grid = $grid;
		foreach ($grid as $propertyKey => $propertyValue) {
			$this->$propertyKey = $propertyValue;
		}

		// complete $this->controlName for controls and forms
		$selfClassName = get_class($this);
		$selfClassNameArr = explode('_', $selfClassName);
		if ($selfClassNameArr[count($selfClassNameArr) - 2] == 'Controls') {
			$this->controlName = 'control' . $this->nameDelimiter . strtolower($selfClassNameArr[count($selfClassNameArr) - 1]);
		}
		if ($selfClassNameArr[count($selfClassNameArr) - 2] == 'Forms') {
			$this->controlName = 'form' . $this->nameDelimiter . strtolower($selfClassNameArr[count($selfClassNameArr) - 1]);
		}
	}

	protected function getRawContainerId ()
	{
		return $this->name
			. $this->nameDelimiter
			. $this->htmlContainerIdReplacement
		;
	}

	protected function getRawContainerElementId ($key = '')
	{
		return $this->name
			. $this->nameDelimiter
			. $this->htmlContainerIdReplacement
			. $this->nameDelimiter
			. $key
		;
	}

	protected function completeRawContainerIds ($renderResult = '')
	{
		$this->controlNameIterator += 1;
		$containerId = $this->controlName . $this->nameDelimiter . $this->controlNameIterator;
		return str_replace(
			$this->htmlContainerIdReplacement,
			$containerId,
			$renderResult
		);
	}

}

