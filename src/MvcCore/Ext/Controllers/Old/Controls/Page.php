<?php

class Grid_Controls_Page extends Grid_ChildsConstructor
{
	const TOTAL_PAGES_COUNT = 9; // 1  +1+  2+1+2  +1+  1
	const ADJACENT_PAGES_COUNT_ON_ONE_SIDE = 2;

	protected $renderedResult;

	public function render ()
	{
		if (!$this->grid->renderBooleans->pageControl) return;

		if (gettype($this->renderResult) != 'string') {

			$params = new stdClass;

			$itemsCount = $this->resultsListCount;
			$pagesCount = ceil($itemsCount / $this->countPerPage);

			if ($pagesCount <= self::TOTAL_PAGES_COUNT) {
				$steps = range(1, $pagesCount);
			} else {
				$steps = $this->getSteps($pagesCount);
			}
			// convert all floats from math rounding and ranges back to integers
			foreach ($steps as $stepKey => $stepValue) {
				$steps[$stepKey] = (int) $stepValue;
			}

			$params->rawControlId = $this->getRawContainerId();
			$params->steps = $steps;
			$params->pagesCount = $pagesCount;
			$params->page = $this->page;
			$params->countPerPage = $this->countPerPage;
			$params->grid = $this->grid;

			$this->renderResult = $this->renderView(
				$this->templates->pageControl,
				$params
			);
		}

		return $this->completeRawContainerIds($this->renderResult);
	}

	protected function getSteps ($pagesCount)
	{
		// prepare steps base
		$steps = array(
			1,				// first page
			$pagesCount,	// last page
		);

		// complete adjacent range (and by this proces, make up counts for total ranges)
		$adjacentFirstPage = $this->page - self::ADJACENT_PAGES_COUNT_ON_ONE_SIDE;
		$adjacentLastPage = $this->page + self::ADJACENT_PAGES_COUNT_ON_ONE_SIDE;
		$allAdjacentPagesCount = self::ADJACENT_PAGES_COUNT_ON_ONE_SIDE * 2;

		// set counts about total left and total right range parts to default values
		$totalPagesRangeCount = $pagesCount - 2 - $allAdjacentPagesCount - 1;
		// -1 at the end means "minus first page", first page is allready in steps
		$totalPagesRangeLeftPartCount = ceil((self::TOTAL_PAGES_COUNT - $allAdjacentPagesCount - 1) / 2) - 1;
		// -1 at the end means "minus last page", last page is allready in steps
		$totalPagesRangeRightPartCount = ceil((self::TOTAL_PAGES_COUNT - $allAdjacentPagesCount - 1) / 2) - 1;

		if ($adjacentFirstPage < 1) {
			// current page is too much at the beginning
			$adjacentFirstPage = 1;
			$adjacentLastPage = $adjacentFirstPage + $allAdjacentPagesCount;
			// fix total range counts
			$totalPagesRangeRightPartCount += $totalPagesRangeLeftPartCount + 1;
			$totalPagesRangeLeftPartCount = 0;
			$totalPagesRangeCount += 1;
		} else if ($adjacentLastPage > $pagesCount) {
			// current page is too much at the end
			$adjacentLastPage = $pagesCount;
			$adjacentFirstPage = $adjacentLastPage - $allAdjacentPagesCount;
			// fix total range counts
			$totalPagesRangeLeftPartCount += $totalPagesRangeRightPartCount + 1;
			$totalPagesRangeRightPartCount = 0;
			$totalPagesRangeCount += 1;
		}
		$adjacentPagesRange = range($adjacentFirstPage, $adjacentLastPage);

		// complete total ranges
		$totalPagesRange = array();
		// left range
		if ($totalPagesRangeLeftPartCount) {
			$totalPagesRangeLeftSideCount = floor($totalPagesRangeCount / 2);
			$totalRangeLeftStep = ($totalPagesRangeLeftSideCount / ($totalPagesRangeLeftPartCount + 1));
			for ($i = 1; $i <= $totalPagesRangeLeftPartCount; $i += 1) {
				$totalPagesRange[] = round(
					1 + ($totalRangeLeftStep * $i)
				);
			}
		}
		// right range
		if ($totalPagesRangeRightPartCount) {
			$totalPagesRangeRightSideCount = ceil($totalPagesRangeCount / 2);
			$totalRangeRightStep = ($totalPagesRangeRightSideCount / ($totalPagesRangeRightPartCount + 1));
			for ($i = 1; $i <= $totalPagesRangeRightPartCount; $i += 1) {
				$totalPagesRange[] = round(
					$pagesCount - 1 - ($totalRangeRightStep * $i)
				);
			}
		}

		// complete ranges to steps array
		$steps = array_merge($steps, $adjacentPagesRange, $totalPagesRange);
		$steps = array_values(array_unique($steps));
		sort($steps);

		return $steps;
	}

}

