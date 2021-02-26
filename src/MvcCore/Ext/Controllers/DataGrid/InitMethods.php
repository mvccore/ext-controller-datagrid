<?php

namespace MvcCore\Ext\Controllers\DataGrid;

trait InitMethods {

	/**
	 * Create \MvcCore\Ext\Controllers\DataGrid instance.
	 * @param  \MvcCore\Controller|NULL $controller
	 * @param  string|int|NULL          $childControllerIndex Automatic name for this instance used in view.
	 * @return void
	 */
	public function __construct (\MvcCore\IController $controller = NULL, $childControllerIndex = NULL) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		/** @var $controller \MvcCore\Controller */
		if ($controller === NULL) {
			$controller = \MvcCore\Ext\Form::GetCallerControllerInstance();
			if ($controller === NULL) 
				$controller = \MvcCore\Application::GetInstance()->GetController();
			if ($controller === NULL) throw new \InvalidArgumentException(
				'['.get_class($this).'] There was not possible to determinate caller controller, '
				.'where is datagrid instance created. Provide `$controller` instance explicitly '
				.'by first `\MvcCore\Ext\Controllers\DataGrid::__construct($controller);` argument.'
			);
		}
		$controller->AddChildController($this, $childControllerIndex);
	}

	/**
	 * @inheritDocs
	 * @return void
	 */
	public function Init () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		if ($this->dispatchState > \MvcCore\IController::DISPATCH_STATE_CREATED) return;
		parent::Init();
		$this->GetConfigUrlSegments();
		$this->GetRoute();
		$this->GetUrlParams();
		$this->initSetUpUrlParams();
		$this->translate = is_callable($this->translator) || $this->translator instanceof \Closure;
		if (!$this->translate)
			$this->translateUrlNames = FALSE;
	}

	/**
	 * @return void
	 */
	protected function initSetUpUrlParams () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		/** @var $context \MvcCore\Controller */
		$context = $this;

		// set up default page if null:
		if (isset($this->urlParams['page'])) {
			if ($this->urlParams['page'] === 0) {
				// redirect to proper page number:
				$redirectUrl = $this->GridUrl([
					'page'	=> 1,
				]);
				return $context::Redirect(
					$redirectUrl, 
					\MvcCore\IResponse::SEE_OTHER, 
					'Grid page is too low.'
				);
			}
		} else {
			$this->urlParams['page'] = 1;
		}

		// set up default count if null or check if count has allowed size:
		if (!isset($this->urlParams['count'])) {
			$this->urlParams['count'] = $this->GetItemsPerPage();
		} else {
			$urlCount = $this->urlParams['count'];
			$lastCountsScale = $this->countScales[count($this->countScales) - 1];
			if ($lastCountsScale !== 0 && ($urlCount === 0 || $urlCount > $lastCountsScale)) {
				// redirect to allowed max count:
				$redirectUrl = $this->GridUrl([
					'page'	=> $this->urlParams['page'],
					'count'	=> $lastCountsScale,
				]);
				return $context::Redirect(
					$redirectUrl, 
					\MvcCore\IResponse::SEE_OTHER, 
					'Grid count is too high.'
				);
			}
		}

		// check if count scale from url is allowed and change count scale if necessary:
		$urlItemsPerPage = $this->urlParams['count'];
		if (
			$this->allowedCustomUrlCountScale ||
			in_array($urlItemsPerPage, $this->countScales, TRUE)
		) {
			$this->itemsPerPage = $urlItemsPerPage;
		} else {
			$differences = [];
			$lastCountScale = 0;
			foreach ($this->countScales as $index => $countScale) {
				if ($countScale === 0) {
					$differences[$urlItemsPerPage > $lastCountScale ? 0 : $urlItemsPerPage] = $index;
				} else {
					$differences[abs($countScale - $urlItemsPerPage)] = $index;
				}
				$lastCountScale = $countScale;
			}
			$minDifference = min(array_keys($differences));
			$minDifferenceCountScaleKey = $differences[$minDifference];
			$minDifferenceCountScale = $this->countScales[$minDifferenceCountScaleKey];
			$redirectUrl = $this->GridUrl([
				'count'	=> $minDifferenceCountScale,
			]);
			return $context::Redirect(
				$redirectUrl, 
				\MvcCore\IResponse::SEE_OTHER, 
				'Grid custom count scale is not allowed.'
			);
		}
		
		// check if page is not larger than 1 if count is unlimited:
		$this->page = $this->urlParams['page'];
		if ($this->itemsPerPage === 0 && $this->page > 1) {
			$redirectUrl = $this->GridUrl([
				'page'	=> 1,
			]);
			return $context::Redirect(
				$redirectUrl, 
				\MvcCore\IResponse::SEE_OTHER, 
				'Grid page is too high with unlimited count.'
			);
		}
	}

}
