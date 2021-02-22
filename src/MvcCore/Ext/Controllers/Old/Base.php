<?php

class Grid_Base
{
  protected $urlPrefixes = array(
    'page'    => '',
    'count'    => '',
    'order'    => 'order',
    'filter'  => 'filter',
  );

  protected $urlSufixes = array(
    'orderAsc'  => 'a',
    'orderDesc'  => 'd',
  );

  protected $urlDelimiters = array(
    'sections'      => '/',
    'subjects'      => '-',
    'values'      => '.',
    'multipleValues'  => '.',
  );

  protected $defaultLogicOperators = array(
    'keyValue'      => '=',
    'multipleValues'  => 'OR',
    'multipleSubjects'  => 'AND',
  );

  protected $routes = array(
    'ajaxSubmit'  => array(
      'pattern'    => '#^/ajax-submit(.*)$#',
      'reverse'    => '/ajax-submit{%__grid_params__}',
      'class'      => 'Ajax',
      'method'    => 'submit',
    ),
    'filterSubmit'  => array(
      'pattern'    => '#^/filter-form-submit(.*)$#',
      'reverse'    => '/filter-form-submit{%__grid_params__}',
      'class'      => 'Forms_Filter',
      'method'    => 'submit',
    ),
    'pageSubmit'  => array(
      'pattern'    => '#^/page-form-submit(.*)$#',
      'reverse'    => '/page-form-submit{%__grid_params__}',
      'class'      => 'Forms_Page',
      'method'    => 'submit',
    ),
    'countSubmit'  => array(
      'pattern'    => '#^/count-form-submit(.*)$#',
      'reverse'    => '/count-form-submit{%__grid_params__}',
      'class'      => 'Forms_Count',
      'method'    => 'submit',
    ),
    'list'      => array(
      'pattern'    => '#^(.*)$#',
      'reverse'    => '{%__grid_params__}',
      'class'      => '',
      'method'    => 'initUrlBuilderAndCompleteModelConfig',
    ),
  );

  protected $templates = array(
    'pageControl'  => '__DIR__/Controls/page.phtml',
    'orderControl'  => '__DIR__/Controls/order.phtml',
    'countControl'  => '__DIR__/Controls/count.phtml',
    'pageForm'    => '__DIR__/Forms/page.phtml',
    'filterForm'  => '__DIR__/Forms/filter.phtml',
    'countForm'    => '__DIR__/Forms/count.phtml',
  );

  protected $renderBooleans = array(
    'pageControl'  => FALSE,
    'orderControl'  => FALSE,
    'countControl'  => FALSE,
    'pageForm'    => FALSE,
    'filterForm'  => FALSE,
    'countForm'    => FALSE,
  );


  protected $name = '';
  protected $namePrefix = 'grid';
  protected $nameDelimiter = '-';
  protected static $nameIterator = 0;
  protected $htmlContainerIdReplacement = '__GRID_HTML_CONTAINER_ID_REPLACEMENT__';

  protected $pageDefault = 1;
  protected $countPerPageDefault = 10;
  protected $orderDefault = array();
  protected $filterDefault = array();
  protected $countScale = array(10, 20, 40);

  protected $childs = array();

  protected $request;
  protected $baseUrl;
  protected $routeName = '';
  protected $routeParam = 'grid_url';

  protected $privateRouteName = '';
  protected $privateRouteParam = '__grid_params__';

  protected $defaultValueTranslator;/* init later */
  protected $countPerPageMax = 50; // 0 means unlimited
  protected $urlAliasKeys = array();

  protected $modelConfig = array();
  protected $resultsList;
  protected $resultsListCount;
  
  
  public function  __construct ($customGridProperties = array())
  {
    // init default value translator lambda function
    $this->defaultValueTranslator = function ($value = '', $databaseValueToUriForm = TRUE) {
      if ($databaseValueToUriForm) {
        return $value;
      } else {
        return preg_replace("#[^0-9]#", "", $value);
      }
    };
    /**
     * set up any value of protected property for whole grid instance throw base construct method
     * to customize for example diferent aliases in diferent grid instances etc...
     */
    foreach ($customGridProperties as $propertyKey => $propertyValue) {
      if (gettype($this->$propertyKey) == 'array' && gettype($propertyValue) == 'array' && $propertyKey != 'countScale') {
        $arr1 = $this->$propertyKey;
        foreach ($this->$propertyKey as $propertySubKey => $propertySubValue) {
          if (isset($propertyValue[$propertySubKey])) {
            if (gettype($arr1[$propertySubKey]) == 'array' && gettype($propertyValue[$propertySubKey]) == 'array') {
              foreach ($propertyValue[$propertySubKey] as $propertySubSubKey => $propertySubSubValue) {
                $arr1[$propertySubKey][$propertySubSubKey] = $propertySubSubValue;
              }
            } else {
              $arr1[$propertySubKey] = $propertyValue[$propertySubKey];
            }
          }
        }
        $this->$propertyKey = $arr1;
      } else {
        if ($propertyKey == 'countScale' && gettype($propertyValue) == 'array') {
          $this->$propertyKey = array_unique($propertyValue);
        } else {
          $this->$propertyKey = $propertyValue;
        }
      }
    }

    // make life easier
    $this->urlPrefixes      = (object) $this->urlPrefixes;
    $this->urlSufixes      = (object) $this->urlSufixes;
    $this->urlDelimiters    = (object) $this->urlDelimiters;
    $this->defaultLogicOperators= (object) $this->defaultLogicOperators;
    $this->templates      = (object) $this->templates;
    $this->renderBooleans    = (object) $this->renderBooleans;
    $this->childs        = (object) $this->childs;
    $this->htmlIdsReplacements  = (object) $this->htmlIdsReplacements;

    // complete grid name
    if (!$this->name) {
      self::$nameIterator += 1;
      $this->name = self::$nameIterator;
    }
    $this->name = (($this->namePrefix) ? $this->namePrefix . $this->nameDelimiter : '' ) . $this->name;
  }
  
  public function __get ($rawPropertyName)
  {
    $getterStr = lcfirst(str_replace('get', '', $rawPropertyName));
    if (isset($this->$getterStr) && gettype($this->$getterStr) !== 'NULL') {
      return $this->$getterStr;
    } else {
      return NULL;
    }
  }

  protected function getPossibleUrlAliasKey ($key, $urlAliasKeys, $backwards = FALSE)
  {
    $result = $key;

    if ($backwards) {
      foreach ($urlAliasKeys as $databaseKey => $urlAliasKey) {
        if ($key == $databaseKey) {
          $result = $urlAliasKey;
          break;
        }
      }
    } else {
      foreach ($urlAliasKeys as $databaseKey => $urlAliasKey) {
        if ($key == $urlAliasKey) {
          $result = $databaseKey;
          break;
        }
      }
    }

    return $result;
  }

  protected function getChildInstanceName ($gridClassSubName = '')
  {
    return 'Grid_' . $gridClassSubName;
  }

  protected function renderView ($templatePath = '', $params = array())
  {
    ob_start();

    $templatePath = str_replace('__DIR__', __DIR__, $templatePath);
    $templatePath = str_replace('\\', '/', $templatePath);
    $templateScriptPath = substr($templatePath, 0, strrpos($templatePath, '/'));

    $view = new Pimcore_View();

    $view->setScriptPath($templateScriptPath);
    $view->addHelperPath('Zend/Controller/Action/Helper/', 'Zend_Controller_Action_Helper_');
    $view->addHelperPath('Website/View/', 'Website_View_');

    foreach ($params as $paramKey => $paramValue) {
      $view->$paramKey = $paramValue;
    }

    $view->template($templatePath, $view);
    // $view->render($templatePath);

    return ob_get_clean();
  }

  protected function _redirect ($url = '')
  {
    if ($url) {
      header("HTTP/1.0 303 See Other");
      header('Location: ' . $url);
      exit;
    }
  }

}

