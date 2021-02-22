<?php

class Grid_Controls_Count extends Grid_ChildsConstructor
{
	protected $renderedResult;

	public function render ()
	{
		if (!$this->grid->renderBooleans->countControl) return;

		if (gettype($this->renderResult) != 'string') {

			$params = new stdClass;

			if ($this->countPerPageMax === 0) {
				$this->countScale[] = 0;
			}

			$params->rawControlId = $this->getRawContainerId();
			$params->countScale = array_unique($this->countScale);
			$params->countPerPage = $this->countPerPage;
			$params->grid = $this->grid;

			$this->renderResult = $this->renderView(
				$this->templates->countControl,
				$params
			);
		}

		return $this->completeRawContainerIds($this->renderResult);
	}

}

