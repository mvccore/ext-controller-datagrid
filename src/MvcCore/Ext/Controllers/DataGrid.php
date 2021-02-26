<?php

namespace MvcCore\Ext\Controllers;

class		DataGrid 
extends		\MvcCore\Controller
implements	\MvcCore\Ext\Controllers\IDataGrid {

	use \MvcCore\Ext\Controllers\DataGrid\ConfigProps,
		\MvcCore\Ext\Controllers\DataGrid\ConfigGettersSetters,
		\MvcCore\Ext\Controllers\DataGrid\InternalProps,
		\MvcCore\Ext\Controllers\DataGrid\InternalGettersSetters,
		\MvcCore\Ext\Controllers\DataGrid\InitMethods,
		\MvcCore\Ext\Controllers\DataGrid\PreDispatchMethods,
		\MvcCore\Ext\Controllers\DataGrid\RenderMethods;
}
