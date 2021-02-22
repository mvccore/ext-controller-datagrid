<?php

class Grid_Controls_Order extends Grid_ChildsConstructor
{
	protected $renderedResult;

	public function render ()
	{
		if (!$this->grid->renderBooleans->orderControl) return;
		
		if (gettype($this->renderResult) != 'string') {

			$index = 1;
			$orderIndexes = $this->grid->modelOrder;
			foreach ($orderIndexes as $orderKey => $orderValueForNothing) {
				$orderIndexes[$orderKey] = $index;
				$index += 1;
			}

			$params = new stdClass;

			$params->rawControlId = $this->getRawContainerId();
			$params->grid = $this->grid;
			$params->order = $this->order;
			$params->modelOrder = $this->grid->modelOrder;
			$params->orderOptions = $this->grid->orderOptions;
			$params->orderIndexes = $orderIndexes;

			$this->renderResult = $this->renderView(
				$this->templates->orderControl,
				$params
			);
		}
		
		return $this->completeRawContainerIds($this->renderResult);
	}

}

