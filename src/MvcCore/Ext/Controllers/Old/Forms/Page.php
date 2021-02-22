<?php

class Grid_Forms_Page extends Grid_ChildsConstructor
{
	protected $renderedResult;

	protected $form;
	protected $pageName = 'page';
	protected $pagesCountName = 'count';

	public function submit ()
	{
		$rawPage = $this->request->getParam($this->pageName);
		$rawPagesCount = $this->request->getParam($this->pagesCountName);

		$page = (int) preg_replace("#[^0-9]#", "", $rawPage);
		$pagesCount = (int) preg_replace("#[^0-9]#", "", $rawPagesCount);

		if ($page < 1 || $page > $pagesCount) {
			$page = $pagesCount;
		}
		$this->grid->page = $page;

		$this->grid->initUrlBuilder();

		$redirectUrl = $this->grid->getUrl(
			$this->grid->page,
			'page'
		);

		$this->_redirect(
		    $redirectUrl,
		    array(
				'code'  => 301,
				'exit'  => TRUE,
		    )
		);
	}

	public function render ()
	{
		if (!$this->grid->renderBooleans->pageForm) return;

		if (gettype($this->renderResult) != 'string') {

			$params = new stdClass;

			$this->setupFormElements();

			$params->rawControlId = $this->getRawContainerId();
			$params->form = $this->form;
			$params->pageName = $this->pageName;
			$params->pagesCountName = $this->pagesCountName;
			$params->grid = $this->grid;

			$this->renderResult = $this->renderView(
				$this->templates->pageForm,
				$params
			);

		}

		return $this->completeRawContainerIds($this->renderResult);
	}

	protected function setupFormElements ()
	{
		$this->form = new Zend_Form;

		$this->form->addElement('text', $this->pageName, array(
			'filters'	=> array('StringTrim', 'StripTags'),
			'decorators'=> array(
				'ViewHelper',
				'Errors',
			),
			'maxlength' => 10,
			'class'		=>'text',
			'id'		=> $this->getRawContainerElementId($this->pageName),
			'value'		=> $this->page,
		));

		$itemsCount = $this->grid->resultsListCount;
		$pagesCount = ceil($itemsCount / $this->countPerPage);
		$this->form->addElement('hidden', $this->pagesCountName, array(
			'filters'	=> array('StringTrim', 'StripTags'),
			'decorators'=> array(
				'ViewHelper',
				'Errors',
			),
			'maxlength' => 10,
			'class'		=>'hidden',
			'id'		=> $this->getRawContainerElementId($this->pagesCountName),
			'value'		=> $pagesCount,
		));
	}

}

