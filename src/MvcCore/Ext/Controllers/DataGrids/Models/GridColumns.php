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
		if ($implementsExtendedModel) {
			/** @var $this \MvcCore\Ext\Models\Db\Model\MetaData */
			list($metaData) = static::getMetaData(0);
			foreach ($metaData as $propData) {
				$dbColumnName = $propData[4];
				if ($dbColumnName !== NULL) {
					$propertyName = $propData[3];
					$modelMetaData[$propertyName] = $dbColumnName;
				}
			}
		}
		return \MvcCore\Ext\Controllers\DataGrid::ParseConfigColumns(
			$this, $modelMetaData
		);
	}
}
