<?php

include_once(__DIR__ . '/Grid/Base.php');

/**
 * If you want to change class name of this component - change it here at line 9
 * and in Grid/ChildsConstructor.php at lines 3 and 12
 */

class Website_Components_Grid extends Grid_Base
{
	protected $page = 0;
	protected $countPerPage = 0;
	protected $order = array();
	protected $filter = array();

	protected $orderOptions = array();
	protected $filterOptions = array();

	protected $modelOrder = array();
	protected $modelFilter = array();

	/* setters - listed in using order *****************************************/

	public function setUrlAliasKeys ($urlAliasKeys = array())
	{
		$this->urlAliasKeys = $urlAliasKeys;
		return $this;
	}

	public function setFilterOptions ($filterOptions = array())
	{
		// complete filter options for default values
		foreach ($filterOptions as $filterKey => $filterOptions) {

			if (isset($filterOptions['name'])) {
				$this->renderBooleans->filterForm = TRUE;
			}

			// if there is initialized "values" record - set up default filter values
			if (isset($filterOptions['values'])) {
				$this->filterDefault[$filterKey] = $filterOptions['values'];
				unset($filterOptions['values']);
			}

			// set default numeric allowed url characters regexp patern
			if (!isset($filterOptions['valueTranslator'])) {
				$filterOptions['valueTranslator'] = $this->defaultValueTranslator;
			}

			// check for logic operators and complete it with default values if necessary
			$filterOptions['logicOperators'] = $this->completeFilterLogicOperators($filterOptions);

			$this->filterOptions[$filterKey] = $filterOptions;
		}

		// very important to url building and sections string comparing
		ksort($this->filterDefault);

		return $this;
	}

	public function setOrderOptions ($orderOptions = array())
	{
		$this->orderOptions = $orderOptions;
		// complete order default array (to save computing on url building)
		foreach ($orderOptions as $key => $options) {
			if (isset($options['value'])) {
				$this->orderDefault[$key] = $options['value'];
			}
			if (isset($options['name'])) {
				$this->renderBooleans->orderControl = TRUE;
			}
		}
		return $this;
	}

	public function setPageDefault ($defaultPage = 1)
	{
		if (gettype($defaultPage) == 'integer') {
			$this->pageDefault = $defaultPage;
		}
		return $this;
	}

	public function setCountPerPageDefault ($defaultCountPerPage = 10)
	{
		if (gettype($defaultCountPerPage) == 'integer') {
			$this->countPerPageDefault = $defaultCountPerPage;
		}
		return $this;
	}

	public function setResultsList ($anyToCountAndForeach)
	{
		$this->resultsList = $anyToCountAndForeach;

		$this->resultsListCount = count($this->resultsList);
		
		if ($this->resultsListCount > $this->countPerPage) {
			if ($this->countPerPage > 0) {
				// do not render page controls for full list
				$this->renderBooleans->pageControl = TRUE;
				$this->renderBooleans->pageForm = TRUE;
			}
			$this->renderBooleans->countControl = TRUE;
			$this->renderBooleans->countForm = TRUE;
		}

		if ($this->resultsListCount < 2) {
			// do not render order control if items count is one or zero
			$this->renderBooleans->orderControl = FALSE;
			$this->renderBooleans->orderForm = FALSE;
		}

		// check if pages count is bigger or the same as page
		$pagesCount = ($this->countPerPage > 0) ? (int) ceil($this->resultsListCount / $this->countPerPage) : 0 ;
		// some user has written to hight page number to url, redirect to last page
		if ($this->page > $pagesCount && $pagesCount > 0) {
			$this->_redirect(
				$this->getUrl($pagesCount, 'page'),
				array(
				    'code'  => 301,
				    'exit'  => TRUE,
				)
			);
		}

		return $this;
	}

	/* all actions throw grid router ******************************************/

	public function routeRequest ($routeOptions = array(/*request,baseUrl,routeName,routeParam*/))
	{
		foreach ($routeOptions as $routeOptionKey => $routeOptionValue) {
			$this->$routeOptionKey = $routeOptionValue;
		}

		// get route
		$routerClassName = $this->getChildInstanceName('Router');
		$this->childs->router = new $routerClassName($this);
		$this->childs->router->getRoute();

		// parse requested grid param to local storrages and setup uri builder
		$urlParserClassName = $this->getChildInstanceName('UrlParser');
		$this->childs->urlParser = new $urlParserClassName($this);

		list(
			$this->page,
			$this->countPerPage,
			$this->order,
			$this->filter
		) = $this->childs->urlParser->parseRequestUrlToMainStorages();

		// process route tasks
		$this->childs->router->processRouteTask();
	}

	/* getters ****************************************************************/

	public function getListModelConfig ()
	{
		return $this->modelConfig;
	}

	public function getUrl ()
	{
		return $this->childs->urlBuilder->getUrlLocal(func_get_args());
	}

	/* renderers **************************************************************/

	public function renderPageControl ()
	{
		if ((isset($this->childs->pageControl) && !$this->childs->pageControl) || !isset($this->childs->pageControl)) {
			$pageControlClassName = $this->getChildInstanceName('Controls_Page');
			$this->childs->pageControl = new $pageControlClassName($this);
		}
		return $this->childs->pageControl->render();
	}

	public function renderCountControl ()
	{
		if ((isset($this->childs->countControl) && !$this->childs->countControl) || !isset($this->childs->countControl)) {
			$countControlClassName = $this->getChildInstanceName('Controls_Count');
			$this->childs->countControl = new $countControlClassName($this);
		}
		return $this->childs->countControl->render();
	}

	public function renderOrderControl ()
	{
		if ((isset($this->childs->orderControl) && !$this->childs->orderControl) || !isset($this->childs->orderControl)) {
			$orderControlClassName = $this->getChildInstanceName('Controls_Order');
			$this->childs->orderControl = new $orderControlClassName($this);
		}
		return $this->childs->orderControl->render();
	}

	public function renderPageForm ()
	{
		if ((isset($this->childs->pageForm) && !$this->childs->pageForm) || !isset($this->childs->pageForm)) {
			$pageFormClassName = $this->getChildInstanceName('Forms_Page');
			$this->childs->pageForm = new $pageFormClassName($this);
		}
		return $this->childs->pageForm->render();
	}

	public function renderFilterForm ()
	{
		if ((isset($this->childs->filterForm) && !$this->childs->filterForm) || !isset($this->childs->filterForm)) {
			$filterFormClassName = $this->getChildInstanceName('Forms_Filter');
			$this->childs->filterForm = new $filterFormClassName($this);
		}
		return $this->childs->filterForm->render();
	}

	public function renderCountForm ()
	{
		if ((isset($this->childs->countForm) && !$this->childs->countForm) || !isset($this->childs->countForm)) {
			$countFormClassName = $this->getChildInstanceName('Forms_Count');
			$this->childs->countForm = new $countFormClassName($this);
		}
		return $this->childs->countForm->render();
	}

	/* all possible uris completer *********************************************/

	public function getPossibleUrlCompleter ()
	{
		$possibleUrlCompleterName = $this->getChildInstanceName('PossibleUrlCompleter');
		$possibleUrlCompleter = new $possibleUrlCompleterName($this);
		return $possibleUrlCompleter;
	}

	/**************************************************************************/

	protected function completeFilterLogicOperators ($filterOptions = array())
	{
		if (!isset($filterOptions['logicOperators'])) {
			$filterOptions['logicOperators'] = array();
		}
		$result = $filterOptions['logicOperators'];

		// check if there operator for cases between key and value
		if (!isset($result['keyValue'])) {
			$result['keyValue'] = array($this->defaultLogicOperators->keyValue);
		}

		// check if there operator for cases between multiple kay & value definitions
		if (!isset($result['multipleValues'])) {
			$result['multipleValues'] = $this->defaultLogicOperators->multipleValues;
		}

		return $result;
	}

	protected function initUrlBuilderAndCompleteModelConfig ()
	{
		$this->initUrlBuilder();

		// if we want to display all items => count=0 and page is higher than 1, redirect user to page=1, we don't want to have a duplicit content, do we?
		if ($this->countPerPage === 0 && $this->page > 1) {
			$this->_redirect(
				$this->getUrl(1,'page'),
				array(
					'code'  => 301,
					'exit'  => TRUE,
				)
			);
		}

		// if we have incoming request with zero count per page and we have not permited all items per page - redirect uset to minimal count per page
		if ($this->countPerPageMax !== 0 && $this->childs->urlParser->parsedSectionStrings->count === '0') {
			$this->_redirect(
				$this->getUrl($this->countScale[0],'count'),
				array(
					'code'  => 301,
					'exit'  => TRUE,
				)
			);
		}

		$modelCompleterClassName = $this->getChildInstanceName('ModelConfigCompleter');
		$this->childs->modelConfigCompleter = new $modelCompleterClassName($this);
		$this->childs->modelConfigCompleter->completeAll();
	}

	protected function initUrlBuilder ()
	{
		$urlBuilderClassName = $this->getChildInstanceName('UrlBuilder');
		$this->childs->urlBuilder = new $urlBuilderClassName($this);
		$this->childs->urlBuilder
			->setParsedSectionStrings(
				$this->childs->urlParser->getParsedSectionStrings()
			)
			->initUrlBuilerProperties()
		;
	}

}

function includeAllGridComponentClasses ($gridDirectoryPath) {
	$gridDirectoryPath = $gridDirectoryPath . '/';
	$gridDirectoryIterator = new DirectoryIterator($gridDirectoryPath);
	$objects = (object) array('files'=>array(),'dirs'=>array());
	foreach ($gridDirectoryIterator as $gridFileinfo) {
		if (!$gridFileinfo->isDot()) {
			if ($gridFileinfo->isDir()) {
				$objects->dirs[] = $gridDirectoryPath . $gridFileinfo->getFilename();
			} else {
				$gridFilePath = $gridDirectoryPath . $gridFileinfo->getFilename();
				if (strpos($gridFilePath, '.php') !== FALSE && strrpos($gridFilePath, '.php') === strlen($gridFilePath) - 4) {
					$objects->files[] = $gridDirectoryPath . $gridFileinfo->getFilename();
				}
			}
		}
	}
	asort($objects->files);
	asort($objects->dirs);
	foreach ($objects->files as $file) {
		include_once($file);
	}
	foreach ($objects->dirs as $dir) {
		includeAllGridComponentClasses($dir);
	}
}
includeAllGridComponentClasses(__DIR__ . '/Grid');
