<?php

/**
 * MvcCore
 *
 * This source file is subject to the BSD 3 License
 * For the full copyright and license information, please view
 * the LICENSE.md file that are distributed with this source code.
 *
 * @copyright	Copyright (c) 2016 Tom Flidr (https://github.com/mvccore)
 * @license		https://mvccore.github.io/docs/mvccore/5.0.0/LICENSE.md
 */

namespace MvcCore\Ext\Controllers\DataGrids\Models;

/**
 * @mixin \MvcCore\Model|\MvcCore\Ext\Models\Db\Model
 */
trait GridColumns {

	/**
	 * Return array of datagrid columns config.
	 * This method is called by datagrid component to parse decorated 
	 * model properties to complete datagrid columns configuration automatically.
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column[]
	 */
	public function GetConfigColumns () {
		/** @var \MvcCore\Ext\Controllers\DataGrids\Models\IGridModel $this */
		$modelMetaData = [];
		$toolClass = \MvcCore\Application::GetInstance()->GetToolClass();
		$implementsExtendedModel = $toolClass::CheckClassInterface(
			get_class($this), 'MvcCore\\Ext\\Models\\Db\\IModel', FALSE, FALSE
		);
		$accessModFlags = 0;
		if ($implementsExtendedModel) {
			/** @var \MvcCore\Ext\Models\Db\Model $context */
			$context = $this;
			list($metaData) = $context::GetMetaData(0);
			foreach ($metaData as $propData) {
				$dbColumnName = $propData[4];
				$types = $propData[2];
				$formatArgs = $propData[5];
				if ($dbColumnName !== NULL) {
					$propertyName = $propData[3];
					$modelMetaData[$propertyName] = [$dbColumnName, $types, $formatArgs];
				}
			}
			if (static::$defaultPropsFlags !== 0)
				$accessModFlags = static::$defaultPropsFlags;
		}
		return \MvcCore\Ext\Controllers\DataGrid::ParseConfigColumns(
			$this, $modelMetaData, $accessModFlags
		);
	}
}
