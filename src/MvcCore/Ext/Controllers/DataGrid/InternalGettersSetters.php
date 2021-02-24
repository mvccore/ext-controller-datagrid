<?php

namespace MvcCore\Ext\Controllers\DataGrid;

trait InternalGettersSetters {

	/**
	 * @return int
	 */
	public function GetOffset () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		return $this->offset;
	}

	/**
	 * @return int
	 */
	public function GetLimit () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		return $this->limit;
	}

	/**
	 * 
	 * @return \MvcCore\Request
	 */
	public function GetGridRequest () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		if ($this->gridRequest === NULL) {
			$gridParam = $this->GetParam(static::PARAM_GRID, FALSE); // get param from application request object
			$gridParam = $gridParam !== NULL
				? '/' . ltrim($gridParam, '/')
				: '';
			$this->gridRequest = \MvcCore\Request::CreateInstance();
			$this->gridRequest->SetPath($gridParam);
		}
		return $this->gridRequest;
	}

	/**
	 * 
	 * @return string
	 */
	public function GridUrl (array $gridParams = []) {
		/** @var $this \MvcCore\Ext\Controllers\DataGrid */
		if ($this->queryStringParamsSepatator === NULL) {
			$routerType = new \ReflectionClass($this->router);
			$method = $routerType->getMethod('getQueryStringParamsSepatator');
			$method->setAccessible(TRUE);
			$this->queryStringParamsSepatator = $method->invoke($this->router);
		}
		$route = $this->GetRoute();
		$defaultParams = array_merge([], $this->GetUrlParams());
		$defaultPage = $defaultParams['page'];
		$pageDefaultChange = isset($gridParams['count']) && $gridParams['count'] != $defaultParams['count'];
		if ($pageDefaultChange) {
			$defaultParams['page'] = NULL;
			$route->SetDefaults($defaultParams);
		}
		foreach ($gridParams as $paramName => $paramValue) 
			if (isset($defaultParams[$paramName]))
				unset($defaultParams[$paramName]);
		list ($gridParam) = $route->Url(
			$this->GetGridRequest(),
			$gridParams,
			$defaultParams,
			$this->queryStringParamsSepatator,
			FALSE
		);
		if ($pageDefaultChange) {
			$defaultParams['page'] = $defaultPage;
			$route->SetDefaults($defaultParams);
		}
		return $this->Url('self', [static::PARAM_GRID => $gridParam]);
	}
}