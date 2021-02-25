<?php

namespace MvcCore\Ext\Controllers\DataGrids\Models;

trait GridColumns {

	/**
	 * Return array of datagrid columns config.
	 * @return \MvcCore\Ext\Controllers\DataGrids\Configs\Column[]
	 */
	public function GetConfigColumns () {
		/** @var $this \MvcCore\Ext\Controllers\DataGrids\Models\IGridModel */
		$modelMetaData = [];
		$toolClass = \MvcCore\Application::GetInstance()->GetToolClass();
		$implementsExtendedModel = $toolClass::CheckClassInterface(
			get_class($this), 'MvcCore\\Ext\\Models\\Db\\IModel', FALSE, FALSE
		);
		$accessModFlags = 0;
		if ($implementsExtendedModel) {
			/** @var $context \MvcCore\Ext\Models\Model */
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
