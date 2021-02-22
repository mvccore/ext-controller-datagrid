<?php

class Grid_Router extends Grid_ChildsConstructor
{
	public function getRoute ()
	{
		$routeParam = $this->request->getParam($this->routeParam);

		foreach ($this->routes as $routeKey => $routeOptions) {
			preg_match($routeOptions['pattern'], $routeParam, $matches);
			if ($matches) {
				$this->grid->privateRouteName = $routeKey;
				break;
			}
		}
		
	}

	public function processRouteTask ()
	{
		$childInstanceName = $this->routes[$this->grid->privateRouteName]['class'];
		$childInstanceMethod = $this->routes[$this->grid->privateRouteName]['method'];

		if ($childInstanceName == '') {
			$childInstance = $this->grid;
		} else {
			$childInstanceName = $this->getChildInstanceName($childInstanceName);
			$childInstance = new $childInstanceName($this->grid);
		};

		$childInstance->$childInstanceMethod();
	}

}

