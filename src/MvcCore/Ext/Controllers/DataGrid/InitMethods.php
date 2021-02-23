<?php

namespace MvcCore\Ext\Controllers\DataGrid;

trait InitMethods {

	/**
	 * Create \MvcCore\Ext\Controllers\DataGrid instance.
	 * @param  \MvcCore\Controller|NULL $controller
	 * @return void
	 */
	public function __construct (\MvcCore\IController $controller = NULL) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		/** @var $controller \MvcCore\Controller */
		if ($controller === NULL) {
			$controller = \MvcCore\Ext\Form::GetCallerControllerInstance();
			if ($controller === NULL) 
				$controller = \MvcCore\Application::GetInstance()->GetController();
			if ($controller === NULL) $this->throwNewInvalidArgumentException(
				'There was not possible to determinate caller controller, '
				.'where is datagrid instance created. Provide `$controller` instance explicitly '
				.'by first `\MvcCore\Ext\Controllers\DataGrid::__construct($controller);` argument.'
			);
		}
		$controller->AddChildController($this);
	}

	/**
	 * Throw new `\InvalidArgumentException` with given
	 * error message and append automatically current class name,
	 * current form id and form class type.
	 * @param  string $errorMsg 
	 * @throws \InvalidArgumentException 
	 */
	protected function throwNewInvalidArgumentException ($errorMsg) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		$str = '['.get_class().'] ' . $errorMsg . ' ('
			. 'form id: `'.$this->id . '`, '
			. 'form type: `'.get_class($this->form).'`'
		.')';
		throw new \InvalidArgumentException($str);
	}

	/**
	 * @inheritDocs
	 * @return void
	 */
	public function Init () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		if ($this->dispatchState > \MvcCore\IController::DISPATCH_STATE_CREATED) return;
		parent::Init();
		$this->GetUrlConfig();
		$this->GetRoute();
		$this->GetUrlParams();
		$this->initCheckUrlParams();
	}

	/**
	 * @return void
	 */
	protected function initCheckUrlParams () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		// set up default page if null:
		if (isset($this->urlParams['page'])) {
			if ($this->urlParams['page'] === 0) {
				// redirect to proper page number:
				$redirectUrl = $this->GridUrl([
					'page'	=> 1,
				]);
				return static::Redirect(
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
			$lastCountsScale = $this->countsScale[count($this->countsScale) - 1];
			if ($lastCountsScale !== 0 && ($urlCount === 0 || $urlCount > $lastCountsScale)) {
				// redirect to allowed max count:
				$redirectUrl = $this->GridUrl([
					'page'	=> $this->urlParams['page'],
					'count'	=> $lastCountsScale,
				]);
				return static::Redirect(
					$redirectUrl, 
					\MvcCore\IResponse::SEE_OTHER, 
					'Grid count is too high.'
				);
			}
		}
		// check if page is not larger than 1 if count is unlimited:
		$page = $this->urlParams['page'];
		$count = $this->urlParams['count'];
		if ($count === 0 && $page > 1) {
			$redirectUrl = $this->GridUrl([
				'page'	=> 1,
			]);
			return static::Redirect(
				$redirectUrl, 
				\MvcCore\IResponse::SEE_OTHER, 
				'Grid page is too high with unlimited count.'
			);
		}
	}

}
